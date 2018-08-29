<?php

namespace App\Http\Controllers\Plans;

use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Http\Controllers\Controller;

/** [PlanUserController description] */
class PlanUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * [create description]
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function create(User $user)
    {
        return view('plans.create')->with('user', $user);
    }

    /**
     * [store description]
     * @param  Request  $request  [description]
     * @param  PlanUser $planuser [description]
     * @return [type]             [description]
     */
    public function store(Request $request, PlanUser $planuser)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return \Illuminate\Http\Response
     */
    public function show(PlanUser $planUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return \Illuminate\Http\Response
     */
    public function edit(PlanUser $planUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PlanUser $planUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(PlanUser $planUser)
    {
        //
    }
}
