<?php

namespace App\Http\Controllers\Bills;

use App\Http\Controllers\Controller;
use App\Models\Bills\Bill;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * [BillController description]
 */
class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('payments.index');
    }

    public function bills()
    {
        $bills = Bill::all();
        return ['data' => $bills->map(function ($bill) {
            return [
                'user_id' => isset($bill->plan_user) ? $bill->plan_user->user->id : "no aplica",
                'alumno' => isset($bill->plan_user) ? $bill->plan_user->user->first_name.' '.$bill->plan_user->user->last_name : "no aplica",
                'plan' => isset($bill->plan_user) ? $bill->plan_user->plan->plan : "no aplica",
                'fecha_boleta' => Carbon::parse($bill->date)->format('d-m-Y') ?? "no aplica",
                'fecha_de_inicio' => Carbon::parse($bill->start_date)->format('d-m-Y') ?? "no aplica",
                'fecha_de_termino' => Carbon::parse($bill->finish_date)->format('d-m-Y') ?? "no aplica",
                'total' => '$ '.number_format($bill->amount, $decimal = 0, '.', '.') ?? "no aplica",
            ];
        })];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bills\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        return $bill->toJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bills\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bill $bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bills\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $bill)
    {
        //
    }
}


           // if (!$bill->plan_user) {
           //      $id = 'sin dato';
           //      $first_name = 'sin dato';
           //      $plan = 'sin dato';
           //  }else{
           //      $id = $bill->plan_user->user->id;
           //      $first_name = $bill->plan_user->user->first_name.' '.$bill->plan_user->user->last_name;
           //      $plan = $bill->plan_user->plan->plan;
           //  }
            // 
           //  
      // return $bills->map(function ($bill) {
      //          return [
      //           'user_id' => $bill->plan_user->user->id,
      //           'alumno' => $bill->plan_user->user->first_name.' '.$bill->plan_user->user->last_name,
      //           'Plan' => $bill->plan_user->plan->plan,
      //           'Fecha boleta' => Carbon::parse($bill->date)->format('d-m-Y'),
      //           'Fecha de inicio' => Carbon::parse($bill->start_date)->format('d-m-Y'),
      //           'Fecha de termino' => Carbon::parse($bill->finish_date)->format('d-m-Y'),
      //           'Total' => '$ '.number_format($bill->amount, $decimal = 0, '.', '.') ?? "no aplica"
      //       ];
      //   });