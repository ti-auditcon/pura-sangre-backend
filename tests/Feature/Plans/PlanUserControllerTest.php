<?php

namespace Tests\Feature\Plans;

use Tests\TestCase;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Clases\ClaseType;
use App\Models\Plans\PlanStatus;
use App\Models\Bills\PaymentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlanUserControllerTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    /**
     * Before the tests are executed
     *
     * @return  void
     */
    public function setUp(): void
    {
        parent::setUp();

        ClaseType::create([
            'clase_type' => 'CrossFit',
            'clase_color' => 'CrossFit',
            'icon' => 'crossfit.svg',
            'icon_white' => 'crossfit.svg',
            'active' => true,
        ]);
    }

    //  deberia separar en dos tests el hecho que el admin no puede agregar un plan que esta descontinuado?

    /** @test */
    public function it_admin_can_assign_any_plan_to_a_client()
    {
        $studentUser = factory(User::class)->create();

        factory(Plan::class, 3)->create();

        foreach (Plan::all() as $key => $plan) {
            $plan_user = factory(PlanUser::class)->make([
                'plan_id'      => $plan->id,
                'start_date'   => now()->format('Y-m-d'),
                'user_id'      => $studentUser->id,
                'finish_date'  => $plan->plan_period_id ? now()->copy()->addMonths($plan->plan_period_id)->format('Y-m-d') : now()->addDays(7)->format('Y-m-d'),
                'counter'      => $plan->counter ?? 0,
                'observations' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi doloremque ullam esse at animi excepturi',
            ]);

            $data = array_merge($plan_user->only('plan_id', 'start_date', 'finish_date', 'counter', 'observations', 'user_id'), [
                'class_numbers' => $plan->class_numbers,
                'clases_by_day' => $plan->daily_clases
            ]);
            
            $response = $this->actingAs($this->admin)->post("/users/{$studentUser->id}/plans", $data);
            
            $this->assertDatabaseHas('plan_user', [
                'plan_id'        => $plan->id,
                'user_id'        => $studentUser->id,
                'counter'        => $plan_user->counter,
                'start_date'     => now()->startOfMinute()->format('Y-m-d H:i:s'),
                'observations'   => $plan_user->observations,
                'plan_status_id' => PlanStatus::ACTIVE
            ]);

            /** Cancel plan to assign other in the next iteration */
            $studentUser->actual_plan()->update(['plan_status_id' => PlanStatus::CANCELED]);

            $this->assertDatabaseHas('plan_user', [
                'plan_id'        => $plan->id,
                'user_id'        => $studentUser->id,
                'counter'        => $plan_user->counter,
                'start_date'     => now()->startOfMinute()->format('Y-m-d H:i:s'),
                'observations'   => $plan_user->observations,
                'plan_status_id' => PlanStatus::CANCELED
            ]);
        }
    }

    /** @test */
    public function when_admin_choose_to_create_a_bill_a_plan_user_flow_is_associated_to_plan_user()
    {
        $studentUser = factory(User::class)->create();
        factory(Plan::class)->create();

        $plan = factory(Plan::class)->create();
        $plan_user = factory(PlanUser::class)->make([
            'user_id' => $studentUser->id, 'plan_id' => $plan->id,
            'start_date'   => now()->format('Y-m-d'),
        ]);

        $plan_user_array = $plan_user->only(
            'start_date', 'finish_date', 'counter',
            'plan_status_id', 'plan_id', 'user_id', 'observations'
        );

        $this->actingAs($this->admin)->post(
            route('users.plans.store', $studentUser),
            array_merge($plan_user_array, [
                'clases_by_day'   => $plan->daily_clases,
                'class_numbers'   => $plan->class_numbers,
                'billed'          => 'on',
                'date'            => date('d-m-Y'),
                'payment_type_id' => PaymentType::TRANSFERENCIA,
                'amount'          => 30000,
            ])
        )->assertRedirect("/users/{$studentUser->id}");

        $this->assertDatabaseHas('plan_user_flows', [
            'start_date'      => now()->startOfMinute()->format('Y-m-d H:i:s'),
            'finish_date'     => $plan_user->finish_date->endOfDay()->format('Y-m-d H:i:s'),
            // 'date'            => date('Y-m-d'),
            'amount'          => 30000,
            'counter'         => $plan_user->counter,
            'plan_id'         => $plan_user->plan_id,
            'user_id'         => $plan_user->user_id,
            'paid'            => 1,
            'observations'    => "Compra de plan: {$plan_user->plan->plan} - {$plan_user->user->full_name}",
            'payment_date'    => today()->format('Y-m-d H:i:s'),
            'bill_pdf'        => null,
            'sii_token'       => "sin emision",
        ]);
    }
}
