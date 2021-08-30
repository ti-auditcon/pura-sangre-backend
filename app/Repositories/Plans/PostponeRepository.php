<?php 

namespace App\Repositories\Plans;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Plans\PostponePlan;

class PostponeRepository 
{
    protected $postpone;

    /**
     *  @param   PostponePlan  $postpone
     */
    public function __construct(PostponePlan $postpone)
    {
        $this->postpone = $postpone;
    }

    public function store($request, $plan_user)
    {
        $freezeStarts = Carbon::parse($request->start_freeze_date);
        $freezeEnds = Carbon::parse($request->end_freeze_date);
        
        $restingDays = $freezeStarts->diffInDays($plan_user->finish_date);
        
        PostponePlan::create([
            'plan_user_id' => $plan_user->id,
            'start_date'   => $freezeStarts,
            'finish_date'  => $freezeEnds,
            'days'         => $restingDays
        ]);
        
        $planes_posteriores = PlanUser::where('user_id', $plan_user->user_id)
                                        ->where('id', '!=', $plan_user->id)
                                        ->where('start_date', '>', $plan_user->start_date)
                                        ->orderByDesc('finish_date');
                                        
        foreach ($planes_posteriores as $plan) {
            $plan->update([
                'start_date' =>$plan->start_date->addDays($restingDays),
                
                'finish_date' => $plan->finish_date->addDays($restingDays)
            ]);
        }

        $this->deleteAllPlanReservationsSince($plan_user, $freezeStarts);

        $plan_user->update([
            'plan_status_id' => $freezeStarts->isToday() ? PlanStatus::CONGELADO : $plan_user->plan_status_id,
        ]);

        return true;
    }


    /**
     *  Delete all the future reservations of the plan,
     *  from the freezing start date
     *
     *  @param   PlanUser  $planUser
     *  @param   Carbon    $fromDate  date of start of the freezing
     *
     *  @return  void
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
     *  @param   PostponePlan  $postpone
     *
     *  @return  bool
     */
    public function delete(PostponePlan $postpone)
    {
        // update finish_date of the planUser with today date adding resting days to plan to be unfreezed
        $postpone->plan_user->update([
            'finish_date'    => today()->addDays($postpone->days),
            'plan_status_id' => PlanStatus::ACTIVO
        ]);

        // calculate difference days from new finish date of unfreezed plan to start_date of the next plan
        $planes_posteriores = PlanUser::where('user_id', $postpone->plan_user->user_id)
                                ->where('start_date', '>', $postpone->plan_user->start_date)
                                ->where('id', '!=', $postpone->plan_user->id)
                                ->orderBy('start_date')
                                ->get([
                                    'id', 'start_date', 'finish_date', 'user_id'
                                ]);

        if (count($planes_posteriores)) {
            $start_date_next_plan = $planes_posteriores->first()->start_date;

            $restingDays = $postpone->finish_date->diffInDays($start_date_next_plan);

            // move to back (backward, rearward) the next plans
            foreach ($planes_posteriores as $plan) {
                $plan->update([
                    'start_date'  => $plan->start_date->subDays($restingDays),
                    'finish_date' => $plan->finish_date->subDays($restingDays)
                ]);
            }
        }


        return $postpone->revoke();



    //     $collection = collect($postpone->plan_user->only(
    //         'start_date', 'finish_date', 'counter', 'plan_status_id', 'plan_id', 'observations'
    //     ));

    //     $previous = $postpone->plan_user->history;

    //     $postpone->plan_user()->update([
    //         'history' => $previous ? $previous->add($collection) : [$collection]
    //     ]);

    //     $restingDays = Carbon::parse($postpone->finish_date)->diffInDays(today());

    //    $postpone->plan_user->update([
    //         'plan_status_id' => PlanStatus::ACTIVO,
    //         'finish_date'    => Carbon::parse($postpone->plan_user->finish_date)->subDays($restingDays + 1)
    //     ]);
        
    //     $planes_posteriores = PlanUser::where('user_id', $postpone->plan_user->user_id)
    //                             ->where('start_date', '>', $postpone->plan_user->start_date)
    //                             ->where('id', '!=', $postpone->plan_user->id)
    //                             ->orderBy('finish_date')
    //                             ->get([
    //                                 'id', 'start_date', 'finish_date', 'user_id'
    //                             ]);

    //     foreach ($planes_posteriores as $plan) {
    //         $plan->update([
    //             'start_date'  => $plan->start_date->subDays($restingDays),
    //             'finish_date' => $plan->finish_date->subDays($restingDays)
    //         ]);
    //     }

    //     return $postpone->revoke();
    }    
}
