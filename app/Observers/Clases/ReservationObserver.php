<?php

namespace App\Observers\Clases;

use Session;
use Carbon\Carbon;
use App\Models\Clases\Clase;
use App\Models\Clases\Reservation;

class ReservationObserver
{
    public function creating(Reservation $reservation)
    {
        $clase = $reservation->clase;
        $plans = $reservation->user->reservable_plans;
        $date_class = Carbon::parse($clase->date);

        // Check if has class yet
        $response = $this->hasReserve($clase, $reservation);
        if ($response) {
            Session::flash('warning', $response);
            return false;
        }

        // Return true if auth user is admin
        if ($reservation->by_god == 1) {
            return true;
        }

        // Verified if class is full
        if ($clase->reservation_count >= $clase->quota) {
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

    private function hasReserve($clase, $reservation)
    {
        $response = '';
        $clases = Clase::where('date', $clase->date)->get();
        foreach ($clases as $clase) {
            $reservations = Reservation::where('clase_id', $clase->id)
                ->where('user_id', $reservation->user_id)
                ->get();
            if (count($reservations) != 0) {
                $response = 'Ya tiene clase tomada este día';
            }
        }
        return $response;
    }

    private function userBadReserve($clase, $period_plan)
    {
        $badResponse = $period_plan->counter <= 0 ?
                       'Ya ha ocupado o reservado todas sus clases de su plan actual' :
                       null;
                       
        return $badResponse;
    }

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
        if ($period_plan) {
            $period_plan->updated_at = now();
            $period_plan->save();
            $reservation->update(['plan_user_id' => $period_plan->id]);
        }
        return true;
    }

    /**
     * [deleted description]
     * @param  Reservation $reservation [description]
     * @return [type]                   [description]
     */
    public function deleted(Reservation $reservation)
    {
        // $reservation_clase = $reservation->clase;

        if ($reservation->plan_user_id) {
            $reservation->plan_user->update([
                'counter' => $reservation->plan_user->counter + 1
            ]);
        }

        // if ($reservation->user) {
        //     $plans = $reservation->user->reservable_plans;
            
        //     $date_class = Carbon::parse($reservation_clase->date);
            
        //     $period_plan = null;
            
        //     foreach ($plans as $planuser) {
        //         if ($date_class->between(Carbon::parse($planuser->start_date), Carbon::parse($planuser->finish_date))) {
        //             $period_plan = $planuser;
        //         }
        //     }

        //     if ($period_plan) {
        //         $period_plan->update(['counter' => $period_plan->counter + 1]);
        //     }
        // }

        return true;
    }

}
