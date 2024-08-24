<?php

namespace Tests\Unit\Console\Commands\Reports;

use Tests\TestCase;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Clases\Clase;
use Tests\Traits\CarbonTrait;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Console\Commands\Reports\MonthlyTrialUserReport;
use App\Console\Commands\Reports\MonthlyTrialUserReport as CommandClass;

class MonthlyTrialUsersReportTest extends TestCase
{
    use RefreshDatabase;
    use CarbonTrait;

    // trialsPlansAt counts only non-cancelled trial plans
    /** @test */
    public function it_trialsPlansAt_counts_only_non_cancelled_trial_plans()
    {
        // createa a cancelled trial plan
        $canceledTrialPlan = PlanUser::withoutEvents(function () {
            return factory(PlanUser::class)->create([
                'plan_status_id' => PlanStatus::CANCELED,
                'plan_id'        => Plan::TRIAL,
                'start_date'     => now()
            ]);
        });

        // createa a non-cancelled trial plan
        $nonCanceledTrialPlan = PlanUser::withoutEvents(function () {
            return factory(PlanUser::class)->create([
                'plan_status_id' => PlanStatus::ACTIVE,
                'plan_id'        => Plan::TRIAL,
                'start_date'     => now()->addDay()
            ]);
        });

        $trialCommandClass = new CommandClass;

        $this->assertEquals(
            1,
            $trialCommandClass->trialPlansAt(now(), now()->addDay())
        );
    }
    
    // trialsPlansAt counts only plans which starts after start value
    /** @test */
    public function it_trialsPlansAt_counts_only_plans_which_starts_between_start_and_end_values()
    {
        // createa a trial plan which starts before start value
        $trialStartBefore = PlanUser::withoutEvents(function () {
            return factory(PlanUser::class)->create([
                'start_date' => now()->subDay(),
                'plan_id'    => Plan::TRIAL,
            ]);
        });

        // createa a trial plan which starts after end value
        $trialStartAfter = PlanUser::withoutEvents(function () {
            return factory(PlanUser::class)->create([
                'start_date' => now()->addDay(),
                'plan_id'    => Plan::TRIAL,
            ]);
        });

        $trialCommandClass = new CommandClass;

        $this->assertEquals(
            0,
            $trialCommandClass->trialPlansAt(now()->startOfDay(), now()->endOfDay())
        );
    }

    // trialClassesConsumedAt counts plans with consumed reservations
    /** @test */
    public function it_trialClassesConsumedAt_counts_plans_with_at_least_one_consumed_reservations()
    {
        // creates trial plan and a consumed reservation belonging to it
        $trialPlan = PlanUser::withoutEvents(function () {
            return factory(PlanUser::class)->create([
                'plan_id'        => Plan::TRIAL,
                'start_date'     => now(),
                'plan_status_id' => PlanStatus::FINISHED,
            ]);
        });

        $consumedReservation = Reservation::withoutEvents(function () use ($trialPlan) {
            return factory(Reservation::class)->create([
                'plan_user_id'          => $trialPlan->id,
                'reservation_status_id' => ReservationStatus::CONSUMED,
            ]);
        });

        $nonConsumedReservation = Reservation::withoutEvents(function () use ($trialPlan) {
            return factory(Reservation::class)->create([
                'plan_user_id'          => $trialPlan->id,
                'reservation_status_id' => ReservationStatus::PENDING,
            ]);
        });

        $trialCommandClass = new CommandClass;

        $this->assertEquals(
            1,
            $trialCommandClass->trialClassesConsumedAt(now()->startOfDay(), now()->addDay())
        );
    }

    /** @test */
    public function it_trialClassesConsumedAt_doesnt_counts_plans_with_non_consumed_reservations()
    {
        // creates trial plan and a consumed reservation belonging to it
        $trialPlan = PlanUser::withoutEvents(function () {
            return factory(PlanUser::class)->create([
                'plan_id'        => Plan::TRIAL,
                'start_date'     => now(),
                'plan_status_id' => PlanStatus::FINISHED,
            ]);
        });

        $lostReservation = Reservation::withoutEvents(function () use ($trialPlan) {
            return factory(Reservation::class)->create([
                'plan_user_id'   => $trialPlan->id,
                'reservation_status_id' => ReservationStatus::LOST,
            ]);
        });

        $pendingReservation = Reservation::withoutEvents(function () use ($trialPlan) {
            return factory(Reservation::class)->create([
                'plan_user_id'   => $trialPlan->id,
                'reservation_status_id' => ReservationStatus::PENDING,
            ]);
        });

        $trialCommandClass = new CommandClass;

        $this->assertEquals(
            0,
            $trialCommandClass->trialClassesConsumedAt(now(), now()->addDay())
        );
    }

