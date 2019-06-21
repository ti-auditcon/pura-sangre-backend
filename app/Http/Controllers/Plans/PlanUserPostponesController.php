<?php

namespace App\Http\Controllers\Plans;

use App\Http\Controllers\Controller;
use App\Models\Plans\PlanUser;
use App\Models\Users\User;
use Illuminate\Http\Request;

class PlanUserPostponesController extends Controller
{
    /**
     * Freeze a PlanUser resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PlanUser $plan_user)
    {
        dd($request->all(), $plan_user);
    }

    /**
     * Unfreeze a PlanUser resource from storage.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(PlanUser $planUser)
    {
        //
    }
}
