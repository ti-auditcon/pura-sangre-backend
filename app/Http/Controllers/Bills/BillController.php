<?php

namespace App\Http\Controllers\Bills;

use App\Models\Bills\Bill;
use Illuminate\Http\Request;
use App\Exports\PaymentsExcel;
use App\Models\Plans\PlanUser;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Bills\BillRequest;

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

    /**
     *  Store a new bill into the system associated a PlanUser
     *
     *  @param   PlanUser     $plan_user
     *  @param   BillRequest  $request
     *  
     *  @return  \Illuminate\Http\RedirectResponse
     */     
    public function store(BillRequest $request)
    {
        Bill::create($request->all());

        return back()->with('success', 'Boleta creada correctamente');
    }

    /**
     *  Update data for a registered payment
     *
     *  @return  \Illuminate\Http\RedirectResponse
     */
    public function update(Bill $payment, BillRequest $request)
    {
        $payment->update($request->all());

        return back()->with('success', 'Boleta actualizada correctamente');
    }

    /**
     *  [getPagos description]
     * 
     *  @param   Request $request [description]
     *  
     *  @return  [type]           [description]
     */
    public function getPagos(Request $request)
    {
      // print_r($request->all());
        $columns = array(0 => 'bills.created_at', 1 => 'users.first_name', 2 => 'plans.plan',
            3 => 'bills.date', 4 => 'bills.start_date', 5 => 'bills.finish_date', 6 => 'amount',
        );

        $totalData = Bill::count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $bills = Bill::offset($start)
                ->limit($limit)
                ->join('plan_user', 'bills.plan_user_id', '=', 'plan_user.id')
                ->join('users', 'plan_user.user_id', '=', 'users.id')
                ->join('plans', 'plan_user.plan_id', '=', 'plans.id')
                ->orderBy($order, $dir)
                ->select('bills.*', 'users.first_name', 'users.last_name')
                ->get();
            $totalFiltered = Bill::count();
        }else{
            $search = $request->input('search.value');
            $bills = Bill::where('date', 'like', date("Y-m-d",strtotime($search)))
                            ->orWhere('start_date','like', date("Y-m-d",strtotime($search)))
                            ->orWhere('finish_date','like', date("Y-m-d",strtotime($search)))
                            ->orWhere('amount','like',"%{$search}%")
                            ->orWhereHas('plan_user.user', function ($user) use ($search) {
                                $user->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('plan_user.plan', function ($plan) use ($search) {
                                $plan->where('plan', 'like', "%{$search}%");
                            })
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();

            $totalFiltered = Bill::where('date', 'like', date("Y-m-d",strtotime($search)))
                            ->orWhere('start_date','like', date("Y-m-d",strtotime($search)))
                            ->orWhere('finish_date','like', date("Y-m-d",strtotime($search)))
                            ->orWhere('amount','like',"%{$search}%")
                            ->orWhereHas('plan_user.user', function ($user) use ($search) {
                                $user->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('plan_user.plan', function ($plan) use ($search) {
                                $plan->where('plan', 'like', "%{$search}%");
                            })
                            ->count();
        }

        $data = array();

        if ($bills) {
            foreach($bills as $bill) {
                $nestedData['fecha_registro'] = $bill->created_at->format('d-m-Y');
                $nestedData['alumno'] = isset($bill->plan_user) ? '<a href="'.url('/users/'.$bill->plan_user->user->id).'">'.$bill->plan_user->user->first_name.' '.$bill->plan_user->user->last_name.'</a>' : "no aplica";
                $nestedData['email'] = isset($bill->plan_user) ? $bill->plan_user->user->email : "no aplica";
                $nestedData['plan'] = isset($bill->plan_user) ? $bill->plan_user->plan->plan : "no aplica";
                $nestedData['payment_type'] = isset($bill->payment_type) ? $bill->payment_type->payment_type : "no aplica";
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
     *  Display the specified resource.
     *
     *  @param  \App\Models\Bills\Bill  $bill
     * 
     *  @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        return $bill->toJson();
    }

    /**
     *  Export Excel of System bills
     *
     *  @return  Maatwebsite\Excel\Facades\Excel
     */
    public function export()
    {
        return Excel::download(new PaymentsExcel, toDay()->format('d-m-Y') . '_pagos.xlsx');
    }

    /**
     *  Delete an payment associated to a PlanUser
     *
     *  @param   Bill   $payment
     *
     *  @return  \Illuminate\Http\RedirectResponse
     */
    public function destroy(Bill $payment)
    {
        $payment->delete();

        return back()->with('succes', 'Se ha eliminado corretamente el pago asociado');
    }
}