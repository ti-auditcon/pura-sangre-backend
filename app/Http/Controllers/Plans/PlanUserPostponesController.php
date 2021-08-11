<?php

namespace App\Http\Controllers\Plans;

use Carbon\Carbon;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Plans\PostponePlan;
use App\Http\Controllers\Controller;
use App\Repositories\Plans\PostponeRepository;
use App\Http\Requests\Plans\PostponePlanRequest;

class PlanUserPostponesController extends Controller
{
    protected $postponeRepository;

    /**
     *  [__construct description]
     *
     *  @param   PostponeRepository  $postpone  [$postpone description]
     */
    public function __construct(PostponeRepository $postpone)
    {
        $this->postponeRepository = $postpone;
    }


    /**
     *  Freeze a PlanUser resource in storage.
     *
     *  @param   \Illuminate\Http\Request  $request
     *
     *  @return  \Illuminate\Http\Response
     */
    public function store(PostponePlanRequest $request, PlanUser $plan_user)
    {
        // Parse Dates
        $start = Carbon::parse($request->start_freeze_date);
        $finish = Carbon::parse($request->end_freeze_date);
        $diff_in_days = $start->diffInDays($finish) + 1;

        PostponePlan::create([
            'plan_user_id' => $plan_user->id,
            'start_date'   => $start,
            'finish_date'  => $finish,
            'days'         => $diff_in_days
        ]);

        $planes_posteriores = $plan_user->user->plan_users->where('start_date', '>', $plan_user->start_date)
                                                          ->where('id', '!=', $plan_user->id)
                                                          ->sortByDesc('finish_date');

        foreach ($planes_posteriores as $plan) {
            $plan->update([
                'start_date' =>$plan->start_date->addDays($diff_in_days),

                'finish_date' => $plan->finish_date->addDays($diff_in_days)
            ]);
        }

        $this->deletePlanReservationsFromADate($plan_user, $start);

        $plan_user->update([
            'plan_status_id' => $start->isToday() ? PlanStatus::CONGELADO : $plan_user->plan_status_id,
            'finish_date'    => $plan_user->finish_date->addDays($diff_in_days)
        ]);

        return back()->with('success', 'Plan Congelado Correctamente');
    }

    /**
     *  Delete all the future reervations of the plan,
     *  from the freezing start date
     *
     *  @param   PlanUser  $planUser
     *  @param   Carbon    $fromDate  date of start of the freezing
     *
     *  @return  void
     */
    public function deletePlanReservationsFromADate($planUser, $fromDate)
    {
        $planUser->reservations()->each(function($reservation) use ($fromDate) {
            if ($reservation->clase && Carbon::parse($reservation->clase->date)->gt($fromDate)) {
                $reservation->delete();
            }
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
        $this->postponeRepository->delete($postpone);
        
        return redirect("users/{$postpone->plan_user->user->id}")
                    ->with('success', 'Plan reanudado correctamente');
    }
}
