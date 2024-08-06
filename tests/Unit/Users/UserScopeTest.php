<?php

namespace Tests\Unit\Users;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserScopeTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    
    public function setUp(): void
    {
        parent::setUp();
    }
 
    /** @test */
    public function it_can_scope_users_active_in_date_range()
    {
        $startPreviousMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();

        $userInRange = factory(User::class)->create(['id' => 100]);
        $this->assertDatabaseHas('users', [
            'id' => 100
        ]);
        $planInRange = factory(Plan::class)->create(['id' => 100]);

        $planUser = PlanUser::create([
            'user_id'     => $userInRange->id,
            'plan_id'     => $planInRange->id,
            'start_date'  => $startPreviousMonth,
            'finish_date' => $endOfPreviousMonth,
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $planUser->id,
            'plan_id'        => 100,
            'start_date'     => $startPreviousMonth,
            'finish_date'    => $endOfPreviousMonth,
            'plan_status_id' => PlanStatus::FINISHED,
        ]);


        $userOutOfRange = factory(User::class)->create();
        PlanUser::create([
            'user_id'     => $userOutOfRange->id,
            'plan_id'     => $planInRange->id,
            'start_date'  => $endOfPreviousMonth->copy()->addDay(),
            'finish_date' => $endOfPreviousMonth->copy()->addMonth(),
        ]);

        $result = User::activeInDateRange($startPreviousMonth, $endOfPreviousMonth)->get();

        $this->assertCount(1, $result);

        $this->assertTrue($result->contains($userInRange));
        $this->assertFalse($result->contains($userOutOfRange));
    }

    /** @test */
    public function it_fetches_dropouts_between_dates()
    {
        // Preparar datos de prueba
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();

        $trialPlan = factory(Plan::class)->create(['id' => Plan::TRIAL]);
        $plan = factory(Plan::class)->create();

        $startDate = Carbon::create(2024, 8, 1);
        $endDate = Carbon::create(2024, 8, 31);

        // Usuarios que se dan de baja dentro del rango de fechas
        PlanUser::create([
            'user_id'        => $user1->id,
            'plan_id'        => $plan->id,
            'start_date'     => $startDate->copy()->subMonth(),
            'finish_date'    => $startDate->copy()->addDays(2),
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $dropouts = User::dropouts($startDate, $endDate)->get();

        // Usuario que no se da de baja porque tiene un nuevo plan inmediatamente
        PlanUser::create([
            'user_id' => $user2->id,
            'plan_id' => $plan->id,
            'start_date' => $startDate->copy()->subMonth(),
            'finish_date' => $startDate->copy()->addDays(2),
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);
        PlanUser::create([
            'user_id' => $user2->id,
            'plan_id' => $plan->id,
            'start_date' => $startDate->copy()->addDays(3),
            'finish_date' => $endDate->copy()->addMonth(),
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        // Usuario con plan de prueba (no debe ser considerado)
        PlanUser::create([
            'user_id' => $user3->id,
            'plan_id' => $trialPlan->id,
            'start_date' => $startDate->copy()->subMonth(),
            'finish_date' => $startDate->copy()->addDays(2),
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $dropouts = User::dropouts($startDate, $endDate)->get();

        // Assertions
        $this->assertCount(1, $dropouts);
        $this->assertTrue($dropouts->contains($user1));
        $this->assertFalse($dropouts->contains($user2));
        $this->assertFalse($dropouts->contains($user3));
    }

    /** @test */
    public function it_can_scope_new_students_in_date_range_not_consider_trial_plans()
    {
        $startPreviousMonth = now()->subMonths(1)->startOfMonth();
        $endOfPreviousMonth = now()->subMonths(1)->endOfMonth();

        $newStudent = factory(User::class)->create();

        $trialPlan = factory(Plan::class)->create(['id' => Plan::TRIAL]);
        // assign a trial plan to the user
        factory(PlanUser::class)->create([
            'user_id'        => $newStudent->id,
            'plan_id'        => $trialPlan->id,
            'start_date'     => $startPreviousMonth->copy()->subMonth(),
            'finish_date'    => $startPreviousMonth->addDays(7),
            'plan_status_id' => PlanStatus::FINISHED,
        ]);

        $nonTrialPlan = factory(Plan::class)->create();

        // assign the first non trial plan to the user
        factory(PlanUser::class)->create([
            'user_id'        => $newStudent->id,
            'plan_id'        => $nonTrialPlan->id,
            'start_date'     => $startPreviousMonth,
            'finish_date'    => $endOfPreviousMonth,
            'plan_status_id' => PlanStatus::FINISHED,
        ]);

        $result = User::newStudentsInDateRange($startPreviousMonth, $endOfPreviousMonth)->get();
        
        $this->assertCount(1, $result);
        $this->assertTrue($result->contains($newStudent));
    }

    /** @test */
    public function it_scope_new_students_in_date_range_not_consider_canceled_plans_as_a_valid_previous_plan()
    {
        $startMonth = now()->startOfMonth();
        $endMonth = now()->endOfMonth();

        $newStudent = factory(User::class)->create();

        $plan = factory(Plan::class)->create();
        // assign a trial plan to the user
        $previousCanceledPlan = User::withoutEvents(function () use ($newStudent, $plan, $startMonth) {
            return factory(PlanUser::class)->create([
                'user_id'        => $newStudent->id,
                'plan_id'        => $plan->id,
                'start_date'     => $startMonth->copy()->subMonth(),
                'finish_date'    => $startMonth->copy()->subMonth()->addDays(7),
                'plan_status_id' => PlanStatus::CANCELED,
            ]);
        });

        $this->assertDatabaseHas('plan_user', [
            'id' => $previousCanceledPlan->id,
            'plan_status_id' => PlanStatus::CANCELED,
        ]);

        $activePlan = factory(Plan::class)->create();
        // assign an active plan to the user
        factory(PlanUser::class)->create([
            'user_id'        => $newStudent->id,
            'plan_id'        => $activePlan->id,
            'start_date'     => $startMonth,
            'finish_date'    => $endMonth,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $result = User::newStudentsInDateRange($startMonth, $endMonth)->get();

        $this->assertCount(1, $result);
        $this->assertTrue($result->contains($newStudent));
    }

    /** @test */
    public function it_scope_new_students_in_date_range_not_consider_canceled_plans()
    {
        $startMonth = now()->startOfMonth();
        $endMonth = now()->endOfMonth();

        $newStudent = factory(User::class)->create();

        $canceledPlan = User::withoutEvents(function () use ($newStudent, $startMonth) {
            return factory(PlanUser::class)->create([
                'user_id'        => $newStudent->id,
                'start_date'     => $startMonth->copy()->subMonth(),
                'finish_date'    => $startMonth->copy()->subMonth()->addDays(7),
                'plan_status_id' => PlanStatus::CANCELED,
            ]);
        });

        $result = User::newStudentsInDateRange($startMonth, $endMonth)->get();

        $this->assertCount(0, $result);
    }

    /** @test */
    public function it_can_scope_new_students_in_date_range()
    {
        $startPreviousMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();

        $newStudent = factory(User::class)->create();
        $planNewStudent = factory(Plan::class)->create();
        PlanUser::create([
            'user_id' => $newStudent->id,
            'plan_id' => $planNewStudent->id,
            'start_date' => $startPreviousMonth,
            'finish_date' => $endOfPreviousMonth,
        ]);

        $notNewStudent = factory(User::class)->create();
        $planNotNewStudent = factory(Plan::class)->create();
        PlanUser::create([
            'user_id' => $notNewStudent->id,
            'plan_id' => $planNotNewStudent->id,
            'start_date' => $endOfPreviousMonth->addDay(),
            'finish_date' => $endOfPreviousMonth->addMonth(),
        ]);

        $result = User::newStudentsInDateRange($startPreviousMonth, $endOfPreviousMonth)->get();

        $this->assertCount(1, $result);
        $this->assertTrue($result->contains($newStudent));
        $this->assertFalse($result->contains($notNewStudent));
    }

    /** @test */
    public function it_scope_new_students_in_date_range_not_consider_users_which_have_a_plan_before_the_start_range()
    {
        $startPreviousMonth = now()->subMonths(1)->startOfMonth();
        $endOfPreviousMonth = now()->subMonths(1)->endOfMonth();

        $regularStudent = factory(User::class)->create();

        $previousPlan = factory(Plan::class)->create(['id' => 101]);
        $plan = factory(Plan::class)->create();
        // assign an active plan to the user
        $planUser = factory(PlanUser::class)->create([
            'user_id'        => $regularStudent->id,
            'plan_id'        => $previousPlan->id,
            'start_date'     => $startPreviousMonth,
            'finish_date'    => $endOfPreviousMonth,
            'plan_status_id' => PlanStatus::FINISHED,
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $planUser->id,
            'plan_id'        => 101,
            'start_date'     => $startPreviousMonth,
            'finish_date'    => $endOfPreviousMonth,
            'plan_status_id' => PlanStatus::FINISHED,
        ]);
        
        $newPlan = factory(PlanUser::class)->create([
            'user_id'        => $regularStudent->id,
            'plan_id'        => $plan->id,
            'start_date'     => now()->startOfMonth(),
            'finish_date'    => now()->endOfMonth(),
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        $result = User::newStudentsInDateRange(
            now()->startOfMonth(),
            now()->endOfMonth()
        )->get();

        $this->assertCount(0, $result);
    }

    /** @test */
    public function it_can_scope_users_with_turnaround_in_date_range()
    {
        // Arrange
        $startPreviousMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();

        $turnaroundUser = factory(User::class)->create();
        $planTurnaround = factory(Plan::class)->create(['plan_status_id' => PlanStatus::ACTIVE]);
        PlanUser::create([
            'user_id' => $turnaroundUser->id,
            'plan_id' => $planTurnaround->id,
            'start_date' => $startPreviousMonth,
            'finish_date' => $endOfPreviousMonth,
        ]);

        $inactiveUser = factory(User::class)->create();
        $planInactive = factory(Plan::class)->create(['plan_status_id' => PlanStatus::FINISHED]);
        PlanUser::create([
            'user_id' => $inactiveUser->id,
            'plan_id' => $planInactive->id,
            'start_date' => $startPreviousMonth,
            'finish_date' => $endOfPreviousMonth,
        ]);

        // Act
        $result = User::turnaroundInDateRange($startPreviousMonth, $endOfPreviousMonth)->get();

        // Assert
        $this->assertTrue($result->contains($turnaroundUser));
        $this->assertFalse($result->contains($inactiveUser));
    }
}
