<?php

namespace Tests\Unit\Http\Requests\Plans;

use Tests\TestCase;
use App\Models\Users\User;
use App\Models\Plans\PlanStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 *  Reference.  [ ] --> represents the start and end of a planUser
 */
class PlanUserStoreRequestTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    
    /**
     * The start date of the plan user being created cannot be
     * between the start and end dates of an active or pre_purcahsed plan.
     *
     * Reference.  [ ] --> represents the start and end of a planUser
     *
     * [-- active_or_pre_purchase_plan_user --]
     *                       [--    creating_plan_user     --]
     * 
     *  @test
     */
    public function it_start_date_cannot_be_before_the_finish_date_of_an_active_or_pre_purchase_planUser()
    {
        $user = factory(User::class)->create();
        // we create a plan that finish in one minute more than the current date
        $previous_plan_user = $this->fakeActivePlanUser([
            'start_date'  => now()->subDays(20)->format('Y-m-d H:i:s'),
            'finish_date' => now()->addMinute()->format('Y-m-d H:i:s'),
            'user_id'     => $user->id,
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $previous_plan_user->id,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $plan = $this->fakePlan();

        // we send to PlanUserRequest a request with a start_date 1 hour before now
        $this->actingAs($this->admin)->post(route('users.plans.store', [
            'user' => $user,
            'plan' => $plan,
        ]), [
            'start_date'    => now()->format('Y-m-d H:i:s'),
            'finish_date'   => now()->addMonths(1)->format('Y-m-d H:i:s'),
            'counter'       => 12,
            'class_numbers' => 12,
            'clases_by_day' => 1,
            'user_id'       => $user->id,
        ])->assertSessionHasErrors([
            'start_date' => 'Las fechas chocan con un plan que ya tiene el usuario.',
        ]);
    }

    /**
     *  The finish date of the planUser being created
     *  cannot be between the start and end dates of an active or pre_purcahsed plan
     *
     *  Reference.  [ ] --> represents the start and end of a planUser
     * 
     *  [- previous_plan_user -]           [-- active_or_pre_purchase_plan_user --]
     *                               [-- creating_plan_user --]
     *
     *   @test
     */
    public function it_finish_date_cannot_be_after_the_start_date_of_an_active_or_pre_purchase_planUser()
    {
        $user = factory(User::class)->create();

        $active_plan_user = $this->fakeActivePlanUser([
            'start_date'     => now()->subDays(1)->format('Y-m-d H:i:s'),
            'finish_date'    => now()->addDays(10)->format('Y-m-d H:i:s'),
            'user_id'     => $user->id,
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $active_plan_user->id,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $plan = $this->fakePlan();

        $this->actingAs($this->admin)->post(route('users.plans.store', [
            'user' => $user,
            'plan' => $plan,
        ]), [
            'start_date'    => now()->subDays(2)->format('Y-m-d H:i:s'),
            'finish_date'   => now()->addDays(2)->format('Y-m-d H:i:s'),
            'counter'       => 12,
            'class_numbers' => 12,
            'clases_by_day' => 1,
            'user_id'       => $user->id,
        ])->assertSessionHasErrors([
            'start_date' => 'Las fechas chocan con un plan que ya tiene el usuario.',
        ])->assertSessionDoesntHaveErrors([
            'finish_date' => 'La fecha de inicio choca con un plan que ya tiene el usuario.',
        ]);
    }

    /**
     *
     *  Ex.  [ ] --> represents the start and end of a planUser
     *  [- previous_plan_user -]  [-- active_or_pre_purchase_plan_user --]
     *                               [-- creating_plan_user --]
     * 
     *   @test
     */
    public function it_start_and_end_dates_cannot_be_between_plan_user()
    {
        $user = factory(User::class)->create();

        $active_plan_user = $this->fakeActivePlanUser([
            'start_date'     => now()->subDays(10)->format('Y-m-d H:i:s'),
            'finish_date'    => now()->addDays(10)->format('Y-m-d H:i:s'),
            'user_id'     => $user->id,
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $active_plan_user->id,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $plan = $this->fakePlan();

        // we send to PlanUserRequest a request with a start_date 1 hour before now
        $this->actingAs($this->admin)->post(route('users.plans.store', [
            'user' => $user,
            'plan' => $plan,
        ]), [
            'start_date'    => now()->subDays(5)->format('Y-m-d H:i:s'),
            'finish_date'   => now()->addDays(5)->format('Y-m-d H:i:s'),
            'counter'       => 12,
            'class_numbers' => 12,
            'clases_by_day' => 1,
            'user_id'       => $user->id,
        ])->assertSessionHasErrors([
            'start_date' => 'Las fechas chocan con un plan que ya tiene el usuario.',
        ]);
    }

    /**
     *  The start and finish dates of the planUser being created cannot be outside
     *  the start and end dates of an active or pre_purcahsed plan
     *
     *          [-- active_or_pre_purchase_plan_user --]
     *   [------         creating_plan_user            ------]
     *
     *  @test
     */
    public function it_start_and_end_dates_cannot_be_outside_a_plan_user()
    {
        $user = factory(User::class)->create();

        $active_plan_user = $this->fakeActivePlanUser([
            'start_date'     => now()->format('Y-m-d H:i:s'),
            'finish_date'    => now()->addMinute()->format('Y-m-d H:i:s'),
            'user_id'     => $user->id,
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $active_plan_user->id,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $plan = $this->fakePlan();

        // we send to PlanUserRequest a request with a start_date 1 hour before now
        $this->actingAs($this->admin)->post(route('users.plans.store', [
            'user' => $user,
            'plan' => $plan,
        ]), [
            'start_date'    => now()->subMinute()->format('Y-m-d H:i:s'),
            'finish_date'   => now()->addMinutes(2)->format('Y-m-d H:i:s'),
            'counter'       => 12,
            'class_numbers' => 12,
            'clases_by_day' => 1,
            'user_id'       => $user->id,
        ])->assertSessionHasErrors([
            'start_date' => 'Las fechas chocan con un plan que ya tiene el usuario.',
        ])->assertSessionDoesntHaveErrors([
            'finish_date' => 'La fecha de inicio choca con un plan que ya tiene el usuario.',
        ]);
    }

    /** 
     * A plan can be created after an active plan
     *  [-- active_plan --]
     *                      [--- creating_plan_user ---]
     * @test 
     */
    public function it_plan_user_can_be_created_after_an_active_plan()
    {
        $user = factory(User::class)->create();

        $active_plan_user = $this->fakeActivePlanUser([
            'start_date'     => now()->subDays(10)->format('Y-m-d H:i:s'),
            'finish_date'    => now()->subMinute()->format('Y-m-d H:i:s'),
            'user_id'     => $user->id,
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $active_plan_user->id,
            'plan_status_id' => PlanStatus::ACTIVE,
            'start_date'     => now()->subDays(10)->format('Y-m-d H:i:s'),
            'finish_date'    => now()->subMinute()->format('Y-m-d H:i:s'),
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $active_plan_user->id,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $plan = $this->fakePlan();

        $this->actingAs($this->admin)->post(route('users.plans.store', [
            'user' => $user,
            'plan' => $plan,
        ]), [
            'start_date'    => now()->format('Y-m-d H:i:s'),
            'finish_date'   => now()->addDays(5)->format('Y-m-d H:i:s'),
            'counter'       => 12,
            'class_numbers' => 12,
            'clases_by_day' => 1,
            'user_id'       => $user->id,
        ])->assertSessionDoesntHaveErrors([
            'start_date' => 'Las fechas chocan con un plan que ya tiene el usuario.',
        ]);
    }
       
    /** 
     * A plan can be created before an active plan
     *                               [-- active_plan --]
     * [--- creating_plan_user ---]
     * @test 
     */
    public function it_plan_user_can_be_created_before_an_active_plan()
    {
        $user = factory(User::class)->create();

        $active_plan_user = $this->fakeActivePlanUser([
            'start_date'     => now()->addDays(10)->format('Y-m-d H:i:s'),
            'finish_date'    => now()->addDays(20)->format('Y-m-d H:i:s'),
            'user_id'     => $user->id,
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $active_plan_user->id,
            'plan_status_id' => PlanStatus::ACTIVE,
            'start_date'     => now()->addDays(10)->format('Y-m-d H:i:s'),
            'finish_date'    => now()->addDays(20)->format('Y-m-d H:i:s'),
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $active_plan_user->id,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $plan = $this->fakePlan();

        $this->actingAs($this->admin)->post(route('users.plans.store', [
            'user' => $user,
            'plan' => $plan,
        ]), [
            'start_date'    => now()->format('Y-m-d H:i:s'),
            'finish_date'   => now()->addDays(5)->format('Y-m-d H:i:s'),
            'counter'       => 12,
            'class_numbers' => 12,
            'clases_by_day' => 1,
            'user_id'       => $user->id,
        ])->assertSessionDoesntHaveErrors([
            'start_date' => 'Las fechas chocan con un plan que ya tiene el usuario.',
        ]);
    }

    /** 
     * A plan can be created after an pre_purchase plan
     *  [-- pre_purchase --]
     *                      [--- creating_plan_user ---]
     * @test 
     */
    public function it_plan_user_can_be_created_after_an_pre_purchase_plan()
    {
        $user = factory(User::class)->create();

        $active_plan_user = $this->fakeActivePlanUser([
            'start_date'     => now()->subDays(10)->format('Y-m-d H:i:s'),
            'finish_date'    => now()->subMinute()->format('Y-m-d H:i:s'),
            'plan_status_id' => PlanStatus::PRE_PURCHASE,
            'user_id'     => $user->id,
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $active_plan_user->id,
            'plan_status_id' => PlanStatus::PRE_PURCHASE,
            'start_date'     => now()->subDays(10)->format('Y-m-d H:i:s'),
            'finish_date'    => now()->subMinute()->format('Y-m-d H:i:s'),
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $active_plan_user->id,
            'plan_status_id' => PlanStatus::PRE_PURCHASE,
        ]);

        $plan = $this->fakePlan();

        $this->actingAs($this->admin)->post(route('users.plans.store', [
            'user' => $user,
            'plan' => $plan,
        ]), [
            'start_date'    => now()->format('Y-m-d H:i:s'),
            'finish_date'   => now()->addDays(5)->format('Y-m-d H:i:s'),
            'counter'       => 12,
            'class_numbers' => 12,
            'clases_by_day' => 1,
            'user_id'       => $user->id,
        ])->assertSessionDoesntHaveErrors([
            'start_date' => 'Las fechas chocan con un plan que ya tiene el usuario.',
        ]);
    }

    /** 
     * A plan can be created before an pre_purchase plan
     *                               [-- pre_purchase --]
     * [--- creating_plan_user ---]
     * @test 
     */
    public function it_plan_user_can_be_created_before_an_pre_purchase_plan()
    {
        $user = factory(User::class)->create();

        $active_plan_user = $this->fakeActivePlanUser([
            'start_date'     => now()->addDays(10)->format('Y-m-d H:i:s'),
            'finish_date'    => now()->addDays(20)->format('Y-m-d H:i:s'),
            'plan_status_id' => PlanStatus::PRE_PURCHASE,
            'user_id'     => $user->id,
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $active_plan_user->id,
            'plan_status_id' => PlanStatus::PRE_PURCHASE,
            'start_date'     => now()->addDays(10)->format('Y-m-d H:i:s'),
            'finish_date'    => now()->addDays(20)->format('Y-m-d H:i:s'),
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $active_plan_user->id,
            'plan_status_id' => PlanStatus::PRE_PURCHASE,
        ]);

        $plan = $this->fakePlan();

        $this->actingAs($this->admin)->post(route('users.plans.store', [
            'user' => $user,
            'plan' => $plan,
        ]), [
            'start_date'    => now()->format('Y-m-d H:i:s'),
            'finish_date'   => now()->addDays(5)->format('Y-m-d H:i:s'),
            'counter'       => 12,
            'class_numbers' => 12,
            'clases_by_day' => 1,
            'user_id'       => $user->id,
        ])->assertSessionDoesntHaveErrors([
            'start_date' => 'Las fechas chocan con un plan que ya tiene el usuario.',
        ]);
    }
}
