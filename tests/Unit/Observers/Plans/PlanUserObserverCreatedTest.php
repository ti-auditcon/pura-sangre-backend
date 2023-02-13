<?php

namespace Tests\Unit\Observers\Plans;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Clases\Reservation;
use App\Observers\Plans\PlanUserObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlanUserObserverCreatedTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    /**
     * At plan creation, it checks if there is some reservations that should be assigned to the plan.
     * Ex. If the plan start January 1st and ends January 10,
     * all the reservations that are between those dates,
     * should be assigned to the plan.
     * 
     * @test
     */
    public function it_assigns_reservations_to_plan()
    {
        $planUser = PlanUser::withoutEvents(function () {
            return factory(PlanUser::class)->create([
                'start_date' => Carbon::now(),
                'finish_date' => Carbon::now()->addDays(10),
            ]);
        });

        $clase = factory(Clase::class)->create([
            'date' => $planUser->start_date->addDay(),
        ]);

        $reservation = Reservation::withoutEvents(function() use ($clase, $planUser) {
            return factory(Reservation::class)->create([
                'clase_id' => $clase->id,
                'user_id' => $planUser->user_id,
                'plan_user_id' => null
            ]);
        });
                
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'plan_user_id' => null,
        ]);
            
        $planUserObserver = new PlanUserObserver();
        $planUserObserver->created($planUser);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'plan_user_id' => $planUser->id,
        ]);
    }

    // it dosn't assign reservations that are out of the plan dates
    /** @test */
    public function it_doesnt_assign_reservations_out_of_plan_dates()
    {
        $planUser = PlanUser::withoutEvents(function () {
            return factory(PlanUser::class)->create([
                'start_date' => Carbon::now(),
                'finish_date' => Carbon::now()->addDays(10),
            ]);
        });

        $clase = factory(Clase::class)->create([
            'date' => $planUser->start_date->subDay(),
        ]);

        $reservation = Reservation::withoutEvents(function() use ($clase, $planUser) {
        return factory(Reservation::class)->create([
                'clase_id' => $clase->id,
                'user_id' => $planUser->user_id,
                'plan_user_id' => null
            ]);
        });
                
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'plan_user_id' => null,
        ]);
            
        $planUserObserver = new PlanUserObserver();
        $planUserObserver->created($planUser);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'plan_user_id' => null,
        ]);
    }

    /**
     * it doenst assign reservations which their classes are the same as the start date of the plan,
     *  but the time is out of the plan
     * 
     *  @test 
     */
    public function it_same_start_date_but_out_of_the_plan_reservations_are_not_assigned()
    {
        $planUser = PlanUser::withoutEvents(function () {
            return factory(PlanUser::class)->create([
                'start_date' => now(),
                'finish_date' => now()->addDays(10),
            ]);
        });

        $clase = factory(Clase::class)->create([
            'date' => now()->startOfHour()->subHour(),
            'start_at' => now()->startOfHour()->subHour(),
        ]);

        $reservation = Reservation::withoutEvents(function() use ($clase, $planUser) {
            return factory(Reservation::class)->create([
                'clase_id' => $clase->id,
                'user_id' => $planUser->user_id,
                'plan_user_id' => null
            ]);
        });
                
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'plan_user_id' => null,
        ]);
            
        $planUserObserver = new PlanUserObserver();
        $planUserObserver->created($planUser);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'plan_user_id' => null,
        ]);
    } 
}
