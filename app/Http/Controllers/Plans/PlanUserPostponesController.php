<?php

namespace App\Http\Controllers\Plans;

use Carbon\Carbon;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Plans\PostponePlan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Repositories\Plans\PostponeRepository;
use App\Http\Requests\Plans\PostponePlanRequest;

class PlanUserPostponesController extends Controller
{
    // protected $postponeRepository;

    // /**
    //  *  [__construct description]
    //  *
    //  *  @param   PostponeRepository  $postpone  [$postpone description]
    //  */
    // public function __construct(PostponeRepository $postpone)
    // {
    //     // $this->postponeRepository = $postpone;
    // }


    /**
     *  Freeze a PlanUser resource in storage.
     *
     *  @param  \Illuminate\Http\Request  $request
     *
     *  @return \Illuminate\Http\Response
     */
    public function store(PostponePlanRequest $request, PlanUser $plan_user)
    {
        // Parse Dates
        $start = Carbon::parse($request->start_freeze_date);
        $finish = Carbon::parse($request->end_freeze_date);

        PostponePlan::create([
            'plan_user_id' => $plan_user->id,

            'start_date' => $start,

            'finish_date' => $finish
        ]);

        $diff_in_days = $start->diffInDays($finish) + 1;

        $planes_posteriores = $plan_user->user->plan_users->where('start_date', '>', $plan_user->start_date)
                                                          ->where('id', '!=', $plan_user->id)
                                                          ->sortByDesc('finish_date');

        foreach ($planes_posteriores as $plan) {
            $plan->update([

                'start_date' =>$plan->start_date->addDays($diff_in_days),

                'finish_date' => $plan->finish_date->addDays($diff_in_days)

            ]);
        }

        $this->deletePlanReservations($plan_user);

        $plan_user->update([
            'plan_status_id' => $start->isToday() ? 2 : $plan_user->plan_status_id,

            'finish_date' => $plan_user->finish_date->addDays($diff_in_days)
        ]);

        Session::flash('success', 'Plan Congelado Correctamente');

        return back();
    }

    /**
     *  Delete all the future reservations of the plan
     *
     *  @return  returnType
     */
    public function deletePlanReservations($planUser)
    {
        $planUser->reservations()->each(function($reservation) {
            $reservation->delete();
        });
    }

    /**
     *  Unfreeze a PlanUser resource from storage.
     *
     *  @param   \App\Models\Plans\PostponePlan     $postpone
     *
     *  @return  \Illuminate\Http\RedirectResponse
     */
    public function destroy(PostponePlan $postpone)
    {
        // $this->postponeRepository->delete($postpone);

        $diff_in_days = Carbon::parse($postpone->finish_date)->diffInDays(today()); 

        $postpone->plan_user->update([
            'plan_status_id' => PlanStatus::ACTIVO,
            'finish_date' => Carbon::parse($postpone->plan_user->finish_date)->subDays($diff_in_days + 1)
        ]);

        $planes_posteriores = PlanUser::where('user_id', $postpone->plan_user->user_id)
                                ->where('start_date', '>', $postpone->plan_user->start_date)
                                ->where('id', '!=', $postpone->plan_user->id)
                                ->orderBy('finish_date')
                                ->get([
                                    'id', 'start_date', 'finish_date', 'user_id'
                                ]);

        foreach ($planes_posteriores as $plan) {
            $plan->update([
                'start_date' =>$plan->start_date->subDays($diff_in_days),
                'finish_date' => $plan->finish_date->subDays($diff_in_days)
            ]);
        }

        $postpone->delete();
        
        return redirect("users/{$postpone->plan_user->user->id}")->with('success', 'Plan reanudado correctamente');
    }


}