<?php 

use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;

Route::middleware(['auth'])->prefix('/')->group(function () {
    /** add 6 days to every plan of all users */
    Route::get('add-days-active-or-pre-purchase-plans', function() {
        $days = 6;

        $users = User::join('plan_user', 'plan_user.user_id', '=', 'users.id')
                        ->where('plan_user.plan_status_id', PlanStatus::ACTIVE)
                        ->orWhere('plan_user.plan_status_id', PlanStatus::PRECOMPRA)
                        ->distinct()
                        ->get(['users.id']);

        // getting the dispatcher instance (needed to enable again the event observer later on)
        $dispatcher = PlanUser::getEventDispatcher();
        // disabling the events
        PlanUser::unsetEventDispatcher();

        foreach ($users as $user) {
            $plan_user = PlanUser::where('user_id', $user->id)
                            ->whereIn('plan_status_id', [PlanStatus::ACTIVE, PlanStatus::PRECOMPRA])
                            ->orderBy('finish_date', 'asc')
                            ->first([
                                'id', 'start_date', 'finish_date', 'user_id'
                            ]);

            if ($plan_user) {
                $plan_user->finish_date = $plan_user->finish_date->addDays($days);
                $plan_user->save();

                $planes_posteriores = PlanUser::where('user_id', $user->id)
                                                ->where('start_date', '>', $plan_user->start_date)
                                                ->where('id', '!=', $plan_user->id)
                                                ->orderByDesc('finish_date')
                                                ->get([
                                                    'id', 'start_date', 'finish_date', 'user_id'
                                                ]);

                foreach ($planes_posteriores as $plan) {
                    $plan->update([
                        'start_date'  => $plan->start_date->addDays($days),
                        'finish_date' => $plan->finish_date->addDays($days)
                    ]);
                }
            }
        }

        // enabling the event dispatcher
        PlanUser::setEventDispatcher($dispatcher);

        return 'all done';
    });

    /**
     *  Calibrate reservations fro a user
     */
    Route::get('users/{user}/calibrate-reservations-plans', function(App\Models\Users\User $user) {
        $user_plans = App\Models\Plans\PlanUser::where('plan_user.user_id', $user->id)
                                ->whereIn('plan_user.plan_status_id', [
                                    App\Models\Plans\PlanStatus::ACTIVO,
                                    App\Models\Plans\PlanStatus::PRECOMPRA,
                                    App\Models\Plans\PlanStatus::COMPLETADO
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
});