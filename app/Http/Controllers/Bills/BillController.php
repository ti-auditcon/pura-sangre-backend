<?php

namespace App\Http\Controllers\Bills;

use App\Models\Bills\Bill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * [BillController description]
 */
class BillController extends Controller
{
    /**
     * [__construct description]
     */
    // public function __construct()
    // {
    //     parent::__construct();
    //     // $this->cMIDLLEWARWE
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Bill::all()->toJson();
        $bills = Bill::all();
        return view('payments.index')->with('bills', $bills);
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
