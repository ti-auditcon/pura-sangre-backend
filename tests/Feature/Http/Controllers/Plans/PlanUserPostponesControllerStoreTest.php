<?php

namespace Tests\Feature\Http\Controllers\Plans;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Clases\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlanUserPostponesControllerStoreTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;
    
    protected $planUser;

    public const FROZEN_PLAN_MESSAGE = 'El plan se ha congelado correctamente.';

    public function setUp(): void
    {
        parent::setUp();

        $this->planUser = PlanUser::withoutEvents(function () {
            return factory('App\Models\Plans\PlanUser')->create([
                'plan_status_id' => PlanStatus::ACTIVE,
                'start_date' => today()->subDays(5),
                'finish_date' => today()->addDays(5),
            ]);
        });
    }

    public function travelTo($date)
    {
        Carbon::setTestNow(Carbon::parse($date));
    }

    /** @test */
    public function it_stores_a_new_postpone_plan_record_correctly()
    {
        $planUser = PlanUser::withoutEvents(function () {
            return factory('App\Models\Plans\PlanUser')->create([
                'plan_status_id' => PlanStatus::ACTIVE,
                'finish_date' => '2000-01-10',
            ]);
        }); 
        
        $this->travelTo('2000-01-05 00:00:00');

        $response = $this->actingAs($this->admin)
            ->post("/plan-user/{$planUser->id}/postpones", [
                'start_freeze_date' => '2000-01-05',
                'end_freeze_date' => '2000-01-10',
            ]);

        $response->assertSessionHas('success', self::FROZEN_PLAN_MESSAGE);

        $this->assertDatabaseHas('freeze_plans', [
            'plan_user_id' => $planUser->id,
            'start_date' => '2000-01-05',
            'finish_date' => '2000-01-10',
            'days' => 6,
        ]);
    }

    /** @test */
    public function it_cannot_postpone_a_plan_user_if_it_has_already_one_postponed()
    {
        $this->withoutExceptionHandling();

        $previousPostponePlan = factory('App\Models\Plans\PostponePlan')->create([
            'plan_user_id' => $this->planUser->id,
            'start_date' => today()->subDays(5),
            'finish_date' => today()->addDays(5),
            'days' => 11,
        ]);

        $this->assertDatabaseHas('freeze_plans', [
            'plan_user_id' => $this->planUser->id,
            'start_date' => today()->subDays(5)->format('Y-m-d'),
            'finish_date' => today()->addDays(5)->format('Y-m-d'),
            'days' => 11,
        ]);

        $this->actingAs($this->admin)
            ->post("/plan-user/{$this->planUser->id}/postpones", [
                'start_freeze_date' => today()->subDays(5)->format('Y-m-d'),
                'end_freeze_date'   => today()->addDays(5)->format('Y-m-d'),
            ])
            ->assertSessionHas('error', 'El plan ya se encuentra congelado.');

        $this->assertDatabaseHas('freeze_plans', [
            'id' => $previousPostponePlan->id,
            'plan_user_id' => $this->planUser->id,
            'revoked' => false,
        ]);
    }

    /** @test */
    public function it_updates_plan_user_status_to_frozen_if_freeze_start_date_is_today()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->admin)
            ->post("/plan-user/{$this->planUser->id}/postpones", [
                'start_freeze_date' => today()->format('Y-m-d'),
                'end_freeze_date'   => today()->addDays(5)->format('Y-m-d'),
            ]);

        $this->assertDatabaseHas('plan_user', [
            'id' => $this->planUser->id,
            'plan_status_id' => PlanStatus::FROZEN,
        ]);
    }

    /** @test */
    public function it_deletes_all_the_future_reservations_for_this_plan_user()
    {
        $previousReservation = Reservation::withoutEvents(function () {
            return factory(Reservation::class)->create([
                'plan_user_id' => $this->planUser->id,
                'clase_id' => factory(Clase::class)->create([
                    'date' => today()->subDay(),
            ])->id,
        ]);

        });
        $futureReservation = factory(Reservation::class)->create([
            'plan_user_id' => $this->planUser->id,
            'clase_id' => factory(Clase::class)->create([
                'date' => today()->addDay(),
            ])->id,
        ]);

        $this->actingAs($this->admin)
            ->post("/plan-user/{$this->planUser->id}/postpones", [
                'start_freeze_date' => today()->format('Y-m-d'),
                'end_freeze_date'   => today()->addDays(5)->format('Y-m-d'),
            ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $previousReservation->id,
        ]);

        $this->assertDatabaseMissing('reservations', [
            'id' => $futureReservation->id,
        ]);
    }

}
