<?php

namespace Tests\Feature\Plans;

use Carbon\Carbon;
use Tests\NfitTestCase;
use App\Models\Plans\Plan;
use App\Models\Users\Role;
use App\Models\Users\User;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Users\StatusUser;
use App\Models\Clases\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class PlanUserTest extends NfitTestCase
{
    use RefreshDatabase;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $admin;

    /**
     *  Allows implementation in a test.
     */
    protected function duringSetUp()
    {
        $this->seed();

        $this->admin = $this->createModel(\App\Models\Users\User::class, [], 1);

        $this->admin->roles()->attach(Role::ADMIN);
    }

    /** @test */
    public function admin_can_see_plan_user_create_view()
    {
        // $this->seed(\StatusUsersTableSeeder::class);
        // $this->seed(\ClaseTypesTableSeeder::class);
        // $this->seed(\PlansTableSeeder::class);

        $admin = factory(\App\Models\Users\User::class)->create();
        // $admin->roles()->attach(1);

        $client_user = factory(\App\Models\Users\User::class)->create();

        $this->actingAs($admin)->get("/users/{$client_user->id}/plans/create")
                               ->assertSee('Planes*');
    }

    /** @test */
    public function admin_can_create_plan_user_to_a_client()
    {
        $client_user = factory(User::class)->create();

        $all_plans = Plan::all();

        $period = today();

        foreach ($all_plans as $plan) {
            $plan_user = [
                'plan_id' => $plan->id,
                "fecha_inicio" => $period,
                "fecha_termino" => $plan->custom ? $period : null,
                "counter" => $plan->custom ? 1 : $plan->class_numbers,
                "payment_type_id" => "1",
                "date" => "01-11-2019",
                "amount" => $plan->amount
            ];

            $response = $this->actingAs($this->admin)->post('/users/' . $client_user->id .  '/plans', $plan_user);

            $response->assertRedirect("/users/{$client_user->id}");

            if ($plan->custom) {
                $finish_date = $period->format('Y-m-d H:i:s');
                $counter = 1;
            } elseif ($plan->id === 1) {
                $finish_date = $period->copy()
                                      ->addWeek()
                                      ->format('Y-m-d H:i:s');
                                      
                $counter = $plan->class_numbers;
            } else {
                $finish_date = $period->copy()
                                      ->addMonths(optional($plan->plan_period)->period_number)
                                      ->subDay()
                                      ->format('Y-m-d H:i:s');

                $counter = $plan->class_numbers * $plan->plan_period->period_number * $plan->daily_clases;
            }

            $this->assertDatabaseHas('plan_user', [
                'plan_id' => $plan->id,
                'user_id' => $client_user->id,
                "start_date" => $period->format('Y-m-d H:i:s'),
                "finish_date" => $finish_date,
                'counter' => $counter
            ]);

            $this->get("/users/{$client_user->id}")->assertOk();

            // $period->addMonths($plan->plan_period->period_number ?? 1);
        }
    }

    /** @test */
    public function status_user_is_updated_after_user_plan_created()
    {
        $client_user = factory(\App\Models\Users\User::class)->create();

        $plan_user = [
            'plan_id' => 3,
            "fecha_inicio" => today(),
            "payment_type_id" => "1",
            "date" => "01-11-2019",
            "amount" => '45000'
        ];

        $response = $this->actingAs($this->admin)
                         ->post("/users/{$client_user->id}/plans", $plan_user);

        $this->assertDatabaseHas('plan_user', [
            'plan_id' => $plan_user['plan_id'],
            'user_id' => $client_user->id,
            "start_date" => Carbon::parse($plan_user['fecha_inicio'])->format('Y-m-d H:i:s'),
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $client_user->id,
            'status_user_id' => StatusUser::ACTIVE
        ]);
    }

    /** @test */
    public function reservations_are_organized_after_user_plan_created()
    {
        $clase_de_hoy = factory(\App\Models\Clases\Clase::class)->create([
            'date' => today()->format('Y-m-d')
        ]);
        $user = factory(\App\Models\Users\User::class)->create();

        $reservation = factory(\App\Models\Clases\Reservation::class)->create([
            'clase_id' => $clase_de_hoy->id,
            'user_id' => $user->id
        ]);
        $reservation = $reservation->toArray();

        $response = $this->actingAs($this->admin)->post('/reservation/', $reservation);

        $this->assertDatabaseHas('clases', [
            'id' => $clase_de_hoy->id,
        ]);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'clase_id' => $clase_de_hoy->id,
            'plan_user_id' => null,
        ]);

        $plan_user = [
            'plan_id' => 3,
            "fecha_inicio" => today(),
            "payment_type_id" => "1",
            "date" => "01-11-2019",
            "amount" => '45000'
        ];

        $response = $this->actingAs($this->admin)->post('/users/' . $user->id .  '/plans', $plan_user);

        $this->assertDatabaseHas('reservations', [
            'plan_user_id' => 1,
            'user_id' => $user->id,
            'clase_id' => $clase_de_hoy->id,
        ]);
    }

    /** @test */
    public function a_bill_is_created_after_plan_user_created()
    {
        $user = $this->createModel(\App\Models\Users\User::class, [], 1);

        $this->actingAs($this->admin)->get('/')->assertOk();

        $plan_user = [
            'plan_id' => 3,
            "fecha_inicio" => today()->format('d-m-Y'),
            "payment_type_id" => 1,
            "date" => today()->addDay()->format('d-m-Y'),
            "amount" => 45000
        ];

        $this->actingAs($this->admin)->post("/users/{$user->id}/plans", $plan_user)
                                     ->assertRedirect();

        $this->assertDatabaseHas('plan_user', [
            'plan_id' => (string) $plan_user['plan_id'],
            'user_id' => (string) $user->id,
            'start_date' => today()->format('Y-m-d H:i:s'),
        ]);

        $this->assertDatabaseHas('bills', [
            'date' => Carbon::parse($plan_user['date'])->format('Y-m-d H:i:s'),
            'start_date' => today()->format('Y-m-d H:i:s'),
            'finish_date' => today()->addMonth()->subDay()->format('Y-m-d H:i:s'),
            'amount' => $plan_user['amount'],
        ]);
    }

    /** @test */
    public function when_creating_plan_dates_can_not_overlap_with_created_plan()
    {
        $user = factory(\App\Models\Users\User::class)->create();

        factory(\App\Models\Plans\PlanUser::class)->create([
            'user_id' => $user->id,
            'start_date' => today(),
            'finish_date' => today()->addMonth()->subDay()
        ]);

        $plan_user = factory(\App\Models\Plans\PlanUser::class)->create([
            'user_id' => $user->id,
            'start_date' => today()->addMonth(),
            'finish_date' => today()->addMonths(2)->subDay()
        ]);

        $response = $this->actingAs($this->admin)->put('/users/' . $user->id .  '/plans/' .  $plan_user->id, [
            'fecha_inicio' => today()->addMonth()->subDay()
        ]);

        $response->assertSessionHas('error-tap');
    }
}
    // $this->withoutExceptionHandling();

