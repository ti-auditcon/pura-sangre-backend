<?php

namespace App\Http\Controllers\Plans;

use Session;
use Carbon\Carbon;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Plans\PostponePlan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Plans\PostponePlanRequest;

class PlanUserPostponesController extends Controller
{
<<<<<<< Updated upstream
    /**
     * Freeze a PlanUser resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
=======
    /**
     *  The instance of the PostponeRepository for this controller
     *
     *  @var  PostponeRepository
     */
    protected $postponeRepository;

    /**
     *  Instanciate the repository for this controller
     *
     *  @param   PostponeRepository  $postpone
     */
    public function __construct(PostponeRepository $postpone)
    {
        $this->postponeRepository = $postpone;
    }

    /**
     *  Freeze a PlanUser resource in storage.
     *
     *  @param   \Illuminate\Http\Request    $request
     *  @param   \App\Models\Plans\PlanUser  $plan_user
     *
     *  @return  \Illuminate\Http\Response
>>>>>>> Stashed changes
     */
    public function store(PostponePlanRequest $request, PlanUser $plan_user)
    {
        dd('aaaaaaaaaaaaaaaaaaa');
        // Parse Dates
        $start = Carbon::parse($request->start_freeze_date);
<<<<<<< Updated upstream
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
=======
        $days_consumed = $plan_user->start_date->diffInDays($start);
        $total_plan_days = $plan_user->finish_date->diffInDays($plan_user->start_date) + 1;

        PostponePlan::create([
            'plan_user_id'    => $plan_user->id,
            'start_date'      => $start,
            'finish_date'     => Carbon::parse($request->end_freeze_date),
            'total_plan_days' => $total_plan_days,
            'days_consumed'   => $days_consumed
        ]);

        //  olny if the freezing starts today we need to remove future reservations,
        //  otherwise the cron should be take care of remove them
        if ($start->isToday()) {
            $this->deletePlanReservationsFromADate($plan_user, $start);
        }

        $plan_user->update([
            'plan_status_id' => $start->isToday() ? PlanStatus::CONGELADO : $plan_user->plan_status_id,
>>>>>>> Stashed changes
        ]);

        Session::flash('success', 'Plan Congelado Correctamente');

        return back();
    }

    /**
<<<<<<< Updated upstream
     *  Delete all the future reservations of the plan
=======
     *  Delete all the future reervations of the plan,
     *  from the freezing start date
     *
     *  @param   PlanUser  $planUser
     *  @param   Carbon    $freezingStart  start date freezing
>>>>>>> Stashed changes
     *
     *  @return  returnType
     */
<<<<<<< Updated upstream
    public function deletePlanReservations($planUser)
    {
        $planUser->reservations()->each(function($reservation) {
            $reservation->delete();
=======
    public function deletePlanReservationsFromADate($planUser, $freezingStart)
    {
        $planUser->reservations()->each(function($reservation) use ($freezingStart) {
            if ($reservation->clase && Carbon::parse($reservation->clase->date)->gt($freezingStart)) {
                $reservation->delete();
            }
>>>>>>> Stashed changes
        });
    }

    /**
     * Unfreeze a PlanUser resource from storage.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(PlanUser $plan_user, PostponePlan $postpone)
    {
<<<<<<< Updated upstream
        $last_postpone = PostponePlan::where('plan_user_id', $plan_user->id)
                                        ->where('finish_date', '>=', today())
                                        ->orderByDesc('start_date')
                                        ->first(); 

        if ($last_postpone) {
            $diff_in_days = Carbon::parse($last_postpone->finish_date)->diffInDays(today()); 

            $plan_user->update([
                'plan_status_id' => PlanStatus::ACTIVO,
                'finish_date' => Carbon::parse($plan_user->finish_date)->subDays($diff_in_days + 1)
            ]);

            $last_postpone->delete();
            
            return redirect('users/' . $plan_user->user->id)->with('success', 'Plan reanudado correctamente');
        }

        $plan_user->update(['plan_status_id' => PlanStatus::ACTIVO]);
        return redirect('users/' . $plan_user->user->id)->with('success', 'Plan reanudado correctamente');
=======
        $this->postponeRepository->delete($postpone);
        
        return redirect("users/{$postpone->plan_user->user->id}")
                ->with('success', 'Plan reanudado correctamente');
>>>>>>> Stashed changes
    }
}