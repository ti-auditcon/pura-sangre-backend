<?php

namespace App\Http\Controllers\Clases;

use App\Http\Controllers\Controller;
use App\Models\Clases\Clase;
use App\Models\Clases\Reservation;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanUserPeriod;
use App\Models\Users\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redirect;
use Session;

class ClaseUserController extends Controller
{
    public function store(Request $request, Clase $clase)
    {
        $planusers = PlanUser::whereIn('plan_status_id', [1,3])->where('user_id', $request->user_id)->get();
        $date_class = Carbon::parse($clase->date);
        $response = $this->hasReserve($clase, $request);
            if ($response != null) {
                Session::flash('warning', $response);
                return Redirect::back();
            }
        if(count($planusers) != 0){
            foreach ($planusers as $planuser) {
                foreach ($planuser->plan_user_periods as $pup) {
                    if ($date_class->between(Carbon::parse($pup->start_date), Carbon::parse($pup->finish_date))) {
                        $period_plan = $pup; 
                    }
                }
            }
            if (!Auth::user()->hasRole(1)) {
                if ($period_plan->counter <= 0) {
                    Session::flash('warning', 'Ya ha ocupado o reservado todas sus clases de este mes de su plan actual');
                    return Redirect::back();
                }
            }
        }else{
            if (Auth::user()->hasRole(1)) {
                Reservation::create(array_merge($request->all(), [
                    'clase_id' => $clase->id,
                    'reservation_status_id' => 1
                ]));
                Session::flash('success','Agregado correctamente a la clase');
                return Redirect::back();
            }else {
                Session::flash('warning', 'No tienes ningun plan activo');
                return Redirect::back();
            }
        }
  
        if ($clase->date < toDay()->format('Y-m-d')) {
            Session::flash('warning','No puede tomar una clase de un día anterior a hoy');
            return Redirect::back();
        }
        elseif ($clase->date > toDay()->format('Y-m-d')) {
            $period_plan->update(['counter' => $period_plan->counter - 1]);
            Reservation::create(array_merge($request->all(), [
                'clase_id' => $clase->id,
                'reservation_status_id' => 1
            ]));
            Session::flash('success','Agregado correctamente a la clase');
            return Redirect::back();
        }
        else {
            $class_hour = Carbon::parse($clase->start_at);
            $diff_mns = $class_hour->diffInMinutes(now()->format('H:i'));
            if ((now()->format('H:i') > $class_hour) || (now()->format('H:i') < $class_hour && $diff_mns < 40)) {
                Session::flash('warning', 'Ya no se puede tomar la clase');
                return Redirect::back();
            }else{
                $period_plan->update(['counter' => $period_plan->counter - 1]);
                Reservation::create(array_merge($request->all(), [
                    'clase_id' => $clase->id,
                    'reservation_status_id' => 1
                ]));
                Session::flash('success','Agregado correctamente a la clase');
                return Redirect::back();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clases\Clase  $clase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clase $clase, User $user)
    {
        // $planuser = PlanUser::where('plan_status_id', 1)->where('user_id', $user->id)->first();
        $planusers = PlanUser::whereIn('plan_status_id', [1,3])->where('user_id', $user->id)->get();
        $date_class = Carbon::parse($clase->date);
        $reservation = $clase->reservations()->where('user_id', $user->id)->first();

        if(count($planusers) != 0){
            foreach ($planusers as $planuser) {
                foreach ($planuser->plan_user_periods as $pup) {
                    if ($date_class->between(Carbon::parse($pup->start_date), Carbon::parse($pup->finish_date))) {
                        $period_plan = $pup; 
                    }
                }
            }
        }

        if ($clase->date < toDay()->format('Y-m-d')) {
            Session::flash('warning','No puede votar una clase de un día anterior a hoy');
            return Redirect::back();
        }
        elseif ($clase->date > toDay()->format('Y-m-d')) {
            if ($reservation->delete()) {
                if ($period_plan != null) {
                    $period_plan->update(['counter' => $period_plan->counter + 1]);
                }
                Session::flash('success','Retiro de clase exitoso');
                return Redirect::back();
            }
        }
        else {
            $class_hour = Carbon::parse($clase->start_at);
            if ($class_hour->diffInMinutes(now()->format('H:i')) < 40 && $class_hour > now()->format('H:i')) {
                Session::flash('warning','Ya no puede votar la clase, por que esta pronto a comenzar');
                return Redirect::back();
            }elseif ($class_hour < now()) {
                Session::flash('warning','No puede votar una clase que ya pasó');
                return Redirect::back();
            }
            else{
                if ($reservation->delete()) {
                    if ($period_plan != null) {
                        $period_plan->update(['counter' => $period_plan->counter + 1]);
                    }
                    Session::flash('success','Retiro de clase exitoso');
                    return Redirect::back();
                }
            }
        }
    }

    private function hasReserve($clase, $request)
    {
        $response = '';
        $clases = Clase::where('date', $clase->date)->get();
        foreach ($clases as $clase) {
            $reservations = Reservation::where('clase_id', $clase->id)->where('user_id', $request->user_id)->get();
            if (count($reservations) != 0) {
                $response = 'Ya tiene clase tomada este día';
            }
        }
        return $response;
    }

}
    // private function hasTwelvePlan($planuser)
    // {
    //     $responseTwo = null;
    //     if ($planuser->plan->class_numbers == 12 && $planuser->counter >= 12 && $planuser->plan->plan_period_id == 1) {
    //         $responseTwo = 'No puede reservar, ya ha ocupado o reservado sus 12 clases del plan 12 clases mensual';
    //     }
    //     elseif ($planuser->plan->class_numbers == 12 && $planuser->counter >= 12 && $planuser->plan->plan_period_id == 3) {
    //         $responseTwo = 'Ya ha ocupado o reservado sus 12 clases de este mes del plan trimestral';
    //     }
    //     elseif ($planuser->plan->class_numbers == 12 && $planuser->counter >= 12 && $planuser->plan->plan_period_id == 5) {
    //         $responseTwo = 'Ya ha ocupado o reservado sus 12 clases de este mes del plan semestral';
    //     }
    //     elseif ($planuser->plan->class_numbers == 12 && $planuser->counter >= 12 && $planuser->plan->plan_period_id == 6) {
    //         $responseTwo = 'Ya ha ocupado o reservado sus 12 clases de este mes del plan anual';
    //     }

    //     return $responseTwo;
    // }
    // 
    
        // $responseTwo = $this->hasClassesLeft($planuser);
        //     if ($responseTwo != null) {
        //         Session::flash('warning', $responseTwo);
        //         return Redirect::back();
        //     }
        //     
        

          // if (!Auth::user()->hasRole(1)) {
        //     if ($planuser->counter <= 0) {
        //     Session::flash('warning', 'Ya ha ocupado o reservado todas sus clases de este mes de su plan actual');
        //     return Redirect::back();
        //     }
        // }


        // obtener periodos de los planes
        // recorrer periodos 
        // la fecha de reserva esta dentro de un periodo
        // ubicar periodo  
        // usuario tiene cupos en el periodo
    
      // foreach ($periodos as $periodo) {
        //     if ($date_class->between(Carbon::parse($periodo->start_date), Carbon::parse($periodo->finish_date))) {
        //         $period_plan[] = $periodo; 
        //     }
        // }
    
    // /**
    //  * [hasClassesLeft description]
    //  * @param  [type]  $planuser [description]
    //  * @return String  [description]
    //  */
    // private function hasClassesLeft($planuser)
    // {
    //     $responseThree = null;
    //     if ($planuser->counter <= 0) {
    //        $responseThree = 'Ya ha ocupado o reservado todas sus clases de este mes del plan'; 
    //     }
    //     return $responseThree;
    // }
