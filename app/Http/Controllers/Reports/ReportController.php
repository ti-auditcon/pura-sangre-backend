<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Plans\PlanIncomeSummary;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $summary = PlanIncomeSummary::all();
        return view('reports.index')->with('summary', $summary);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\Plans\PlanIncomeSummary  $planIncomeSummary
     * @return \Illuminate\Http\Response
     */
    public function show(PlanIncomeSummary $planIncomeSummary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plans\PlanIncomeSummary  $planIncomeSummary
     * @return \Illuminate\Http\Response
     */
    public function edit(PlanIncomeSummary $planIncomeSummary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plans\PlanIncomeSummary  $planIncomeSummary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PlanIncomeSummary $planIncomeSummary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plans\PlanIncomeSummary  $planIncomeSummary
     * @return \Illuminate\Http\Response
     */
    public function destroy(PlanIncomeSummary $planIncomeSummary)
    {
        //
    }
}
