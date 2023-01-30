<?php

namespace Tests\Feature\Console\Commands;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Users\StatusUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 *
 * pre_purchase_plan_changes_to_active_if_start_date_is_equals_or_before_now
 * pre_purchase_plan_keeps_pre_purchase_status_if_start_date_is_after_today
 * current_timezone_is_according_box_center_timezone
 * active_plan_that_finish_today_does_not_change_status_if_it_is_today_yet
 * active_plan_that_finish_today_changes_status_to_finished_if_current_day_is_tomorrow
 * it_doesnt_consider_deleted_plan_users
 *
 */
class RefreshPlansTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    protected $signature = 'purasangre:plans:refresh';

    public function setUp(): void
    {
        parent::setUp();

        $this->seed('PlansTableSeeder');
    }

    /** @test */
    public function it_active_plan_is_closed_and_pre_purchase_is_activated()
    {
        $activePlan = PlanUser::withoutEvents(function() {
            return factory(PlanUser::class)->create([
                'start_date' => today()->subDays(2)->format('Y-m-d'),
                'finish_date' => today()->subDays(1)->format('Y-m-d'),
                'plan_status_id' => PlanStatus::ACTIVE
            ]);
        });

        $prePurchasePlan = PlanUser::withoutEvents(function () {
            return factory(PlanUser::class)->create([
                'start_date' => today()->format('Y-m-d'),
                'finish_date' => today()->format('Y-m-d'),
                'plan_status_id' => PlanStatus::PRE_PURCHASE
            ]);
        });

        $this->assertDatabaseHas('plan_user', [
            'id' => $activePlan->id,
            'plan_status_id' => PlanStatus::ACTIVE
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id' => $prePurchasePlan->id,
            'plan_status_id' => PlanStatus::PRE_PURCHASE
        ]);

        $this->travelTo(today()->startOfDay());

        /**  Call the command  */
        $this->artisan($this->signature)->assertExitCode(0);

        /**  We check that the active planUser changed its status to finished  */
        $this->assertDatabaseHas('plan_user', [
            'id' => $activePlan->id,
            'plan_status_id' => PlanStatus::FINISHED
        ]);
        
        /**  We check that the prepurchase planUser changed its status to active  */
        $this->assertDatabaseHas('plan_user', [
            'id' => $prePurchasePlan->id,
            'plan_status_id' => PlanStatus::ACTIVE
        ]);
    }

    /** @test */
    public function it_pre_purchase_plan_keeps_pre_purchase_status_if_start_date_is_after_today()
    {
        $prePurchasePlan = PlanUser::withoutEvents(function () {
            return factory(PlanUser::class)->create([
            'start_date' => today()->addDay(),
            'plan_status_id' => PlanStatus::PRE_PURCHASE
            ]);
        });

        $this->assertDatabaseHas('plan_user', [
            'id' => $prePurchasePlan->id,
            'plan_status_id' => PlanStatus::PRE_PURCHASE
        ]);

        /**  Call the command  */
        $this->artisan($this->signature)->assertExitCode(0);

        /**  We check that the planUser changed its status to active  */
        $this->assertDatabaseHas('plan_user', [
            'id' => $prePurchasePlan->id,
            'plan_status_id' => PlanStatus::PRE_PURCHASE
        ]);
    }

    /** @test */
    public function it_active_plan_that_finish_today_doesnt_change_their_status_if_its_still_today()
    {
        $planSoonToFinish = $this->fakeActivePlanUser([
            'finish_date' => today()->format('Y-m-d 23:59:59')
        ]);

        /**  Call the command  */
        $this->artisan($this->signature)->assertExitCode(0);

        /**  We check that the planUser changed its status to active  */
        $this->assertDatabaseHas('plan_user', [
            'id' => $planSoonToFinish->id,
            'plan_status_id' => PlanStatus::ACTIVE
        ]);
    }

    /** @test */
    public function it_active_plan_that_finish_today_changes_status_to_finished_if_current_day_is_tomorrow()
    {
        $this->travelTo(now()->startOfDay());

        $planSoonToFinish = PlanUser::withoutEvents(function () {
            return factory(PlanUser::class)->create([
                'finish_date' => today()->subDay()->format('Y-m-d'),
                'plan_status_id' => PlanStatus::ACTIVE,
            ]);
        });

        $this->assertDatabaseHas('plan_user', [
            'id' => $planSoonToFinish->id,
            'plan_status_id' => PlanStatus::ACTIVE
        ]);

        /**  Call the command  */
        $this->artisan($this->signature)->assertExitCode(0);

        /**  We check that the planUser changed its status to active  */
        $this->assertDatabaseHas('plan_user', [
            'id' => $planSoonToFinish->id,
            'plan_status_id' => PlanStatus::FINISHED
        ]);
    }

    public function travelTo($date)
    {
        Carbon::setTestNow($date);
    }

    /** @test */
    public function it_doesnt_consider_deleted_plans()
    {
        $lastUpdateTime = now()->startOfMinute();

        $deletedPlan = factory(PlanUser::class)->create([
            'finish_date' => today(),
            'plan_status_id' => PlanStatus::ACTIVE,
            'updated_at' => $lastUpdateTime,
            'deleted_at' => $lastUpdateTime,
        ]);

        /**  Call the command  */
        $this->artisan($this->signature)
            ->expectsOutput('Number of plans to update: 0')
            ->assertExitCode(0);

        /**  We check that the planUser changed its status to active  */
        $this->assertDatabaseHas('plan_user', [
            'id' => $deletedPlan->id,
            'updated_at' => $lastUpdateTime->copy()->format('Y-m-d H:i:s')
        ]);
    }

    // if user has a plan that is going to start the status of the user is inactive
    /** @test */
    public function it_user_keeps_their_status_if_has_a_plan_that_hasnt_started_yet()
    {
        $user = User::withoutEvents(function () {
            return factory(User::class)->create([
                'status_user_id' => StatusUser::ACTIVE
            ]);
        });

        $plan = PlanUser::withoutEvents(function () use($user) {
            return factory(PlanUser::class)->create([
                'user_id' => $user->id,
                'start_date' => today()->addDay(),
                'finish_date' => today()->addDays(2),
                'plan_status_id' => PlanStatus::PRE_PURCHASE
            ]);
        });

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status_user_id' => StatusUser::ACTIVE
        ]);

        /**  Call the command  */
        $this->artisan($this->signature)->assertExitCode(0);

        /**  We check that the planUser changed its status to active  */
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status_user_id' => StatusUser::ACTIVE
        ]);
    }

    /** @test */
    public function it_user_staus_is_test_when_current_plan_type_is_a_test_type()
    {
        $user = User::withoutEvents(function () {
            return factory(User::class)->create([
                'status_user_id' => StatusUser::ACTIVE
            ]);
        });

        $plan = PlanUser::withoutEvents(function () use($user) {
            return factory(PlanUser::class)->create([
                'user_id' => $user->id,
                'start_date' => today()->subDay(),
                'finish_date' => today()->addDays(2),
                'plan_status_id' => PlanStatus::PRE_PURCHASE,
                'plan_id' => 1
            ]);
        });

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status_user_id' => StatusUser::ACTIVE
        ]);

        /**  Call the command  */
        $this->artisan($this->signature)->assertExitCode(0);

        /**  We check that the planUser changed its status to active  */
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status_user_id' => StatusUser::TEST
        ]);
    }
}
