<?php

namespace Tests\Feature\Plans;

use App\Models\Plans\Plan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PlanUserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function admin_can_see_plan_user_create_view()
    {
        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);

        $admin = factory(\App\Models\Users\User::class)->create();
        $admin->roles()->attach(1);

        $client_user = factory(\App\Models\Users\User::class)->create();

        $response = $this->actingAs($admin)->get('/users/' . $client_user->id .  '/plans/create');

        $response->assertSee('Planes*');
    }

    /** @test */
    public function admin_can_create_plan_user_to_a_client()
    {
        // $this->withoutExceptionHandling();

        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\PlanPeriodsTableSeeder::class);
        $this->seed(\PlanStatusTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);

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
                "counter" => $plan->class_numbers ?? 1,
                "payment_type_id" => "1",
                "date" => "01-11-2019",
                "amount" => $plan->amount
            ];

            $response = $this->actingAs($admin)->post('/users/' . $client_user->id .  '/plans', $plan_user);

            $response->assertRedirect('/users/' . $client_user->id);

            $this->assertDatabaseHas('plan_user', [
                'plan_id' => $plan->id,
                'user_id' => $client_user->id,
                "start_date" => $period->format('Y-m-d H:i:s'),
                'counter' => $plan->class_numbers ?? 1,
            ]);

            $this->get('/users/' . $client_user->id)->assertOk();

            $period->addMonths($plan->plan_period->period_number ?? 1);
        }
    }
}
    // $this->withoutExceptionHandling();

