<?php

namespace App\Http\Controllers\Clases;

use Auth;
use Session;
use Redirect;
use Carbon\Carbon;
use App\Models\Users\User;
use App\Models\Clases\Clase;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Models\Clases\Reservation;
use App\Http\Controllers\Controller;

class ClaseUserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Clase $clase)
    {
        // dd($request->all());
        $planuser = PlanUser::where('plan_status_id', 1)->where('user_id', $request->user_id)->first();
            if ($planuser == null && Auth::user()->hasRole(1)) {
                Reservation::create(array_merge($request->all(), [
                    'clase_id' => $clase->id,
                    'reservation_status_id' => 1
                ]));
                    Session::flash('success','Agregado correctamente a la clase');
                    return Redirect::back();
            }elseif ($planuser == null) {
                Session::flash('warning', 'No tienes ningun plan activo');
                return Redirect::back();
            }

        $response = $this->hasReserve($clase, $request);
            if ($response != null) {
                Session::flash('warning', $response);
                return Redirect::back();
            }

        if (!Auth::user()->hasRole(1)) {
            if ($planuser->counter <= 0) {
            Session::flash('warning', 'Ya ha ocupado o reservado todas sus clases de este mes de su plan actual');
            return Redirect::back();
            }
        }
        
        if ($clase->date < toDay()->format('Y-m-d')) {
            Session::flash('warning','No puede tomar una clase de un día anterior a hoy');
            return Redirect::back();
        }
        elseif ($clase->date > toDay()->format('Y-m-d')) {
            $planuser->update(['counter' => $planuser->counter - 1]);
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
            Session::flash('warning','Ya no se puede tomar la clase');
            return Redirect::back();
            }else{
                $planuser->update(['counter' => $planuser->counter - 1]);
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
        $reservation = $clase->reservations()->where('user_id', $user->id)->first();
        $planuser = PlanUser::where('plan_status_id', 1)->where('user_id', $user->id)->first();

        if ($clase->date < toDay()->format('Y-m-d')) {
            Session::flash('warning','No puede votar una clase de un día anterior a hoy');
            return Redirect::back();
        }
        elseif ($clase->date > toDay()->format('Y-m-d')) {
            if ($reservation->delete()) {
                $planuser->update(['counter' => $planuser->counter + 1]);
                Session::flash('success','Retiro de clase exitoso');
                return Redirect::back();
            }
        }
        else {
            $class_hour = Carbon::parse($clase->start_at);
            if ($class_hour->diffInMinutes(now()->format('H:i')) < 40) {
                Session::flash('warning','Ya no puede votar la clase');
                return Redirect::back();
            }else{
                if ($reservation->delete()) {
                    $planuser->update(['counter' => $planuser->counter + 1]);
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
            $reservations = Reservation::where('user_id', $request->user_id)->where('clase_id', $clase->id)->get();
            if (count($reservations) != 0) {
                $response = 'Ya tiene clase tomada este día';
            }
        }
        return $response;
    }

    /**
     * [hasClassesLeft description]
     * @param  [type]  $planuser [description]
     * @return String  [description]
     */
    private function hasClassesLeft($planuser)
    {
        $responseThree = null;
        if ($planuser->counter <= 0) {
           $responseThree = 'Ya ha ocupado o reservado todas sus clases de este mes del plan'; 
        }
        return $responseThree;
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