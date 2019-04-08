<?php

namespace App\Observers\Plans;

use App\Models\Clases\Reservation;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanIncomeSummary;
use App\Models\Plans\PlanUser;
use App\Models\Users\User;
use Carbon\Carbon;
use Session;

/**
 * [PlanUserObserver description]
 */
class PlanUserObserver
{
    /**
     * [creating description]
     * @param  PlanUser $planUser [description]
     * @return [type]             [description]
     */
    public function creating(PlanUser $planUser)
    {
        $user = User::findOrFail($planUser->user_id);
        $fecha_inicio = Carbon::parse($planUser->start_date);
        $fecha_termino = Carbon::parse($planUser->finish_date);
        $plan_users = PlanUser::whereIn('plan_status_id', [1, 3])->where('user_id', $user->id)->get();
        foreach ($plan_users as $plan_user) {
            if (($fecha_inicio->between(Carbon::parse($plan_user->start_date), Carbon::parse($plan_user->finish_date))) || ($fecha_termino->between(Carbon::parse($plan_user->start_date), Carbon::parse($plan_user->finish_date)))) {

                Session::flash('error', 'El usuario tiene un plan que choca con la fecha de inicio y período seleccionados');
                return false;
            } elseif (($fecha_inicio->lt(Carbon::parse($plan_user->start_date))) && ($fecha_termino->gt(Carbon::parse($plan_user->finish_date)))) {

                Session::flash('error', 'El usuario tiene un plan activo que choca con la fecha de inicio y período seleccionados');
                return false;
            } elseif (($fecha_inicio->gt(Carbon::parse($plan_user->start_date))) && ($fecha_termino->lt(Carbon::parse($plan_user->finish_date)))) {
                Session::flash('error', 'El usuario tiene un plan activo que choca con la fecha de inicio y período seleccionados');
                return false;
            }
        }
        return true;
    }

    /**
     * Handle the plan user "created" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function created(PlanUser $planUser)
    {
        $actl_pln_usr = isset($planUser->user->actual_plan) ? $planUser->user->actual_plan : null;
        if ($actl_pln_usr && $planUser->start_date > today()) {
            $planUser->plan_status_id = 3;
            $planUser->user->status_user_id = 1;
        } elseif ($actl_pln_usr && $planUser->finish_date < today()) {
            $planUser->plan_status_id = 4;
            $planUser->user->status_user_id = 1;
        }
        if (!$actl_pln_usr && $planUser->start_date <= today() && $planUser->finish_date >= today()) {
            if ($planUser->plan_id == 1) {
                $planUser->user->status_user_id = 3;
            } else {
                $planUser->user->status_user_id = 1;
            }
            $planUser->plan_status_id = 1;
            $reservations = Reservation::join('clases', 'reservations.clase_id', '=', 'clases.id')
                ->where('reservations.user_id', $planUser->user_id)
                ->whereBetween('date', [Carbon::parse($planUser->start_date)->format('Y-m-d'), Carbon::parse($planUser->finish_date)->format('Y-m-d')])
                ->pluck('reservations.id');
            foreach ($reservations as $reserv) {
                $reservation = Reservation::whereId($reserv)->first();
                if ($reservation->plan_user_id != $planUser->id) {
                    $reservation->update(['plan_user_id' => $planUser->id]);
                    $planUser->counter -= 1;
                }
            }
        }
        $planUser->user->save();
        if (!$planUser->user->actual_plan && $planUser->start_date > today()) {
            $planUser->plan_status_id = 3;
            $planUser->user->status_user_id = 2;
            $planUser->user->save();
        } elseif (!$planUser->user->actual_plan && $planUser->finish_date < today()) {
            $planUser->plan_status_id = 4;
            $planUser->user->status_user_id = 2;
            $planUser->user->save();
        }
        $planUser->save();
    }

    public function updated(PlanUser $planUser)
    {
        dd('hola');
        //UPDATE PARA CANCELAR EL PLAN
        if ($planUser->plan_status_id == 5) {
            foreach ($planUser->reservations as $key => $reserv) {
                if ($reserv->reservation_status_id == 1 || $reserv->reservation_status_id == 2) {
                    $reserv->delete();
                } elseif ($reserv->reservation_status_id == 3 || $reserv->reservation_status_id == 4) {
                    $reserv->update(['plan_user_id' => null]);
                }
            }
            if ($planUser->user->actual_plan) {
                $planUser->user->status_user_id = 1;
            } else {
                $planUser->user->status_user_id = 2;
            }
            $planUser->user->save();
        }
    }

    /**
     * Handle the plan user "restored" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
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
     * Handle the plan user "force deleted" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function forceDeleted(PlanUser $planUser)
    {
        //
    }
}
