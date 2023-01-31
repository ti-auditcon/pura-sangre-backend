<?php

namespace App\Http\Controllers\Plans;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Users\StatusUser;
use App\Models\Plans\PostponePlan;
use App\Http\Controllers\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;

class PostponeController extends Controller
{
    /**
     * [index description]
     *
     * @return  [type]  [return description]
     */
    public function index()
    {
        return view('postpones.index');
    }

    /**
     * Postpone all plan by certains time
     *
     * @return  view
     */
    public function postponeAll(Request $request)
    {
        $plans_to_postpone = PlanUser::where('plan_status_id', PlanStatus::ACTIVE)
                                        ->with('plan:id')
                                        ->get(['id', 'start_date', 'finish_date', 'user_id', 'plan_status_id', 'plan_id']);

        foreach($plans_to_postpone as $planToPostpone) {
            $this->postpone($request, $planToPostpone);
        }

        return view('postpones.index')->with('success', 'Todo pospuesto');
    }

    /**
     * methodDescription
     *
     * @return  returnType
     */
    public function postpone($request, $plan_user)
    {
        // getting the dispatcher instance (needed to enable again the event observer later on)
        $dispatcher = PlanUser::getEventDispatcher();
        // disabling the events
        PlanUser::unsetEventDispatcher();
            // perform the operation you want

        // Parse Dates
        $start = Carbon::parse($request->start_date); 
        $finish = Carbon::parse($request->finish_date);

        if ($plan_user->plan_status_id === PlanStatus::ACTIVE) {
            PostponePlan::create([
                'plan_user_id' => $plan_user->id,
                'start_date'   => $start,
                'finish_date'  => $finish
            ]);
        }

        $diff_in_days = $start->diffInDays($finish) + 1;

        $planes_posteriores = PlanUser::where('user_id', $plan_user->user_id)
                                        ->where('start_date', '>', $plan_user->start_date)
                                        ->where('id', '!=', $plan_user->id)
                                        ->orderByDesc('finish_date')
                                        ->get([
                                            'id', 'start_date', 'finish_date', 'user_id'
                                        ]);

        foreach ($planes_posteriores as $plan) {
            $plan->update([
                'start_date'  =>$plan->start_date->addDays($diff_in_days),
                'finish_date' => $plan->finish_date->addDays($diff_in_days)
            ]);
        }

        $plan_user->update([
            'plan_status_id' => PlanStatus::FROZEN,
            'finish_date'    => $plan_user->finish_date->addDays($diff_in_days)
        ]);

        if (!$plan_user->user->actual_plan) {
            $plan_user->user->update(['status_user_id' => StatusUser::INACTIVE]);
        }

        // enabling the event dispatcher
        PlanUser::setEventDispatcher($dispatcher);

        return true;
    }
}