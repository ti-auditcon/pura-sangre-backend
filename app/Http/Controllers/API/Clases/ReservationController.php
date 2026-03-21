<?php

namespace App\Http\Controllers\API\Clases;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Clases\Clase;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Http\Controllers\Controller;

class ReservationController extends Controller
{
    /**
     * Reservas futuras (y de hoy) del alumno autenticado.
     */
    public function index()
    {
        $reservations = Reservation::where('user_id', auth()->id())
            ->with([
                'clase:id,date,start_at,finish_at,quota,room,clase_type_id,coach_id',
                'clase.claseType:id,clase_type,clase_color',
                'clase.coach:id,first_name,last_name',
                'reservation_status:id,reservation_status,type',
            ])
            ->whereHas('clase', fn ($q) => $q->where('date', '>=', now()->startOfDay()))
            ->get()
            ->sortBy('clase.date')
            ->values();

        return response()->json($reservations);
    }

    /**
     * Crear una reserva para el alumno autenticado.
     *
     * Validaciones (en orden):
     *  1. Plan activo en la fecha de la clase.
     *  2. No estar ya inscrito en esa clase.
     *  3. Si la clase es especial → reservar directamente sin consumir cupo.
     *  4. Tener cupos disponibles en el plan (counter > 0).
     *  5. Clase con capacidad disponible (isFull).
     *  6. No tener otra reserva del mismo tipo ese día.
     *  7. El plan cubre el bloque horario de la clase.
     *
     * El ReservationObserver se encarga de asignar plan_user_id y descontar 1
     * crédito en el hook created() (ya contempla omitir el descuento para clases especiales).
     */
    public function store(Request $request)
    {
        $request->validate([
            'clase_id' => 'required|integer|exists:clases,id',
        ]);

        $clase = Clase::with(['claseType', 'block.plans'])->findOrFail($request->clase_id);
        $user  = auth()->user();

        // 0. La clase no debe haber comenzado ya
        $classStart = $clase->date->copy()->setTimeFromTimeString($clase->start_at);
        if ($classStart->lte(now())) {
            return response()->json(['error' => 'No puedes reservar una clase que ya ha comenzado.'], 403);
        }

        // 1. Plan activo en la fecha de la clase
        $planUser = PlanUser::where('user_id', $user->id)
            ->whereIn('plan_status_id', [PlanStatus::ACTIVE, PlanStatus::PRE_PURCHASE])
            ->where('start_date', '<=', $clase->date)
            ->where('finish_date', '>=', $clase->date)
            ->first();

        if (!$planUser) {
            return response()->json(['error' => 'No tienes un plan activo para la fecha de esta clase.'], 403);
        }

        // Sección crítica encapsulada en transacción con lock exclusivo sobre la clase.
        // Cualquier petición concurrente para la misma clase bloqueará hasta que esta termine,
        // garantizando que los conteos de cupo y reservas sean siempre precisos.
        $result = DB::transaction(function () use ($clase, $user, $planUser) {
            // Re-fetch con lockForUpdate — serializa peticiones concurrentes sobre la misma fila
            $clase = Clase::with(['claseType', 'block.plans'])->lockForUpdate()->findOrFail($clase->id);

            // 2. Ya inscrito en esta clase
            if (Reservation::where('clase_id', $clase->id)->where('user_id', $user->id)->exists()) {
                return ['error' => 'Ya tienes una reserva para esta clase.', 'status' => 403];
            }

            // 3. Clase especial: reservar directamente sin consumir cupo ni validar slots
            if ($clase->claseType && $clase->claseType->special) {
                $reservation = Reservation::create([
                    'clase_id'              => $clase->id,
                    'reservation_status_id' => ReservationStatus::PENDING,
                    'user_id'               => $user->id,
                    'by_god'                => 0,
                ]);
                if (!$reservation->exists) {
                    return ['error' => 'No se pudo crear la reserva para esta clase especial.', 'status' => 422];
                }
                return ['reservation' => $reservation];
            }

            // 4. Cupos disponibles en el plan
            if ($planUser->counter <= 0) {
                return ['error' => 'No te quedan clases disponibles en tu plan.', 'status' => 403];
            }

            // 5. Capacidad disponible en la clase (conteo fresco dentro del lock)
            if ($clase->isFull()) {
                return ['error' => 'La clase está llena.', 'status' => 403];
            }

            // 6. No tener otra reserva del mismo tipo de clase ese día
            // Usamos ->whereDate() (aplica DATE() en SQL) para comparar correctamente con la
            // columna DATETIME, igual que hace el Observer internamente.
            $sameDayConflict = Reservation::where('user_id', $user->id)
                ->whereHas('clase', fn ($q) => $q->whereDate('date', $clase->date->toDateString())
                                                 ->where('clase_type_id', $clase->clase_type_id))
                ->exists();

            if ($sameDayConflict) {
                return ['error' => 'Ya tienes una clase de este tipo reservada para ese día.', 'status' => 403];
            }

            // 7. El plan del usuario está habilitado para el bloque horario de la clase
            if ($clase->block && !$clase->block->plans->contains('id', $planUser->plan_id)) {
                return ['error' => 'Tu plan no está habilitado para este bloque horario.', 'status' => 403];
            }

            // Crear reserva — el ReservationObserver descuenta 1 crédito del plan en created()
            $reservation = Reservation::create([
                'clase_id'              => $clase->id,
                'reservation_status_id' => ReservationStatus::PENDING,
                'user_id'               => $user->id,
                'by_god'                => 0,
            ]);

            // Guardia de seguridad: si el Observer canceló la creación silenciosamente
            // (p.ej. encontró un conflicto que nuestros checks no detectaron), devolvemos error.
            if (!$reservation->exists) {
                return ['error' => 'No se pudo crear la reserva. Verifica tu plan y que no tengas otra clase del mismo tipo este día.', 'status' => 422];
            }

            return ['reservation' => $reservation];
        });

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json($result['reservation']->load([
            'clase:id,date,start_at,finish_at,quota,room,clase_type_id',
            'clase.claseType:id,clase_type,clase_color',
            'reservation_status:id,reservation_status,type',
        ]), 201);
    }

    /**
     * Confirmar una reserva pendiente del alumno autenticado.
     */
    public function confirm(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }

        if ($reservation->reservation_status_id !== ReservationStatus::PENDING) {
            return response()->json(['error' => 'Solo se pueden confirmar reservas pendientes.'], 422);
        }

        $reservation->update(['reservation_status_id' => ReservationStatus::CONFIRMED]);

        return response()->json($reservation->load([
            'reservation_status:id,reservation_status,type',
        ]));
    }

    /**
     * Eliminar (cancelar) una reserva del alumno autenticado.
     * El ReservationObserver restaura el crédito del plan automáticamente.
     */
    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }

        if ($reservation->reservation_status_id === ReservationStatus::CONFIRMED) {
            return response()->json(['error' => 'No se puede cancelar una reserva confirmada.'], 422);
        }

        $reservation->delete();

        return response()->json(['success' => true]);
    }
}