    // trialClassesConsumedAt counts consumed reservations that belong to its trial plan
    /** @test */
    public function it_trialClassesConsumedAt_counts_consumed_reservations_that_belong_to_its_trial_plan()
    {
        // creates trial plan and a consumed reservation belonging to it
        $trialPlan = PlanUser::withoutEvents(function () {
            return factory(PlanUser::class)->create([
                'plan_id'        => Plan::TRIAL,
                'start_date'     => now(),
                'plan_status_id' => PlanStatus::FINISHED,
            ]);
        });

        $otherNonTrialPlan = PlanUser::withoutEvents(function () {
            return factory(PlanUser::class)->create([
                'start_date'     => now()->addDay(),
                'plan_status_id' => PlanStatus::FINISHED,
            ]);
        });

        // creates a consumed reservation belonging to other plan
        $otherNonTrialPlanReservation = Reservation::withoutEvents(function () use ($otherNonTrialPlan) {
            return factory(Reservation::class)->create([
                'plan_user_id'   => $otherNonTrialPlan->id,
                'reservation_status_id' => ReservationStatus::CONSUMED,
            ]);
        });


        $trialCommandClass = new CommandClass;


        $this->assertEquals(
            0,
            $trialCommandClass->trialClassesConsumedAt(now(), now()->addDays(2)) // to consider both plans
        );
    }

    // trialConvertionAt 
    // users trial last class ends 14 days before the start of the plan, but not the first class of the trial plan
    /** @test */
    /** @test */
    public function it_just_last_trial_plan_class_is_in_range_of_trial_conversion()
    {
        $user = factory(User::class)->create();

        $this->travelTo('2024-01-01 00:00:00');
        $lastDayOfJanuary = now()->endOfMonth()->startOfDay();

        // users has a trial plan that start january 31, and the first consumed class is same day,
        // last class of the trial plan is February 1st, and the normal plan starts 14 days after february 1st
        $trialPlan = PlanUser::withoutEvents(function () use ($lastDayOfJanuary, $user) {
            return factory(PlanUser::class)->create([
                'plan_status_id' => PlanStatus::FINISHED,
                'plan_id'        => Plan::TRIAL,
                'start_date'     => $lastDayOfJanuary,
                'finish_date'    => $lastDayOfJanuary->copy()->addDays(7),
                'user_id'        => $user->id,
            ]);
        });

        $this->assertDatabaseHas('plan_user', [
            'id' => $trialPlan->id,
            'start_date' => '2024-01-31 00:00:00',
            'finish_date' => '2024-02-07 00:00:00',
            'plan_status_id' => PlanStatus::FINISHED,
        ]);

        $firstReservation = Reservation::withoutEvents(function () use ($trialPlan, $lastDayOfJanuary) {
            return factory(Reservation::class)->create([
                'plan_user_id'          => $trialPlan->id,
                'reservation_status_id' => ReservationStatus::CONSUMED,
                'clase_id'              => factory(Clase::class)->create([
                    'date'  => $lastDayOfJanuary->copy()->format('Y-m-d 10:00:00'),
                ])->id
            ]);
        });

        $this->assertDatabaseHas('clases', [
            'id' => $firstReservation->clase_id,
            'date' => '2024-01-31 10:00:00',
        ]);

        $lastReservation = Reservation::withoutEvents(function () use ($trialPlan, $lastDayOfJanuary) {
            return factory(Reservation::class)->create([
                'plan_user_id'          => $trialPlan->id,
                'reservation_status_id' => ReservationStatus::CONSUMED,
                'clase_id'              => factory(Clase::class)->create([
                    'date' => $lastDayOfJanuary->copy()->addDays(1)->format('Y-m-d 10:00:00'),
                ])->id
            ]);
        });

        $this->assertDatabaseHas('clases', [
            'id' => $lastReservation->clase_id,
            'date' => '2024-02-01 10:00:00',
        ]);

        $regularPlan = PlanUser::withoutEvents(function () use ($user) {
            return factory(PlanUser::class)->create([
                'plan_status_id' => PlanStatus::ACTIVE,
                'plan_id'        => 100,
                'start_date'     => today()->addMonth()->format('Y-m-10 00:00:00'),
                'finish_date'    => today()->addMonth(),
                'user_id'        => $user->id,
            ]);
        });

        $this->assertDatabaseHas('plan_user', [
            'id' => $regularPlan->id,
            'start_date' => '2024-02-10 00:00:00',
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $commandClass = new MonthlyTrialUserReport();

        $this->assertEquals(
            1,
            $commandClass->trialConvertionAt(now()->addMonth()->startOfMonth(), now()->addMonth()->endOfMonth())
        );
    }
}
