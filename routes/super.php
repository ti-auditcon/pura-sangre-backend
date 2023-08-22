<?php 

use Carbon\Carbon;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Clases\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\Clases\ReservationStatus;

Route::middleware(['auth'])->prefix('/')->group(function () {
    /**
     * Calibrate reservations fro a user
     */
    Route::get('users/{user}/calibrate-reservations-plans', function(App\Models\Users\User $user) {
        $user_plans = App\Models\Plans\PlanUser::where('plan_user.user_id', $user->id)
                                ->whereIn('plan_user.plan_status_id', [
                                    App\Models\Plans\PlanStatus::ACTIVE,
                                    App\Models\Plans\PlanStatus::PRE_PURCHASE,
                                    App\Models\Plans\PlanStatus::FINISHED
                                ])
                                ->join('plans', 'plan_user.plan_id', 'plans.id')
                                ->get([
                                    'plans.id as planId', 'plans.class_numbers',
                                    'plan_user.id as planUserId', 'plan_user.user_id', 'plan_user.plan_status_id',
                                    'plan_user.start_date', 'plan_user.finish_date', 'plan_user.counter', 'plan_user.plan_id'
                                ]);

        foreach ($user_plans as $plan_user) {
            $reservations = App\Models\Clases\Reservation::leftJoin('clases', 'reservations.clase_id', '=', 'clases.id')
                                                            ->where('reservations.user_id', $user->id)
                                                            ->where('clases.date', '>=', $plan_user->start_date)
                                                            ->where('clases.date', '<=', $plan_user->finish_date)
                                                            ->get([
                                                                'reservations.id as reservationId',
                                                                'reservations.user_id', 'reservations.clase_id',
                                                                'reservations.reservation_status_id', 
                                                                'clases.id as claseId', 'clases.date',
                                                            ]);
            foreach ($reservations as $reservation) {
                if ($reservation->reservation_status_id === ReservationStatus::CONFIRMED && Carbon::parse($reservation->date) < today()) {
                    Reservation::find($reservation->reservationId)->update([
                        'reservation_status_id' => ReservationStatus::CONSUMED,
                        'plan_user_id' => $plan_user->planUserId
                    ]);
                } else {
                    Reservation::find($reservation->reservationId)->update([
                        'plan_user_id' => $plan_user->planUserId
                    ]);
                }
            }

            $plan_user->update(['counter' => $plan_user->class_numbers - count($reservations)]);
        }
    });

    // Route::get('add-one-day-to-users-plans', function() {
    //     $currentDate = now();

    //     $plansToUpdate = DB::table('plan_user')
    //         ->whereNull('deleted_at')
    //         ->where('finish_date', '>=', $currentDate)
    //         ->where('plan_status_id', '!=', App\Models\Plans\PlanStatus::FINISHED)
    //         ->orderByDesc('finish_date')
    //         ->get();

    //     foreach ($plansToUpdate as $plan) {
    //         $updateData = [];
    //         $newFinishDate = Carbon::parse($plan->finish_date)->addDay();

    //         // If the plan's start date is before the current date
    //         if (Carbon::parse($plan->start_date)->lt($currentDate)) {
    //             $updateData['finish_date'] = $newFinishDate;
    //         } else {
    //             $updateData['start_date'] = Carbon::parse($plan->start_date)->addDay();
    //             $updateData['finish_date'] = $newFinishDate;
    //         }

    //         DB::table('plan_user')
    //             ->where('id', $plan->id)
    //             ->update($updateData);
    //     }

    //     return response()->json([
    //         'message' => 'Planes actualizados correctamente.'
    //     ]);
    // });

    // Route::get('update-past-reservations', function() {
    //     DB::table('reservations')
    //         ->where('reservations.reservation_status_id', ReservationStatus::CONFIRMED)
    //         ->join('clases', 'reservations.clase_id', '=', 'clases.id')
    //         ->where('clases.date', '<', now()->format('Y-m-d H:i:s'))
    //         ->update(['reservations.reservation_status_id' => ReservationStatus::CONSUMED]);

    //     DB::table('reservations')
    //         ->where('reservations.reservation_status_id', ReservationStatus::PENDING)
    //         ->join('clases', 'reservations.clase_id', '=', 'clases.id')
    //         ->where('clases.date', '<', now()->format('Y-m-d H:i:s'))
    //         ->update(['reservations.reservation_status_id' => ReservationStatus::LOST]);
    // });
});