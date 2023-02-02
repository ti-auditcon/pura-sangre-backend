<?php

namespace App\Http\Controllers\Plans;

use App\Models\Plans\PlanUser;
use App\Models\Plans\PostponePlan;
use App\Http\Controllers\Controller;
use App\Repositories\Plans\PostponeRepository;
use App\Http\Requests\Plans\PostponePlanRequestStore;

class PlanUserPostponesController extends Controller
{
    protected $postponeRepository;

    /**
     * [__construct description]
     *
     * @param   PostponeRepository  $postpone  [$postpone description]
     */
    public function __construct(PostponeRepository $postpone)
    {
        $this->postponeRepository = $postpone;
    }


    /**
     * Freeze a PlanUser resource in storage.
     *
     * @param   \Illuminate\Http\Request  $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function store(PostponePlanRequestStore $request, PlanUser $plan_user)
    {
        $this->postponeRepository->store($request, $plan_user);

        return back()->with('success', 'Plan Congelado Correctamente');
    }


    /**
     * Unfreeze a PlanUser resource from storage.
     *
     * @param   \App\Models\Plans\PostponePlan     $postpone
     *
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function destroy(PostponePlan $postpone)
    {
        $this->postponeRepository->delete($postpone);
        
        return redirect("users/{$postpone->plan_user->user->id}")
                    ->with('success', 'Plan reanudado correctamente');
    }
}
