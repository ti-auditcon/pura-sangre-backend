<?php

namespace Tests\Unit\Console\Commands\Reports;

use Tests\TestCase;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Console\Commands\Reports\MonthlyTrialUserReport as CommandClass;

class MonthlyTrialUsersReportTest extends TestCase
{
    use RefreshDatabase;

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
}
