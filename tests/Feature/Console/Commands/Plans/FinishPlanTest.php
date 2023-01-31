<?php

namespace Tests\Feature\Console\Commands\Plans;

use Tests\TestCase;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FinishPlanTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    
    /**
     *   Make easy to respresent 0 quotas
     *
     *  @var  integer
     */
    const NO_QUOTAS = 0;

    /**
     *  Make easy to respresent the plan needs to have quptas
     *
     *  @var  integer
     */
    const ONE_QUOTA = 1;

    /**
     *  Name of the command
     *
     *  @var  string
     */
    protected $signature = "purasangre:plans:finish";

    protected $plan;

    public function SetUp(): void
    {
        parent::setUp();

        $this->plan = factory(Plan::class)->create([
            'class_numbers' => self::ONE_QUOTA
        ]);
    }

    // select only plans with 0 or less counter
    /** @test */
    public function it_iterates_just_plans_with_zero_or_less_quotas()
    {
        $planUser = factory(PlanUser::class)->create([
            'counter' => self::NO_QUOTAS,
            'plan_status_id' => PlanStatus::ACTIVE,
            'plan_id' => factory(Plan::class)->create([
                'class_numbers' => self::ONE_QUOTA
            ]),
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id' => $planUser->id,
            'counter' => self::NO_QUOTAS,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $this->artisan($this->signature)
            ->expectsOutput("Plan numbers to be iterated: 1")
            ->expectsOutput("Plan with id: {$planUser->id} is going to be closed")
            ->assertExitCode(0);
    }

    /** @test */
    public function it_iterates_just_plans_with_active_status()
    {
        $this->withExceptionHandling();

        $plan = factory(Plan::class)->create([
            'class_numbers' => self::ONE_QUOTA
        ]);

        PlanUser::withoutEvents(function  () use($plan) {
            factory(PlanUser::class)->create([
                'counter' => self::NO_QUOTAS,
                'plan_status_id' => PlanStatus::FINISHED,
                'plan_id' => $plan->id,
                'user_id' => $this->admin->id,
            ]);
            
            factory(PlanUser::class)->create([
                'counter' => self::NO_QUOTAS,
                'plan_status_id' => PlanStatus::FREEZED,
                'plan_id' => $plan->id,
                'user_id' => $this->admin->id,
            ]);
            
            factory(PlanUser::class)->create([
                'counter' => self::NO_QUOTAS,
                'plan_status_id' => PlanStatus::PRE_PURCHASE,
                'plan_id' => $plan->id,
                'user_id' => $this->admin->id,
            ]);

            factory(PlanUser::class)->create([
                'counter' => self::NO_QUOTAS,
                'plan_status_id' => PlanStatus::CANCELED,
                'plan_id' => $plan->id,
                'user_id' => $this->admin->id,
            ]);
        });

        $this->assertDatabaseMissing('plan_user', [
            'plan_status_id' => PlanStatus::ACTIVE,
            'user_id' => $this->admin->id,
        ]);

        $this->artisan($this->signature)
            ->expectsOutput("Plan numbers to be iterated: 0")
            ->assertExitCode(0);

    }

    /** @test */
    public function it_plan_is_not_closed_if_there_are_quotas_left_even_the_last_plan_day()
    {
        $plan = factory(Plan::class)->create([
            'class_numbers' => self::ONE_QUOTA
        ]);

        $planUser = PlanUser::withoutEvents(function  () use($plan) {
            return factory(PlanUser::class)->create([
                'counter' => self::ONE_QUOTA,
                'plan_status_id' => PlanStatus::ACTIVE,
                'plan_id' => $plan->id,
                'user_id' => $this->admin->id,
                'finish_date' => today(),
            ]);
        });

        $this->assertDatabaseHas('plan_user', [
            'id' => $planUser->id,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $this->artisan($this->signature)
            ->expectsOutput("Plan numbers to be iterated: 0")
            ->assertExitCode(0);
    }

    /** @test */
    public function it_plan_is_not_finished_if_has_pending_reservations()
    {
        $this->withExceptionHandling();

        $plan = factory(Plan::class)->create([
            'class_numbers' => self::ONE_QUOTA
        ]);

        $planUser = PlanUser::withoutEvents(function  () use($plan) {
            return factory(PlanUser::class)->create([
                'counter' => self::NO_QUOTAS,
                'plan_status_id' => PlanStatus::ACTIVE,
                'plan_id' => $plan->id,
                'user_id' => $this->admin->id,
            ]);
        });

        Reservation::withoutEvents(function() use($planUser) {
            factory(Reservation::class)->create([
                'plan_user_id' => $planUser->id,
                'reservation_status_id' => ReservationStatus::PENDING,
                'clase_id' => factory(\App\Models\Clases\Clase::class)->create([
                    'date' => today(),
                ])->id,
            ]);
        });

        $this->assertDatabaseHas('reservations', [
            'plan_user_id' => $planUser->id,
            'reservation_status_id' => ReservationStatus::PENDING,
        ]);

        $this->artisan($this->signature)
        // its iterated but not finished...
            ->expectsOutput("Plan numbers to be iterated: 1")
            ->assertExitCode(0);

        $this->assertDatabaseHas('plan_user', [
            'id' => $planUser->id,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);
    }


    // plan is not closed if has confirmed reservations
    /** @test */
    public function it_plan_is_not_closed_if_it_has_confirmed_reservations()
    {
        $this->withExceptionHandling();

        $plan = factory(Plan::class)->create([
            'class_numbers' => self::ONE_QUOTA
        ]);

        $planUser = PlanUser::withoutEvents(function  () use($plan) {
            return factory(PlanUser::class)->create([
                'counter' => self::NO_QUOTAS,
                'plan_status_id' => PlanStatus::ACTIVE,
                'plan_id' => $plan->id,
                'user_id' => $this->admin->id,
            ]);
        });

        Reservation::withoutEvents(function() use($planUser) {
            factory(Reservation::class)->create([
                'plan_user_id' => $planUser->id,
                'reservation_status_id' => ReservationStatus::CONFIRMED,
                'clase_id' => factory(\App\Models\Clases\Clase::class)->create([
                    'date' => today(),
                ])->id,
            ]);
        });

        $this->assertDatabaseHas('reservations', [
            'plan_user_id' => $planUser->id,
            'reservation_status_id' => ReservationStatus::CONFIRMED,
        ]);

        $this->artisan($this->signature)
        // its iterated but not finished...
            ->expectsOutput("Plan numbers to be iterated: 1")
            ->assertExitCode(0);

        $this->assertDatabaseHas('plan_user', [
            'id' => $planUser->id,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);
    }

    /** @test */
    public function it_finish_date_is_changed_to_today_when_closing_the_plan()
    {
        $this->withExceptionHandling();

        $planUser = PlanUser::withoutEvents(function  () {
            return factory(PlanUser::class)->create([
                'counter' => self::NO_QUOTAS,
                'plan_status_id' => PlanStatus::ACTIVE,
                'plan_id' => $this->plan->id,
                'user_id' => $this->admin->id,
                'finish_date' => today()->addDay(),
            ]);
        });

        $this->assertDatabaseHas('plan_user', [
            'id' => $planUser->id,
            'plan_status_id' => PlanStatus::ACTIVE,
            'finish_date' => today()->addDay(),
        ]);

        $this->artisan($this->signature)->assertExitCode(0);

        $this->assertDatabaseHas('plan_user', [
            'id' => $planUser->id,
            'plan_status_id' => PlanStatus::FINISHED,
            'finish_date' => now()->startOfMinute()->format('Y-m-d H:i:s'),
        ]);
    }

    // it when closing the plan, the status is changed to finished
    /** @test */
    public function it_when_closing_the_plan_the_status_is_changed_to_finished()
    {
        $this->withExceptionHandling();

        $planUser = PlanUser::withoutEvents(function  () {
            return factory(PlanUser::class)->create([
                'counter' => self::NO_QUOTAS,
                'plan_status_id' => PlanStatus::ACTIVE,
                'plan_id' => $this->plan->id,
                'user_id' => $this->admin->id,
            ]);
        });

        $this->assertDatabaseHas('plan_user', [
            'id' => $planUser->id,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $this->artisan($this->signature)->assertExitCode(0);

        $this->assertDatabaseHas('plan_user', [
            'id' => $planUser->id,
            'plan_status_id' => PlanStatus::FINISHED,
        ]);
    }

    // iterates just for plans not deleted
    /** @test */
    public function it_iterates_just_for_plans_not_deleted()
    {
        $this->withExceptionHandling();

        $planUser = PlanUser::withoutEvents(function  () {
            return factory(PlanUser::class)->create([
                'counter' => self::NO_QUOTAS,
                'plan_status_id' => PlanStatus::ACTIVE,
                'plan_id' => $this->plan->id,
                'user_id' => $this->admin->id,
            ]);
        });

        $this->assertDatabaseHas('plan_user', [
            'id' => $planUser->id,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $this->plan->delete();

        $this->artisan($this->signature)
            ->expectsOutput("Plan numbers to be iterated: 0")
            ->assertExitCode(0);

        $this->assertDatabaseHas('plan_user', [
            'id' => $planUser->id,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);
    }

}
