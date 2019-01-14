<?php

namespace App\Http\Controllers\Bills;

use App\Http\Controllers\Controller;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanUser;
use App\Models\Users\User;
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
        $plans = Plan::all();
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


    public function getPagos(Request $request)
    {
        // print_r($request->all());
        $columns = array(
            0 => 'student',
            1 => 'plan',
            0 => 'date',
            1 => 'start_date',
            2 => 'finish_date',
            3 => 'amount'
        );
        
        $totalData = Bill::count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $bills = Bill::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            $totalFiltered = Bill::count();
        }else{
            $search = $request->input('search.value');
            $bills = Bill::where('date', 'like', "%{$search}%")
                            ->orWhere('start_date','like',"%{$search}%")
                            ->orWhere('finish_date','like',"%{$search}%")
                            ->orWhere('amount','like',"%{$search}%")
                            ->orWhereHas('plan_user.user', function ($user) use ($search) {
                                $user->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                            })
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();
            $totalFiltered = Bill::where('date', 'like', "%{$search}%")
                            ->orWhere('start_date','like',"%{$search}%")
                            ->orWhere('finish_date','like',"%{$search}%")
                            ->orWhere('amount','like',"%{$search}%")
                            ->orWhereHas('plan_user.user', function ($user) use ($search) {
                                $user->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                            })
                            ->count();
        }

        $data = array();
        
        if($bills){
            foreach($bills as $bill){
                $nestedData['alumno'] = isset($bill->plan_user) ? $bill->plan_user->user->first_name.' '.$bill->plan_user->user->last_name : "no aplica";
                $nestedData['plan'] = isset($bill->plan_user) ? $bill->plan_user->plan->plan : "no aplica";
                $nestedData['date'] = date('d-m-Y',strtotime($bill->date));
                $nestedData['start_date'] = date('d-m-Y',strtotime($bill->start_date));
                $nestedData['finish_date'] = date('d-m-Y',strtotime($bill->finish_date));
                $nestedData['amount'] = '$ '.number_format($bill->amount, $decimal = 0, '.', '.');
                // $nestedData['action'] = '
                //     <a href="#!" class="btn btn-warning btn-xs">Edit</a>
                //     <a href="#!" class="btn btn-danger btn-xs">Delete</a>
                // ';
                $data[] = $nestedData;
            }
        }
        
        $json_data = array(
            "draw"          => intval($request->input('draw')),
            "recordsTotal"  => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"          => $data
        );
        
        echo json_encode($json_data);

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