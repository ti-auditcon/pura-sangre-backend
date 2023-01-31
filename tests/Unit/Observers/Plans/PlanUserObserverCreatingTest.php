<?php

namespace Tests\Unit\Observers\Plans;

use Tests\TestCase;
use App\Models\Plans\PlanStatus;
use App\Observers\Plans\PlanUserObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlanUserObserverCreatingTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

        // public function creating(PlanUser $planUser)
        // if the creating plan start after today, the status is changed to prepurchased
        /** @test */
        public function it_status_gets_pre_purchased_if_plan_starts_after_now()
        {
            $planUser = $this->fakeActivePlanUser([
                'start_date' => now()->addMinute(),
                'finish_date' => now()->addDays(31),
                'plan_status_id' => 99
            ]);

            $planUserObserver = new PlanUserObserver();

            $planUserObserver->creating($planUser);

            $this->assertEquals(PlanStatus::PRE_PURCHASE, $planUser->plan_status_id, 'The plan status is not PRE_PURCHASE');
        }
        
        /** @test */
        public function it_status_gets_active_if_plan_starts_now()
        {
            $planUser = $this->fakeActivePlanUser([
                'start_date' => now(),
                'finish_date' => now()->addDays(31),
                'plan_status_id' => 99
            ]);

            $planUserObserver = new PlanUserObserver();

            $planUserObserver->creating($planUser);

            $this->assertEquals($planUser->plan_status_id, PlanStatus::ACTIVE);
        }

        /** @test */
        public function it_status_gets_finished_if_plan_ends_before_now()
        {
            $planUser = $this->fakeActivePlanUser([
                'start_date' => now()->subDays(31),
                'finish_date' => now()->subMinute(),
                'plan_status_id' => 99
            ]);

            $planUserObserver = new PlanUserObserver();

            $planUserObserver->creating($planUser);

            $this->assertEquals($planUser->plan_status_id, PlanStatus::FINISHED);
        }
}
