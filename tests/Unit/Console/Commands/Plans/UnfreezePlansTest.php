<?php

namespace Tests\Unit\Console\Commands\Plans;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Plans\PostponePlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UnfreezePlansTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purasangre:plans:unfreeze';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unfreeze all the plans who has today the unfreeze date';

    /**
     * Change the actual time to another one
     *
     * @param   Carbon|string
     *
     * @return  void
     */
    public function travelTo($date) :void
    {
        Carbon::setTestNow($date);
    }


    /** @test */
    public function it_revokes_the_postpone_register()
    {
        $freezedPlan = $this->fakeActivePlanUser([
            'plan_status_id' => PlanStatus::FROZEN,
        ]);

        $postponeRegister = factory(PostponePlan::class)->create([
            'plan_user_id' => $freezedPlan->id,
            'days'         => 10,
            'finish_date'  => today()->endOfDay()->subDay()
        ]);

        $this->assertDatabaseHas('freeze_plans', [
            'id' => $postponeRegister->id,
            'revoked' => false
        ]);

        $this->artisan($this->signature)->assertExitCode(0);

        $this->assertDatabaseHas('freeze_plans', [
            'id' => $postponeRegister->id,
            'revoked' => true
        ]);
    }

    /** 
     * [-- current plan (just unfrozen) --]
     *                                                   [-- next plan (not yet active) --]
     * 
     * move forward means to move it close to you.
     *  Ex. the start of the next plan is after the end of the current plan, so we need to move it forward (closer to the current plan)
     * 
     * @test
     */
    public function it_move_next_plans_dates_forward_if_the_closest_next_plan_starts_after_the_current_active_plan_ends()
    {
        $planToUnfreeze = $this->fakeActivePlanUser([
            'plan_status_id' => PlanStatus::FROZEN,
            'start_date'     => today()->startOfMonth(),
            'finish_date'    => today()->endOfMonth(),
            'user_id'        => $this->admin->id
        ]);


        $nextClosestPlan = $this->fakeActivePlanUser([
            'plan_status_id' => PlanStatus::PRE_PURCHASE,
            'start_date'     => today()->addMonth()->startOfMonth(),
            'finish_date'    => today()->addMonth()->endOfMonth(),
            'user_id'        => $this->admin->id     
        ]);
        
        $freezeRegister = factory(PostponePlan::class)->create([
            'plan_user_id' => $planToUnfreeze->id,
            'days'         => 5,
            'finish_date'  => today()->subDay()
        ]);

        $this->artisan($this->signature)->assertExitCode(0);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $planToUnfreeze->id,
            'plan_status_id' => PlanStatus::ACTIVE,
            'finish_date'    => today()->addDays(5 - 1)->endOfDay()->format('Y-m-d H:i:s'),
        ]);

        //  we fetch the plan again to get the new data from database
        $nowActivePlan = PlanUser::find($planToUnfreeze->id);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $nextClosestPlan->id,
            'plan_status_id' => PlanStatus::PRE_PURCHASE,
            'start_date'     => $nowActivePlan->finish_date->startOfDay()->addDay()->format('Y-m-d H:i:s'),
        ]);
    }

    /** 
     * [-- current plan (just unfrozen) --]
     *                         [-- next plan (not yet active) --]                          
     * 
     * move backwards means to move it far from you.
     * Ex. the start of the next plan is before the end of the current plan, so we need to move it backwards (far from the current plan)
     * @test
     */
    public function it_move_next_plans_dates_backward_if_the_closest_next_plan_start_before_the_current_active_plan_ends()
    {
        $planToUnfreeze = $this->fakeActivePlanUser([
            'plan_status_id' => PlanStatus::FROZEN,
            'start_date'     => today()->startOfMonth(),
            'finish_date'    => today()->endOfMonth(),
            'user_id'        => $this->admin->id
        ]);

        $nextClosestPlan = $this->fakeActivePlanUser([
            'plan_status_id' => PlanStatus::PRE_PURCHASE,
            'start_date'     => today()->addMonth()->startOfMonth(),
            'finish_date'    => today()->addMonth()->endOfMonth(),
            'user_id'        => $this->admin->id     
        ]);

        $this->travelTo(today()->endOfMonth()->subDays(5));

        $freezeRegister = factory(PostponePlan::class)->create([
            'plan_user_id' => $planToUnfreeze->id,
            'days'         => 10,
            'finish_date'  => today()->subDay()
        ]);


        $this->artisan($this->signature)->assertExitCode(0);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $planToUnfreeze->id,
            'plan_status_id' => PlanStatus::ACTIVE,
            'finish_date'    => today()->addDays(10 - 1)->endOfDay()->format('Y-m-d H:i:s'),
        ]);

        //  we fetch the plan again to get the new data from database
        $nowActivePlan = PlanUser::find($planToUnfreeze->id);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $nextClosestPlan->id,
            'plan_status_id' => PlanStatus::PRE_PURCHASE,
            'start_date'     => $nowActivePlan->finish_date->startOfDay()->addDay()->format('Y-m-d H:i:s'),
        ]);
    }
}
