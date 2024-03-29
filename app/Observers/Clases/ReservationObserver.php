<?php

namespace App\Observers\Clases;

use Carbon\Carbon;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Clases\Reservation;
use Illuminate\Support\Facades\Session;

class ReservationObserver
{
    public function creating(Reservation $reservation)
    {
        $clase = $reservation->clase;
        $plans = $reservation->user->reservable_plans;
        $date_class = Carbon::parse($clase->date);

        if ($clase->claseType->special) {
            $exists = Reservation::where('clase_id', $clase->id)
                                ->where('user_id', $reservation->user_id)
                                ->exists('id');
            if ($exists) {
                Session::flash('warning', 'Ya tiene clase tomada este día');
                return false;
            }

            return true;
        }

        // Check if has class yet
        if ($response = $this->hasReserve($clase, $reservation->user_id)) {
            Session::flash('warning', $response);

            return false;
        }

        // Return true if auth user is admin
        if ($reservation->by_god == 1) {
            return true;
        }

        if ($clase->isFull()) {
            Session::flash('warning', 'La clase esta llena');

            return false;
        }

        $period_plan = null;
        foreach ($plans as $planuser) {
            if ($date_class->between(Carbon::parse($planuser->start_date), Carbon::parse($planuser->finish_date))) {
                $period_plan = $planuser;
            }
        }
        if (!$period_plan) {
            Session::flash('warning', 'No tiene un plan que le permita tomar esta clase');
            return false;
        }
        $response = $this->userBadReserve($clase, $period_plan);
        if ($response) {
            Session::flash('warning', $response);
            return false;
        }
    }

    /**
     * Check if user had reserve
     *
     * @param   Clase  $clase   Class to check
     * @param   int    $userId  The user who make the reservation
     *
     * @return  boolean|string
     */
    public function hasReserve($clase, $userId)
    {
        $has_a_reservation = Reservation::where('user_id', $userId)
                                ->join('clases', 'reservations.clase_id', '=', 'clases.id')
                                ->where('clases.date', $clase->date)
                                ->where('clases.clase_type_id', $clase->clase_type_id)
                                ->exists('id');

        return $has_a_reservation
            ? "Ya tiene una clase tomada para {$clase->claseType->clase_type} este día."
            : false;
    }

    private function userBadReserve($clase, $period_plan)
    {
        $badResponse = $period_plan->counter <= 0 ?
                       'Ya ha ocupado o reservado todas sus clases de su plan actual' :
                       null;

        return $badResponse;
    }

    /**
     * Undocumented function
     *
     * @param Reservation $reservation
     * @return void
     */
    public function created(Reservation $reservation)
    {
        $clase = $reservation->clase;
        $plans = $reservation->user->reservable_plans;
        $date_class = Carbon::parse($clase->date);

        $period_plan = null;
        foreach ($plans as $planuser) {
            if ($date_class->between(Carbon::parse($planuser->start_date), Carbon::parse($planuser->finish_date))) {
                $period_plan = $planuser;
            }
        }

        /**
         * we discount a quota of the plan only if the user has a valid plan
         * and if the class type is not special class
         */
        if ($period_plan && !$clase->claseType->special) {
            $reservation->update(['plan_user_id' => $period_plan->id]);

            // getting the dispatcher instance (needed to enable again the event observer later on)
            $dispatcher = PlanUser::getEventDispatcher();
            // disabling the events
            PlanUser::unsetEventDispatcher();
            // perform the operation you want
            $period_plan->subQuotas(1);
            PlanUser::setEventDispatcher($dispatcher);
        }

        return true;
    }

    /**
     * @param   Reservation  $reservation
     *
     * @return  bool
     */
    public function deleted(Reservation $reservation)
    {
        if ($reservation->plan_user_id) {
            $reservation->plan_user->update(['counter' => $reservation->plan_user->counter + 1]);
        }

        return true;
    }
}
