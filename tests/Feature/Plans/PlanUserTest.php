<?php

namespace Tests\Feature\Plans;

use Tests\TestCase;
use App\Models\Clases\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlanUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_see_plan_user_create_view()
    {
        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);

        $admin = factory(\App\Models\Users\User::class)->create();
        // $admin->roles()->attach(1);

        $client_user = factory(\App\Models\Users\User::class)->create();

        $response = $this->actingAs($admin)->get('/users/' . $client_user->id .  '/plans/create');

        $response->assertSee('Planes*');
    }

    /** @test */
    public function admin_can_create_plan_user_to_a_client()
    {
        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\PlanPeriodsTableSeeder::class);
        $this->seed(\PlanStatusTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);
        $this->seed(\RolesTableSeeder::class);

        $admin = factory(\App\Models\Users\User::class)->create();
        $admin->roles()->attach(1);

        $client_user = factory(\App\Models\Users\User::class)->create();

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

            $response = $this->actingAs($admin)->post('/users/' . $client_user->id .  '/plans', $plan_user);

            $response->assertRedirect('/users/' . $client_user->id);

            if ($plan->custom) {
                $finish_date = $period->format('Y-m-d H:i:s');
                $counter = 1;
            } elseif ($plan->id == 1) {
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

            $this->get('/users/' . $client_user->id)->assertOk();

            $period->addMonths($plan->plan_period->period_number ?? 1);
        }
    }

    /** @test */
    public function status_user_is_updated_after_user_plan_created()
    {
        $this->withoutExceptionHandling();

        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\PlanPeriodsTableSeeder::class);
        $this->seed(\PlanStatusTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);
        $this->seed(\RolesTableSeeder::class);

        $admin = factory(\App\Models\Users\User::class)->create();
        $admin->roles()->attach(1);

        $client_user = factory(\App\Models\Users\User::class)->create();

        $plan_user = [
            'plan_id' => 3,
            "fecha_inicio" => today(),
            "payment_type_id" => "1",
            "date" => "01-11-2019",
            "amount" => '45000'
        ];

        $response = $this->actingAs($admin)->post('/users/' . $client_user->id .  '/plans', $plan_user);

        $this->assertDatabaseHas('plan_user', [
            'plan_id' => 3,
            'user_id' => $client_user->id,
            "start_date" => today()->format('Y-m-d H:i:s'),
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $client_user->id,
            'status_user_id' => 1
        ]);
    }

    /** @test */
    public function reservations_are_organized_after_user_plan_created()
    {
        $this->withoutExceptionHandling();

        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\BlockTableSeeder::class);
        $this->seed(\PlanPeriodsTableSeeder::class);
        $this->seed(\PlanStatusTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);
        $this->seed(\RolesTableSeeder::class);
        $this->seed(\UsersTableSeeder::class);

        $clase_de_hoy = factory(\App\Models\Clases\Clase::class)->create([
            'date' => today()->format('Y-m-d')
        ]);

        $admin = factory(\App\Models\Users\User::class)->create();
        $admin->roles()->attach(1);
        $user = factory(\App\Models\Users\User::class)->create();

        $reservation = factory(Reservation::class)->create([
            'clase_id' => $clase_de_hoy->id,
            'user_id' => $user->id
        ]);
        $reservation = $reservation->toArray();
        
        $response = $this->actingAs($admin)->post('/reservation/', $reservation);
        
        $this->assertDatabaseHas('clases', [
            'id' => $clase_de_hoy->id,
        ]);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'clase_id' => $clase_de_hoy->id,
            'plan_user_id' => null,
        ]);

        $plan_user = factory(\App\Models\Plans\PlanUser::class)->create([
            'user_id' => $user->id,
            'start_date' => today() 
        ]);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'clase_id' => $clase_de_hoy->id,
            'plan_user_id' => $plan_user->id,
        ]);
    }
}
    // $this->withoutExceptionHandling();

