<?php

namespace App\Http\Controllers\API\Clases;

use Carbon\Carbon;
use App\Models\Clases\Clase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClaseController extends Controller
{
    /**
     * Devuelve las clases de un rango de fechas (por defecto la semana actual).
     * Para la PWA de alumnos.
     */
    public function index(Request $request)
    {
        // Sanitize: extract only the date part (YYYY-MM-DD) in case a malformed
        // string like "2026-03-1616" is sent instead of "2026-03-16".
        $sanitize = fn($value) => $value ? preg_replace('/^(\d{4}-\d{2}-\d{2}).*$/', '$1', $value) : null;

        $startInput = $sanitize($request->input('start'));
        $endInput   = $sanitize($request->input('end'));

        $start = $startInput
            ? Carbon::parse($startInput)->startOfDay()
            : Carbon::now()->startOfDay();

        $end = $endInput
            ? Carbon::parse($endInput)->endOfDay()
            : Carbon::now()->addDays(6)->endOfDay();

        $userId = auth()->id();

        $clases = Clase::with([
                'claseType:id,clase_type,clase_color,icon_white',
                'coach:id,first_name,last_name',
                'reservations' => fn ($q) => $q->where('user_id', $userId)
                    ->select(['id', 'clase_id', 'reservation_status_id']),
            ])
            ->withCount('reservations')
            ->whereHas('claseType')
            ->whereBetween('date', [$start, $end])
            ->orderBy('date')
            ->orderBy('start_at')
            ->get(['id', 'date', 'start_at', 'finish_at', 'quota', 'room', 'clase_type_id', 'coach_id'])
            ->map(function ($clase) {
                $clase->is_full = $clase->quota > 0 && $clase->reservations_count >= $clase->quota;
                $myRes = $clase->reservations->first();
                $clase->my_reservation_id = $myRes ? $myRes->id : null;
                $clase->unsetRelation('reservations'); // no exponer la colección en el JSON
                return $clase;
            });

        return response()->json($clases);
    }

    /**
     * Detalle de una clase con la reserva del usuario autenticado.
     */
    public function show(Clase $clase)
    {
        $clase->load([
            'claseType:id,clase_type,clase_color',
            'coach:id,first_name,last_name,avatar',
        ]);
        $clase->loadCount('reservations');

        $myReservation = $clase->reservations()
            ->where('user_id', auth()->id())
            ->with('reservation_status:id,reservation_status,type')
            ->first(['id', 'reservation_status_id']);

        $students = $clase->reservations()
            ->whereIn('reservation_status_id', [1, 2, 3])
            ->with('user:id,first_name,last_name,avatar')
            ->get(['id', 'user_id', 'reservation_status_id'])
            ->map(fn ($r) => [
                'name'                   => $r->user->first_name . ' ' . $r->user->last_name,
                'avatar'                 => $r->user->avatar,
                'reservation_status_id'  => $r->reservation_status_id,
            ]);

        return response()->json([
            'clase'          => $clase,
            'is_full'        => $clase->isFull(),
            'my_reservation' => $myReservation,
            'students'       => $students,
        ]);
    }
}
