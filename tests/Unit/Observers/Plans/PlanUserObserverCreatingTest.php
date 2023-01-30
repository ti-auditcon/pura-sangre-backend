<?php

namespace Tests\Unit\Observers\Plans;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlanUserObserverCreatingTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

        // public function creating(PlanUser $planUser)
        // if the creating plan start after today, the status is changed to prepurchased
        // should I encapsulate the variables for
        // if_the_creating_plan_starts_today_or_before_also_finish_today_or_after_and_the_user_has_not_and_active_plan_then_the_status_is_changed_to_active
        // if the creating plan start after today, the status is changed to prepurchased
        // if the creating plan start after today, the status is changed to prepurchased
}
