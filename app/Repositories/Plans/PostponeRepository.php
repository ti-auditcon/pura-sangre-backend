<?php 

namespace App\Repositories\Plans;

use Carbon\Carbon;
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

    /**
     *  @param   PostponePlan  $postpone
     *
     *  @return  bool
     */
    public function delete(PostponePlan $postpone)
    {
        $collection = collect($postpone->plan_user->only(
            'start_date', 'finish_date', 'counter', 'plan_status_id', 'plan_id', 'observations'
        ));

        $previous = $postpone->plan_user->history;

        $postpone->plan_user()->update([
            'history' => $previous ? $previous->add($collection) : [$collection]
        ]);

        $diff_in_days = Carbon::parse($postpone->finish_date)->diffInDays(today());

       $postpone->plan_user->update([
            'plan_status_id' => PlanStatus::ACTIVO,
            'finish_date'    => Carbon::parse($postpone->plan_user->finish_date)->subDays($diff_in_days + 1)
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
                'start_date'  => $plan->start_date->subDays($diff_in_days),
                'finish_date' => $plan->finish_date->subDays($diff_in_days)
            ]);
        }

        return $postpone->revoke();
    }    
}
