<?php

namespace Tests\Feature\Http\Controllers\Plans;

use Tests\TestCase;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlanUserControllerUpdateTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    public const PLAN_IS_FREEZED_MESSAGE = 'El plan no puede ser actualizado porque está congelado.';

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

    /** @test */
    public function it_plan_is_updated_correctly()
    {
        $planUser = $this->fakeActivePlanUser([
            'plan_id' => $this->plan->id,
            'user_id' => $this->user->id,
            'start_date' => today()->format('Y-m-d H:i:s'),
            'finish_date' => today()->endOfday()->format('Y-m-d H:i:s'),
        ]);

        $this->assertDatabaseHas('plan_user', [
            'user_id' => $this->user->id,
            'plan_id' => $this->plan->id,
            'start_date' => today()->format('Y-m-d H:i:s'),
            'finish_date' => today()->endOfDay()->format('Y-m-d H:i:s'),
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('users.plans.update', [
                'user' => $this->user->id,
                'plan' => $planUser->id,
            ]), [
                'start_date' => today()->subDays(1)->format('Y-m-d'),
                'finish_date' => today()->addDays(10)->format('Y-m-d'),
                'class_numbers' => 10,
                'clases_by_day' => 1,
                'plan_id' => $this->plan->id,
                'plan_user_id' => $planUser->id,
            ]);

            $response->assertSessionHas([
                'success' => 'El plan se actualizó correctamente',
            ]);

        $this->assertDatabaseHas('plan_user', [
            'user_id' => $this->user->id,
            'plan_id' => $this->plan->id,
            'start_date' => today()->subDays(1)->format('Y-m-d H:i:s'),
            'finish_date' => today()->addDays(10)->endOfDay()->format('Y-m-d H:i:s'),
            'plan_status_id' => PlanStatus::ACTIVE
        ]);
    }

    /** @test */
    public function it_cannot_update_plan_if_its_freezed()
    {
        $planUser = $this->fakeActivePlanUser([
            'plan_id' => $this->plan->id,
            'user_id' => $this->user->id,
            'start_date' => today()->format('Y-m-d H:i:s'),
            'finish_date' => today()->endOfday()->format('Y-m-d H:i:s'),
            'plan_status_id' => PlanStatus::FREEZED
        ]);

        $this->assertDatabaseHas('plan_user', [
            'user_id' => $this->user->id,
            'plan_id' => $this->plan->id,
            'start_date' => today()->format('Y-m-d H:i:s'),
            'finish_date' => today()->endOfDay()->format('Y-m-d H:i:s'),
        ]);

        $this->actingAs($this->admin)
            ->put(route('users.plans.update', [
                'user' => $this->user->id,
                'plan' => $planUser->id,
            ]), [
                'start_date' => today()->addDays(5)->format('Y-m-d'),
                'finish_date' => today()->addDays(10)->format('Y-m-d'),
                'class_numbers' => 10,
                'clases_by_day' => 1,
                'plan_id' => $this->plan->id,
                'plan_user_id' => $planUser->id,
            ])->assertSessionHasErrors([
                'plan_user_id' => self::PLAN_IS_FREEZED_MESSAGE
            ]);

        $this->assertDatabaseHas('plan_user', [
            'user_id' => $this->user->id,
            'plan_id' => $this->plan->id,
            'start_date' => today()->format('Y-m-d H:i:s'),
            'finish_date' => today()->endOfDay()->format('Y-m-d H:i:s'),
            'plan_status_id' => PlanStatus::FREEZED
        ]);
    }

    /** @test */
    public function it_plan_is_updated_to_active_if_user_reactivates_it()
    {
        $this->withoutExceptionHandling();

        $planUser = $this->fakeActivePlanUser([
            'plan_id' => $this->plan->id,
            'user_id' => $this->user->id,
            'start_date' => today()->format('Y-m-d H:i:s'),
            'finish_date' => today()->endOfday()->format('Y-m-d H:i:s'),
            'plan_status_id' => PlanStatus::FINISHED
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id' => $planUser->id,
            'plan_status_id' => PlanStatus::FINISHED,
        ]);

        $this->actingAs($this->admin)
            ->put(route('users.plans.update', [
                'user' => $this->user->id,
                'plan' => $planUser->id,
            ]), [
                'start_date' => today()->format('Y-m-d'),
                'finish_date' => today()->format('Y-m-d'),
                'plan_id' => $this->plan->id,
                'plan_user_id' => $planUser->id,
            ])->assertSessionHas([
                'success' => 'El plan se actualizó correctamente',
            ]);

        $this->assertDatabaseHas('plan_user', [
            'id' => $planUser->id,
            'plan_status_id' => PlanStatus::ACTIVE
        ]);
    }

    /** @test */
    public function it_cannot_update_an_active_plan_if_the_dates_overlap_with_an_active_frozen_plan()
    {
        $frozenPlan = PlanUser::withoutEvents(function () {
            return factory('App\Models\Plans\PlanUser')->create([
                'plan_status_id' => PlanStatus::FROZEN,
                'start_date' => '2019-01-01 00:00:00',
                'finish_date' => '2019-01-31 23:59:59',
            ]);
        });

        $activePlan = PlanUser::withoutEvents(function () use($frozenPlan) {
            return factory('App\Models\Plans\PlanUser')->create([
                'plan_status_id' => PlanStatus::PRE_PURCHASE,
                'start_date' => '2019-02-01 00:00:00',
                'finish_date' => '2019-02-28 23:59:59',
                'user_id' => $frozenPlan->user_id,
                'observations' => 'active plan'
            ]);
        });

        $this->actingAs($this->admin)
            ->put(route('users.plans.update', [
                'user' => $activePlan->user_id,
                'plan' => $activePlan->id,
                'plan_user_id' => $activePlan->id,
                'user_id' => $activePlan->user_id,
            ]), [
                'start_date' => '2019-01-31', // this date overlaps with the frozen plan
                'finish_date' => '2019-02-28',
            ]);

        $this->assertDatabaseHas('plan_user', [
            'id' => $activePlan->id,
            'start_date' => $activePlan->start_date->format('Y-m-d H:i:s'),
            'finish_date' => $activePlan->finish_date->format('Y-m-d H:i:s'),
        ]);
    }
}
