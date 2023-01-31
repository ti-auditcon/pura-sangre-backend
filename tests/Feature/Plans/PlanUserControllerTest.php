<?php

namespace Tests\Feature\Plans;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Plans\Plan;
use App\Models\Users\Role;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Users\RoleUser;
use App\Models\Clases\ClaseType;
use App\Models\Plans\PlanStatus;
use App\Models\Bills\PaymentType;
use App\Models\Plans\FlowOrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlanUserControllerTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /**
     * A created admin for tests
     *
     * @var  User
     */
    protected $admin;

    /**
     * Before the tests are executed
     *
     * @return  void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->createAnAdminUser();

        $birthdate_users = app(User::class)->birthdate_users();

        view()->share(compact('birthdate_users'));

        ClaseType::create([
            'clase_type' => 'CrossFit',
            'clase_color' => 'CrossFit',
            'icon' => 'crossfit.svg',
            'icon_white' => 'crossfit.svg',
            'active' => true,
        ]);
    }

    /**
     * Manage all the requirements to create a Admin for tests
     *
     * @return  void
     */
    public function createAnAdminUser(): void
    {
        $user = factory(User::class)->create();
        $this->createAdminRole();
        $this->makeUserAnAdmin($user);
        $this->admin = $user;
    }

    /**
     * @return  void
     */
    public function createAdminRole(): void
    {
        factory(Role::class)->create(['role' => 'admin']);
    }

    /**
     * @param   User  $user
     */
    protected function makeUserAnAdmin($user)
    {
        factory(RoleUser::class)->create(['user_id' => $user->id, 'role_id' => Role::ADMIN]);
    }

    // /** @test */
    // public function view_assign_user_plan_just_has_the_available_plans()
    // {
    //     $this->withoutExceptionHandling();
    //     $admin = $this->createAdminAndBringIt();
    //     $user = $this->createAUserAndBringIt();
    //     factory(Parameter::class)->create();

    //     $data = $this->actingAs($admin)->get("/admin/users/{$user->id}/plans/create")
    //                                     ->assertOk();

    //     // foreach($data['plans'] as $plan) {
    //     //     $this->
    //     // }
    // }

    //  deberia separar en dos tests el hecho que el admin no puede agregar un plan que esta descontinuado?

    /** @test */
    public function admin_can_assign_any_plan_to_a_client()
    {
        $studentUser = factory(User::class)->create();
        factory(Plan::class, 3)->create();
        
        foreach (Plan::all() as $plan) {
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

            $this->actingAs($this->admin)->post("/users/{$studentUser->id}/plans", $data);

            $this->assertDatabaseHas('plan_user', [
                'plan_id'        => $plan_user->plan_id,
                'user_id'        => $studentUser->id,
                'counter'        => $plan_user->counter,
                'start_date'     => $plan_user->start_date->format('Y-m-d H:i:s'),
                // 'finish_date'    => $plan->plan_period_id ?
                //                     now()->copy()->addMonths($plan->plan_period_id)->format('Y-m-d H:i:s') :
                //                     now()->addDays(7)->format('Y-m-d H:i:s'),
                'observations'   => $plan_user->observations,
                'plan_status_id' => PlanStatus::ACTIVO
            ]);

            /** Cancel plan to assign other in the next iteration */
            $studentUser->actual_plan()->update(['plan_status_id' => PlanStatus::CANCELADO]);
        }
    }

    /** @test */
    public function when_admin_choose_to_create_a_bill_a_plan_user_flow_is_associated_to_plan_user()
    {
        $studentUser = factory(User::class)->create();
        factory(Plan::class, 3)->create();

        $plan = factory(Plan::class)->create();
        $plan_user = factory(PlanUser::class)->make(['user_id' => $studentUser->id, 'plan_id' => $plan->id]);

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
            'start_date'      => $plan_user->start_date->format('Y-m-d H:i:s'),
            'finish_date'     => $plan_user->finish_date->format('Y-m-d H:i:s'),
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
