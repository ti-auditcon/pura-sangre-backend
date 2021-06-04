<?php 

namespace App\Repositories\Plans;

use Carbon\Carbon;
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
        $diff_in_days = Carbon::parse($postpone->finish_date)->diffInDays(today()); 

        $postpone->plan_user->update([
            'plan_status_id' => PlanStatus::ACTIVO,
            'finish_date'    => Carbon::parse($postpone->plan_user->finish_date)->subDays($diff_in_days + 1)
        ]);

        return $postpone->delete();
    }    
}
