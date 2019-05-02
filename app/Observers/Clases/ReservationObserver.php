<?php

namespace App\Observers\Clases;

use App\Models\Clases\Clase;
use App\Models\Clases\Reservation;
use Carbon\Carbon;
use Session;

class ReservationObserver
{
    public function creating(Reservation $reservation)
    {
        $clase = $reservation->clase;
        $plans = $reservation->user->reservable_plans;
        $date_class = Carbon::parse($clase->date);
        $response = $this->hasReserve($clase, $reservation);
        if ($response) {
            Session::flash('warning', $response);
            return false;
        }

        if ($reservation->by_god) {
            return true;
        } else {
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
        $badResponse = null;
        if ($period_plan->counter <= 0) {
            $badResponse = 'Ya ha ocupado o reservado todas sus clases de su plan actual';

        }
        // elseif ($clase->reservations()->count() >= $clase->quota) {
        //     $badResponse = 'No se puede tomar esta clase por que esta llena';

        // }elseif ($clase->date < toDay()->format('Y-m-d')) {
        //     $badResponse = 'No puede tomar una clase de un día anterior a hoy';

        // }elseif ($clase->date == toDay()->format('Y-m-d')) {
        //     $class_hour = Carbon::parse($clase->start_at);
        //     $diff_mns = $class_hour->diffInMinutes(now()->format('H:i'));
        //     if ((now()->format('H:i') > $class_hour->format('H:i')) || (now()->format('H:i') < $class_hour->format('H:i') && $diff_mns < 40)) {
        //         $badResponse = 'Ya no se puede tomar la clase';
        //     }
        // }
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

    // public function deleting(Reservation $reservation)
    // {
    //     $clase = $reservation->clase;
    //     // $plans = $reservation->user->reservable_plans;
    //     // $date_class = Carbon::parse($clase->date);

    //     if ($reservation->by_god) {
    //         return true;
    //     } else {
    //         if (!Auth::user()->hasRole(1)) {
    //             $response = $this->badGetOut($clase);
    //             if ($response) {
    //                 Session::flash('warning', $response);
    //                 return false;
    //             }
    //         }
    //     }
    // }

    // public function badGetOut($clase)
    // {
    //     $badGetOut = null;
    //     $class_hour = Carbon::parse($clase->start_at);
    //     if ($clase->date < toDay()->format('Y-m-d')) {
    //         $badGetOut = 'No puede votar una clase de un día anterior a hoy';
    //     } elseif ($clase->date == toDay()->format('Y-m-d')) {
    //         if ($class_hour->diffInMinutes(now()->format('H:i')) < 40 && $class_hour > now()->format('H:i')) {
    //             $badGetOut = 'Ya no puede votar la clase, por que esta pronto a comenzar';
    //         } elseif ($class_hour < now()) {
    //             $badGetOut = 'No puede votar una clase que ya pasó';
    //         }
    //     }
    //     return $badGetOut;
    // }

    public function deleted(Reservation $reservation)
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
            $period_plan->update(['counter' => $period_plan->counter + 1]);
        }
        return true;
    }

}
