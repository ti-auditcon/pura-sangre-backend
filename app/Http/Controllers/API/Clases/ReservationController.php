<?php

namespace App\Http\Controllers\API\Clases;

use Illuminate\Http\Request;
use App\Models\Clases\Clase;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;
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
     * El ReservationObserver valida plan, cupos y descuenta créditos automáticamente.
     */
    public function store(Request $request)
    {
        $request->validate([
            'clase_id' => 'required|integer|exists:clases,id',
        ]);

        $clase = Clase::findOrFail($request->clase_id);

        // Verificaciones previas para retornar errores JSON descriptivos
        if ($clase->isFull()) {
            return response()->json(['error' => 'La clase está llena.'], 422);
        }

        $alreadyBooked = Reservation::where('clase_id', $clase->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($alreadyBooked) {
            return response()->json(['error' => 'Ya tienes una reserva para esta clase.'], 422);
        }

        $reservation = Reservation::create([
            'clase_id'              => $clase->id,
            'reservation_status_id' => ReservationStatus::PENDING,
            'user_id'               => auth()->id(),
            'by_god'                => 0,
        ]);

        // Si el Observer canceló la creación (plan inválido, sin créditos, etc.)
        if (!$reservation || !$reservation->exists) {
            return response()->json(['error' => 'No se pudo crear la reserva. Verifica que tienes un plan activo con clases disponibles.'], 422);
        }

        return response()->json($reservation->load([
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
