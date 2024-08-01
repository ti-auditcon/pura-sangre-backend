<?php

namespace Tests\Unit\Users;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserScopeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Seed the database or create necessary models here
        // For example, creating some users and plans
    }
 
    /** @test */
    public function it_can_scope_users_active_in_date_range()
    {
        // Arrange
        $startPreviousMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();

        $userInRange = factory(User::class)->create();
        $planInRange = factory(Plan::class)->create();
        PlanUser::create([
            'user_id' => $userInRange->id,
            'plan_id' => $planInRange->id,
            'start_date' => $startPreviousMonth,
            'finish_date' => $endOfPreviousMonth,
        ]);

        $userOutOfRange = factory(User::class)->create();
        $planOutOfRange = factory(Plan::class)->create();
        PlanUser::create([
            'user_id' => $userOutOfRange->id,
            'plan_id' => $planOutOfRange->id,
            'start_date' => $endOfPreviousMonth->addDay(),
            'finish_date' => $endOfPreviousMonth->addMonth(),
        ]);

        // Act
        $result = User::activeInDateRange($startPreviousMonth, $endOfPreviousMonth)->get();

        // Assert
        $this->assertTrue($result->contains($userInRange));
        $this->assertFalse($result->contains($userOutOfRange));
    }

    /** @test */
    public function it_can_scope_users_finished_in_date_range()
    {
        // Arrange
        $startPreviousMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();

        $userInRange = factory(User::class)->create();
        $planInRange = factory(Plan::class)->create();
        PlanUser::create([
            'user_id'     => $userInRange->id,
            'plan_id'     => $planInRange->id,
            'start_date'  => $startPreviousMonth->subMonth(),
            'finish_date' => $endOfPreviousMonth,
        ]);

        $userOutOfRange = factory(User::class)->create();
        $planOutOfRange = factory(Plan::class)->create();
        PlanUser::create([
            'user_id'     => $userOutOfRange->id,
            'plan_id'     => $planOutOfRange->id,
            'start_date'  => $endOfPreviousMonth->addDay(),
            'finish_date' => $endOfPreviousMonth->addMonth(),
        ]);

        // Act
        $result = User::finishedInDateRange($startPreviousMonth, $endOfPreviousMonth)->get();

        // Assert
        $this->assertTrue($result->contains($userInRange));
        $this->assertFalse($result->contains($userOutOfRange));
    }

    /** @test */
    public function it_can_scope_users_as_dropouts()
    {
        // Arrange
        $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();

        $dropoutUser = factory(User::class)->create();
        $planDropout = factory(Plan::class)->create();
        PlanUser::create([
            'user_id' => $dropoutUser->id,
            'plan_id' => $planDropout->id,
            'start_date' => Carbon::now()->subMonths(2),
            'finish_date' => $endOfPreviousMonth,
        ]);

        $activeUser = factory(User::class)->create();
        $planActive = factory(Plan::class)->create();
        PlanUser::create([
            'user_id' => $activeUser->id,
            'plan_id' => $planActive->id,
            'start_date' => $endOfPreviousMonth->addDay(),
            'finish_date' => $endOfPreviousMonth->addMonth(),
        ]);

        // Act
        $result = User::dropouts($endOfPreviousMonth)->get();

        // Assert
        $this->assertTrue($result->contains($dropoutUser));
        $this->assertFalse($result->contains($activeUser));
    }

    /** @test */
    public function it_can_scope_new_students_in_date_range()
    {
        // Arrange
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

        // Act
        $result = User::newStudentsInDateRange($startPreviousMonth, $endOfPreviousMonth)->get();

        // Assert
        $this->assertTrue($result->contains($newStudent));
        $this->assertFalse($result->contains($notNewStudent));
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
