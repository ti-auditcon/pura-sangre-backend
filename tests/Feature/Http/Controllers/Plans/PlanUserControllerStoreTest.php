<?php

namespace Tests\Feature\Http\Controllers\Plans;

use Tests\TestCase;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Users\StatusUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlanUserControllerStoreTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    protected $user;

    protected $plan;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'first_name' => 'Student name',
        ]);

        $this->plan = factory(Plan::class)->create([
            'id' => 99,
        ]);
    }

    /**
     * When the finish date of the plan is today of after today,
     * the time of the finish date is set to the end of the day (23:59:59)
     * 
     * @test
     */
    public function it_plan_ends_at_the_end_of_the_day()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->admin)
            ->post(route('users.plans.store', [
                'user' => $this->user->id,
            ]), [
                'start_date' => today()->format('Y-m-d'),
                'finish_date' => today()->format('Y-m-d'),
                'class_numbers' => 10,
                'clases_by_day' => 1,
                'plan_id' => $this->plan->id,
            ]);

        $this->assertDatabaseHas('plan_user', [
            'user_id' => $this->user->id,
            'finish_date' => today()->endOfDay()->format('Y-m-d H:i:s'),
        ]);
    }

    // plan created starts at the beginning of the day 00:00:00 if the start date is after today
    /** @test */
    public function it_created_plan_starts_at_the_beginning_of_the_day_when_the_start_date_is_after_today()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->admin)
            ->post(route('users.plans.store', [
                'user' => $this->user->id,
            ]), [
                'start_date' => today()->addDay()->format('Y-m-d'),
                'finish_date' => today()->addDay()->format('Y-m-d'),
                'class_numbers' => 10,
                'clases_by_day' => 1,
                'plan_id' => $this->plan->id,
            ]);

        $this->assertDatabaseHas('plan_user', [
            'user_id' => $this->user->id,
            'start_date' => today()->addDay()->startOfDay()->format('Y-m-d H:i:s'),
        ]);
    }

    // plan created starts now if the start date is today
    /** 
     * when the start date of the plan is today, the time of the start date is set to now (start of the minute)
     * @test
     */
    public function it_created_plan_starts_now_when_the_start_date_is_today()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->admin)
            ->post(route('users.plans.store', [
                'user' => $this->user->id,
            ]), [
                'start_date' => today()->format('Y-m-d'),
                'finish_date' => today()->addDay()->format('Y-m-d'),
                'class_numbers' => 10,
                'clases_by_day' => 1,
                'plan_id' => $this->plan->id,
            ]);

        $this->assertDatabaseHas('plan_user', [
            'user_id' => $this->user->id,
            'start_date' => now()->startOfMinute()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * If the plan starts before today,
     * the time of the start date is set to the beginning of that day (00:00:00) 
     * 
     * ex. if today is january 10, 
     *  and the plan starts at january 9,
     *  the start date of the plan is set to january 9 at 00:00:00
     * 
     * @test */
    public function it_created_plan_starts_before_today_at_begining_when_the_start_date_is_before_today()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->admin)
            ->post(route('users.plans.store', [
                'user' => $this->user->id,
            ]), [
                'start_date' => today()->subDay()->format('Y-m-d'),
                'finish_date' => today()->addDay()->format('Y-m-d'),
                'class_numbers' => 10,
                'clases_by_day' => 1,
                'plan_id' => $this->plan->id,
            ]);

        $this->assertDatabaseHas('plan_user', [
            'user_id' => $this->user->id,
            'start_date' => today()->subDay()->startOfDay()->format('Y-m-d 00:00:00'),
        ]);
    }

    // if plan is pre_purchase, the user status is set to inactive
    /** @test */
    public function it_user_status_is_set_to_inactive_when_plan_is_pre_purchase()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->admin)
            ->post(route('users.plans.store', [
                'user' => $this->user->id,
            ]), [
                'start_date' => today()->addDay()->format('Y-m-d'),
                'finish_date' => today()->addDay()->format('Y-m-d'),
                'class_numbers' => 10,
                'clases_by_day' => 1,
                'plan_id' => $this->plan->id,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'status_user_id' => StatusUser::INACTIVE
        ]);
    }

    // if plan is active, the user status is set to active
    /** @test */
    public function it_user_status_is_set_to_active_when_plan_is_active()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->admin)
            ->post(route('users.plans.store', [
                'user' => $this->user->id,
            ]), [
                'start_date' => today()->format('Y-m-d'),
                'finish_date' => today()->addDay()->format('Y-m-d'),
                'class_numbers' => 10,
                'clases_by_day' => 1,
                'plan_id' => $this->plan->id,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'status_user_id' => StatusUser::ACTIVE
        ]);
    }

    // if plan is active, and plan is TEST, the user status is set to TEST
    /** @test */
    public function it_user_status_is_set_to_test_when_plan_is_active_and_plan_is_trial()
    {
        $this->withoutExceptionHandling();

        $testPlan = factory(Plan::class)->create([
            'id' => Plan::TRIAL
        ]);

        $this->actingAs($this->admin)
            ->post(route('users.plans.store', [
                'user' => $this->user->id,
            ]), [
                'start_date' => today()->format('Y-m-d'),
                'finish_date' => today()->addDay()->format('Y-m-d'),
                'class_numbers' => 10,
                'clases_by_day' => 1,
                'plan_id' => $testPlan->id,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'status_user_id' => StatusUser::TEST
        ]);
    }

    /** 
     * to simulate a plan that is finished, we set the finish date to yesterday
     * 
     * @test 
     */
    public function it_user_status_is_set_to_inactive_when_plan_is_finished_and_user_does_not_have_any_other_active_plan()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->admin)
            ->post(route('users.plans.store', [
                'user' => $this->user->id,
            ]), [
                'start_date' => today()->subDays(2)->format('Y-m-d'),
                'finish_date' => today()->subDay()->format('Y-m-d'),
                'class_numbers' => 10,
                'clases_by_day' => 1,
                'plan_id' => $this->plan->id,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'status_user_id' => StatusUser::INACTIVE
        ]);
    }
}
