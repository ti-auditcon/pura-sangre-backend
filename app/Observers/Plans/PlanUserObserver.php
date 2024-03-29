<?php

namespace App\Observers\Plans;

use Carbon\Carbon;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Users\StatusUser;
use App\Models\Clases\Reservation;
use App\Models\Plans\PostponePlan;
use App\Models\Plans\PlanIncomeSummary;

class PlanUserObserver
{
    /**
     * [creating description]
     * 
     * @param  PlanUser $planUser [description]
     * 
     * @return [type]             [description]
     */
    public function creating(PlanUser $planUser)
    {
        $planUser->plan_status_id = $this->checkActualPlan($planUser);
    }

    /**
     * Handle the plan user "created" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function created(PlanUser $planUser)
    {
        $planUser->fixReservations();
 
        $planUser->user->updateStatus();
        // $this->updateStatusUser($planUser);
    }

    /**
     * [updating description]
     *
     * @param  PlanUser $planUser [description]
     * @return [type]             [description]
     */
    public function updating(PlanUser $planUser)
    {
        // Skip "updating" observer if plan is cancelled
        if ($this->planUserIsBeingCancelled($planUser->plan_status_id)) {
            return;
        }

        if ($planUser->isFrozen()) {
            return;
        }

        // $user = User::findOrFail($planUser->user_id);

        // $fecha_inicio = Carbon::parse($planUser->start_date);
        // $fecha_termino = Carbon::parse($planUser->finish_date);

        // $plan_users = PlanUser::whereIn('plan_status_id', [1, 3])
        //                         ->where('user_id', $user->id)
        //                         ->where('id', '!=', $planUser->id)
        //                         ->get();

        // foreach ($plan_users as $plan_user) {
        //     $start_date = Carbon::parse($plan_user->start_date);

        //     $finish_date = Carbon::parse($plan_user->finish_date);

        //     if ( $fecha_inicio->between($start_date, $finish_date) || $fecha_termino->between($start_date, $finish_date)) {
        //         Session::flash(
        //             'error-tap',
        //             'No se pudo actualizar las fechas, debido a que el plan ' . $plan_user->plan->plan . ' que va desde el ' . $start_date->format('d-m-Y') . ' al ' . $finish_date->format('d-m-Y') . ' choca con una fecha del plan que intentas modificar');

        //         return false;
        //     }

        //     if ( $fecha_inicio->lt($start_date) && $fecha_termino->gt($finish_date) ) {
        //         Session::flash(
        //             'error-tap',
        //             'No se pudo actualizar las fechas, debido a que el plan ' . $plan_user->plan->plan . ' que va desde el ' . $start_date->format('d-m-Y') . ' al ' . $finish_date->format('d-m-Y') . ', choca fecha del plan que intentas modificar'
        //         );

        //         return false;
        //     }

        //     if ( $fecha_inicio->gt($start_date) && $fecha_termino->lt($finish_date) ) {
        //         Session::flash(
        //             'error-tap',
        //             'No se pudo actualizar las fechas, debido a que el plan ' . $plan_user->plan->plan . ' que va desde el ' . $start_date->format('d-m-Y') . ' al ' . $finish_date->format('d-m-Y') . ', choca con una fecha del plan que intentas modificar');

        //         return false;
        //     }
        // }

        if ($planUser->plan_status_id != PlanStatus::CANCELED) {
            $planUser->plan_status_id = $this->checkActualPlan($planUser);
        }
    }

    /**
     * Check if the plan to be update is actually being cancelled
     *
     * @param   int   $planStatusId 
     *
     * @return  bool  
     */
    public function planUserIsBeingCancelled($planStatusId)
    {
        return $planStatusId === PlanStatus::CANCELED;
    }

    /**
     * We do the next steps:
     * 1. Check if the plan is canceled
     *  1.1 we delete all the reservations that are pending or confirmed
     *  1.2 we remove the associated planUsers associated to the reservations that are consumed or lost
     * 2. If the plan is not canceled we fix the reservations
     * 3. We update the status of the user
     * 
     * @param  PlanUser $planUser
     * 
     * @return void
     */
    public function updated(PlanUser $planUser)
    {
        if ($planUser->isCanceled()) {
            foreach ($planUser->reservations as $reservation) {
                if ($reservation->isPending() || $reservation->isConfirmed()) {
                    $reservation->delete();
                } elseif ($reservation->isConsumed() || $reservation->isLost()) {
                    $reservation->update(['plan_user_id' => null]);
                }
            }
        } else {
            $planUser->fixReservations();
        }

        $this->updateStatusUser($planUser);
    }

