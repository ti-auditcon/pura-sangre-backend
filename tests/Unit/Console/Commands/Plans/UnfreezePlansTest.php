<?php

namespace Tests\Unit\Console\Commands\Plans;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Plans\PostponePlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UnfreezePlansTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purasangre:plans:unfreeze';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unfreeze all the plans who has today the unfreeze date';

    /**
     *  Change the actual time to another one
     *
     *  @param   Carbon|string
     *
     *  @return  void
     */
    public function travelTo($date) :void
    {
        Carbon::setTestNow($date);
    }

    /** 
     *  Si el próximo plan comienza antes de que termine el actual,
     *  las fechas de los siguientes planes se deben mover hacia adelante
     * 
     *  @test
     */
    public function it_move_next_plans_dates_forward_if_the_closest_next_plan_start_before_the_current_active_plan_ends()
    {
        $this->withoutExceptionHandling();
        $planToUnfreeze = factory(PlanUser::class)->create([
            'plan_status_id' => PlanStatus::FREEZED,
            'start_date'     => today()->startOfMonth(),
            'finish_date'    => today()->endOfMonth(),
            'user_id'        => $this->admin->id     
        ]);

        $nextClosestPlan = factory(PlanUser::class)->create([
            'plan_status_id' => PlanStatus::PRE_PURCHASE,
            'start_date'     => today()->addMonth()->startOfMonth(),
            'finish_date'    => today()->addMonth()->endOfMonth(),
            'user_id'        => $this->admin->id     
        ]);

        // travelTo day 25 of this month
        $this->travelTo(Carbon::createFromDate(today()->year, today()->month, 25));

        $freezeRegister = factory(PostponePlan::class)->create([
            'plan_user_id' => $planToUnfreeze->id,
            'days'         => 10,
            'finish_date'  => today()->subDay()
        ]);

        $this->artisan($this->signature)->expectsOutput(0);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $planToUnfreeze->id,
            'plan_status_id' => PlanStatus::ACTIVE,
            'finish_date'    => today()->addDays(10 -1)->format('Y-m-d H:i:s'), // we substract 1 day from the today()
        ]);

        //  we fetch the plan again to get the new data from database
        $nowActivePlan = PlanUser::find($planToUnfreeze->id);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $nextClosestPlan->id,
            'plan_status_id' => PlanStatus::PRE_PURCHASE,
            'start_date'     => $nowActivePlan->finish_date->addDay()->format('Y-m-d H:i:s'),
        ]);
    }

    /** 
     *  Si el próximo plan comienza después de que termine el actual,
     *  las fechas de los siguientes planes se deben mover hacia atrás
     * 
     *  @test
     */
    public function it_move_next_plans_dates_backward_if_the_closest_next_plan_start_after_the_current_active_plan_ends()
    {
        $planToUnfreeze = factory(PlanUser::class)->create([
            'plan_status_id' => PlanStatus::FREEZED,
            'start_date'     => today()->startOfMonth(),
            'finish_date'    => today()->endOfMonth(),
            'user_id'        => $this->admin->id     
        ]);

        $nextClosestPlan = factory(PlanUser::class)->create([
            'plan_status_id' => PlanStatus::PRE_PURCHASE,
            'start_date'     => today()->addMonth()->startOfMonth(),
            'finish_date'    => today()->addMonth()->endOfMonth(),
            'user_id'        => $this->admin->id     
        ]);

        // travelTo day 20 of this month
        $this->travelTo(Carbon::createFromDate(today()->year, today()->month, 20));

        factory(PostponePlan::class)->create([
            'plan_user_id' => $planToUnfreeze->id,
            'days'         => 5,
            'finish_date'  => today()->subDay()
        ]);

        $this->artisan($this->signature)->expectsOutput(0);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $planToUnfreeze->id,
            'plan_status_id' => PlanStatus::ACTIVE,
            'finish_date'    => today()->addDays(5 - 1)->format('Y-m-d H:i:s'), // we substract 1 day for the today()
        ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $nextClosestPlan->id,
            'plan_status_id' => PlanStatus::PRE_PURCHASE,
            'start_date'     => $nextClosestPlan->start_date->addDays( -4 )->format('Y-m-d H:i:s'),
            'finish_date'    => $nextClosestPlan->finish_date->addDays( -4 )->format('Y-m-d H:i:s'),
        ]);
    }
}
