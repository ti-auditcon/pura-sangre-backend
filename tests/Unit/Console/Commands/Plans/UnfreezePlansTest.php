<?php

namespace Tests\Unit\Console\Commands\Plans;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Users\StatusUser;
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
        $this->withoutExceptionHandling();

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
            'revoked' => false,
            'finish_date' => today()->endOfDay()->subDay()->format('Y-m-d')
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


    /** @test */
    public function it_unfreeze_plans_with_yesterday_end_freezing_correctly()
    {
        $freezing = factory(PostponePlan::class)->create([
            'finish_date' => today()->subDay(),
        ]);

        $this->assertEquals(
            PostponePlan::ACTIVE,
            PostponePlan::find($freezing->id)->revoked
        );

        $this->artisan($this->signature)->assertExitCode(0);

        $this->assertEquals(
            PostponePlan::REVOKED,
            PostponePlan::find($freezing->id)->revoked
        );
    }

    // it plans with end freezing after yesteday doesnt unfreeze
    /** @test */
    public function it_plans_with_end_freezing_after_yesterday_doesnt_unfreeze()
    {
        $freezing = factory(PostponePlan::class)->create([
            'finish_date' => today()->today(),
        ]);

        $this->assertEquals(
            PostponePlan::ACTIVE,
            PostponePlan::find($freezing->id)->revoked
        );

        $this->artisan($this->signature);

        $this->assertEquals(
            PostponePlan::ACTIVE,
            PostponePlan::find($freezing->id)->revoked
        );
    }

    // it stores history record correctly
    // /** @test */
    // public function it_stores_history_record_correctly()
    // {
    //     $freezing = factory(PostponePlan::class)->create([
    //         'finish_date' => today()->subDay(),
    //     ]);

    //     $this->artisan($this->signature)->assertExitCode(0);

    //     $this->assertEquals(
    //         [
    //             'date' => now()->startOfMinute()->format('Y-m-d H:i:s'),
    //             'description' => 'Se activa plan por término de congelamiento.',
    //             'plan_status_id' => PlanStatus::ACTIVE,
    //             'counter' => $freezing->plan_user->counter,
    //             'total_classes' => $freezing->plan_user->total_classes,
    //             'start_date' => $freezing->plan_user->start_date->toISOString(),
    //             'finish_date' => today()->addDays($freezing->days -1)->endOfDay()->toISOString(),
    //         ],
    //         json_decode(PlanUser::find($freezing->plan_user_id)->history, true)[0]
    //     );
    // }

    /**
     *  At unfreeze, finish_date of plan_user is today
     *  plus the days in the "days" fied into PostponePlan
     *
     *  @test
     */
    public function it_plan_user_has_a_correct_number_of_days_after_unfreezing_it()
    {
        $planUser = factory(PlanUser::class)->create([
            'plan_status_id' => PlanStatus::FREEZED,
            'start_date' => today(),
            // we finish the plan the next month to ensure that at unfreeze,
            //  the finish_date is changed correctly depending on the days in the "days" field
            'finish_date' => today()->addMonth(),
        ]);

        $daysToAdd = 2;

        $freezing = factory(PostponePlan::class)->create([
            'finish_date' => today()->subDay(),
            'days' => $daysToAdd,
            'plan_user_id' => $planUser->id,
        ]);

        $this->artisan($this->signature);

        $this->assertDatabaseHas('plan_user', [
            'id' => $freezing->plan_user_id,
            'finish_date' => today()->addDay()->endOfDay()
        ]);
    }

    // at unfreeze a plan the time of the finish_date is 23:59:59
    /** @test */
    public function it_plan_user_finish_date_has_a_correct_time_after_unfreezing_it()
    {
        $planUser = factory(PlanUser::class)->create([
            'plan_status_id' => PlanStatus::FREEZED,
            'start_date' => today(),
            'finish_date' => today()->addMonth(),
        ]);

        $daysToAdd = 2;

        $freezing = factory(PostponePlan::class)->create([
            'finish_date' => today()->subDay(),
            'days' => $daysToAdd,
            'plan_user_id' => $planUser->id,
        ]);

        $this->artisan($this->signature);

        $this->assertDatabaseHas('plan_user', [
            'id' => $freezing->plan_user_id,
            'finish_date' => today()->addDay()->endOfDay()->format('Y-m-d H:i:s')
        ]);
    }

    /** @test */
    public function it_user_gets_active_status_if_it_was_inactive_before_unfreeze()
    {
        $user = factory(User::class)->create([
            'status_user_id' => StatusUser::INACTIVE,
        ]);

        $planUser = PlanUser::withoutEvents(function () use($user) {
            $planUser = factory(PlanUser::class)->create([
                'plan_status_id' => PlanStatus::FREEZED,
                'user_id' => $user->id,
                'start_date' => today()->subDays(2),
                'finish_date' => today()->addMonth()->endOfDay(),
                'plan_id' => factory(Plan::class)->create([
                    'id' => 999
                ])->id,
            ]);

            factory(PostponePlan::class)->create([
                'finish_date' => today()->subDay(),
                'days' => 2,
                'plan_user_id' => $planUser->id,
                'revoked' => PostponePlan::ACTIVE,
            ]);

            return $planUser;
        });

        $this->artisan($this->signature);

        $this->assertDatabaseHas('users', [
            'id' => $planUser->user->id,
            'status_user_id' => StatusUser::ACTIVE,
        ]);
    }
}
