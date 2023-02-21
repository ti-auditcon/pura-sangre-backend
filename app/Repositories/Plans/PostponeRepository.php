<?php 

namespace App\Repositories\Plans;

use Carbon\Carbon;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Plans\PostponePlan;
use App\Http\Requests\Plans\PostponePlanRequest;
use App\Http\Requests\Plans\PostponePlanRequestStore;

class PostponeRepository 
{
    protected $postpone;

    /**
     * @param   PostponePlan  $postpone
     */
    public function __construct(PostponePlan $postpone)
    {
        $this->postpone = $postpone;
    }

    /**
     * Logic to freeze a plan.
     * 
     * - Transform the start and end freeze dates to Carbon instances.
     * - Calculate the remaining days of the plan.
     * - Create the PostponePlan resource.
     * - Get all the subsequent plans of the user.
     * - Update the subsequent plans with the new dates.
     * - Delete the subsequent bookings of the plan to freeze.
     * - Update the plan status to FROZEN.
     * 
     *
     * @param   PostponePlanRequest  $request
     * @param   [type]  $plan_user  [$plan_user description]
     *
     * @return  bool<true>
     */
    public function store(PostponePlanRequestStore $request, $plan_user)
    {
        $freezeStarts = Carbon::parse($request->start_freeze_date);
        $freezeEnds = Carbon::parse($request->end_freeze_date);

        if (
            PostponePlan::where('plan_user_id', $plan_user->id)
                ->where('revoked', false)
                ->exists('id')
        ) {
            return 'El plan ya se encuentra congelado.';
        }
        
        $remainingDays = $freezeStarts->diffInDays($plan_user->finish_date) + 1;
        
        PostponePlan::create([
            'plan_user_id' => $plan_user->id,
            'start_date'   => $freezeStarts,
            'finish_date'  => $freezeEnds,
            'days'         => $remainingDays
        ]);
        
        $subsequentPlans = PlanUser::where('user_id', $plan_user->user_id)
                                        ->where('id', '!=', $plan_user->id)
                                        ->where('start_date', '>', $plan_user->start_date)
                                        ->orderByDesc('finish_date');
                                        
        foreach ($subsequentPlans as $plan) {
            $plan->update([
                'start_date' =>$plan->start_date->addDays($remainingDays),
                
                'finish_date' => $plan->finish_date->addDays($remainingDays)
            ]);
        }

        $this->deleteAllPlanReservationsSince($plan_user, $freezeStarts);

        $plan_user->update([
            'plan_status_id' => $freezeStarts->isToday() ? PlanStatus::FROZEN : $plan_user->plan_status_id,
        ]);

        return true;
    }

    /**
     * Delete all the future reservations of the plan,
     * from the freezing start date
     *
     * @param   PlanUser  $planUser
     * @param   Carbon    $fromDate  date of start of the freezing
     *
     * @return  void
     */
    public function deleteAllPlanReservationsSince($planUser, $fromDate)
    {
        $planUser->reservations()->each(function($reservation) use ($fromDate) {
            if ($reservation->clase && Carbon::parse($reservation->clase->date)->gt($fromDate)) {
                $reservation->delete();
            }
        });
    }

    /**
     * @param   PostponePlan  $postpone
     *
     * @return  bool
     */
    public function delete(PostponePlan $postpone)
    {
        // update finish_date of the planUser with today date adding resting days to plan to be unfreezed
        $postpone->plan_user->update([
            'finish_date'    => today()->endOfDay()->addDays($postpone->days),
            'plan_status_id' => PlanStatus::ACTIVE
        ]);

        // calculate difference days from new finish date of unfreezed plan to start_date of the next plan
        $subsequentPlans = PlanUser::where('user_id', $postpone->plan_user->user_id)
                                ->where('start_date', '>', $postpone->plan_user->start_date)
                                ->where('id', '!=', $postpone->plan_user->id)
                                ->orderBy('start_date')
                                ->get([
                                    'id', 'start_date', 'finish_date', 'user_id'
                                ]);

        if (count($subsequentPlans)) {
            $start_date_next_plan = $subsequentPlans->first()->start_date;

            $remainingDays = $postpone->finish_date->diffInDays($start_date_next_plan);

            // move to back (backward, rearward) the next plans
            foreach ($subsequentPlans as $plan) {
                $plan->update([
                    'start_date'  => $plan->start_date->subDays($remainingDays),
                    'finish_date' => $plan->finish_date->subDays($remainingDays)
                ]);
            }
        }

        return $postpone->revoke();
    }    
}