    /**
     * Handle the plan user "restored" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * 
     * @return void
     */
    public function deleted(PlanUser $planUser)
    {
        if ($planUser->bill) {
            $plan_income_sum = PlanIncomeSummary::where('month', Carbon::parse($planUser->bill->date)->month)
                ->where('year', Carbon::parse($planUser->bill->date)->year)
                ->where('plan_id', $planUser->plan_id)->first();
            if ($plan_income_sum) {
                $plan_income_sum->amount -= $planUser->bill->amount;
                $plan_income_sum->quantity -= 1;
                $plan_income_sum->save();
            }
            $planUser->bill->delete();
        }
    }

    /**
     * Undocumented function
     *
     * @param  PlanUser  $planUser
     * 
     * @return  void
     */
    public function checkActualPlan(PlanUser $planUser)
    {
        $planStatus = $planUser->getStatusByDates();

        if (
            $planUser->user->hasNotACurrentPlan() 
            && $planStatus === PlanStatus::ACTIVE 
            && $planUser->isNotFrozen() 
        ) {
            $planStatus = PlanStatus::ACTIVE;
        }

        return $planStatus;
    }

    /**
     * planUserIsFreeze
     *
     * @return  returnType
     */
    public function planUserIsFreeze($planUser)
    {
        $plan_is_freezed = PostponePlan::where('plan_user_id', $planUser->id)->exists('id');

        return $plan_is_freezed;
    }

    /**
     * Undocumented function
     *
     * @param PlanUser $planUser
     * @return void
     */
    public function fixReservations(PlanUser $planUser)
    {
        $reservations = Reservation::join('clases', 'reservations.clase_id', '=', 'clases.id')
            ->where('reservations.user_id', $planUser->user_id)
            ->where('date', '>=', Carbon::parse($planUser->start_date)->format('Y-m-d'))
            ->where('date', '<=', Carbon::parse($planUser->finish_date)->format('Y-m-d'))
            ->get('reservations.id');
            
        $reservations_out = Reservation::join('clases', 'reservations.clase_id', '=', 'clases.id')
            ->where('reservations.user_id', $planUser->user_id)
            ->whereNotBetween('date', [Carbon::parse($planUser->start_date)->format('Y-m-d'), Carbon::parse($planUser->finish_date)->format('Y-m-d')])
            ->get('reservations.id');
                                    
        PlanUser::withoutEvents(function () use ($planUser, $reservations, $reservations_out) {
            foreach ($reservations as $reserv) {
                $reservation = Reservation::find($reserv->id, ['id', 'plan_user_id']);
                
                if ($reservation->plan_user_id !== $planUser->id) {
                    $reservation->update(['plan_user_id' => $planUser->id]);
                    $planUser->counter -= 1;
                    $planUser->save();
                }
            }

            foreach ($reservations_out as $reserv) {
                $reservation = Reservation::find($reserv->id, ['id', 'plan_user_id']);

                if ($reservation->plan_user_id === $planUser->id) {
                    $reservation->update(['plan_user_id' => null]);
                    $planUser->counter += 1;
                    $planUser->save();
                }
            }
        });
        // foreach ($reservations as $reserv) {
        //     $reservation = Reservation::find($reserv->id, ['id', 'plan_user_id']);
            
        //     if ($reservation->plan_user_id !== $planUser->id) {
        //         $reservation->update(['plan_user_id' => $planUser->id]);
        //         $planUser->counter -= 1;
        //         $planUser->save();
        //     }
        //     dd($reserv);      
        // }

        // foreach ($reservations_out as $reserv) {
        //     $reservation = Reservation::find($reserv->id, ['id', 'plan_user_id']);

        //     if ($reservation->plan_user_id === $planUser->id) {
        //         $reservation->update(['plan_user_id' => null]);
        //         $planUser->counter += 1;
        //         $planUser->save();
        //     }
        // }

        return $planUser;
    }

    public function updateStatusUser(PlanUser $planUser)
    {
        $user = $planUser->user;

        if ($planUser->isCurrent() && $planUser->plan_status_id === PlanStatus::ACTIVE) {

            $user->status_user_id = $planUser->isTrial()
                                    ? StatusUser::TEST 
                                    : StatusUser::ACTIVE;

        } elseif ($user->actual_plan && $user->actual_plan->id != $planUser->id) {

            $user->status_user_id = $user->actual_plan->isTrial()
                                    ? StatusUser::TEST 
                                    : StatusUser::ACTIVE;
                                    
        } else {
            $user->status_user_id = StatusUser::INACTIVE;
        }

        $user->save();
    }
}
