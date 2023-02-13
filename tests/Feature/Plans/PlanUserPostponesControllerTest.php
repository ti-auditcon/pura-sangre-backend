<?php

namespace Tests\Feature\Plans;

use Tests\TestCase;
use App\Models\Users\Role;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Users\RoleUser;
use App\Models\Plans\PlanStatus;
use App\Models\Plans\PostponePlan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlanUserPostponesControllerTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public const PLAN_IS_FREEZED_MESSAGE = 'El plan no puede ser actualizado porque está congelado.';

    // if the plan is freezed, it can't be updated until is unfreezed

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

        $this->createAnAdmin();

        $birthdate_users = app(User::class)->birthdate_users();
        view()->share(compact('birthdate_users'));
    }

    /**
     * Manage all the requirements to create a Admin for tests
     *
     * @return  void
     */
    public function createAnAdmin(): void
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

    /** @test */
    public function it_remaining_days_of_a_freezed_plan_are_calculated_correctly()
    {
        // we set days for an active current plan to be setted (for finish_date)
        $remainingPlanDays = 10;

        $plan_user = factory(PlanUser::class)->create([
            'plan_status_id' => PlanStatus::ACTIVE,
            'finish_date' => today()->endOfDay()->addDays($remainingPlanDays)
        ]);

        $this->actingAs($this->admin)
            ->post("/plan-user/{$plan_user->id}/postpones", [
                "start_freeze_date" => today()->format('d-m-Y'),
                "end_freeze_date" => today()->format('d-m-Y')
            ]);

        $this->assertDatabaseHas('freeze_plans', [
            'plan_user_id' => $plan_user->id,
            'start_date'   => today()->format('Y-m-d'),
            'finish_date'  => today()->format('Y-m-d'),
            'days'         => $remainingPlanDays
        ]);
    }

    /** @test  */
    public function at_unfreeze_a_plan_the_postpone_record_is_revoked_correctly()
    {
        $plan_user = factory(PlanUser::class)->create();
        
        $postpone = factory(PostponePlan::class)->create([
            'plan_user_id' => $plan_user->id,
            'revoked' => false
        ]);

        $this->actingAs($this->admin)->delete("/postpones/{$postpone->id}");

        $this->assertDatabaseHas('freeze_plans', [
            'id'      => $postpone->id,
            'revoked' => true,
        ]);
    }

    /** @test */
    public function at_unfreeze_the_plan_has_a_correct_finish_date()
    {
        $plan_user = factory(PlanUser::class)->create();

        $remainingPlanDays = today()->diffInDays($plan_user->finish_date);

        $postpone = factory(PostponePlan::class)->create([
            'days'         => $remainingPlanDays,
            'plan_user_id' => $plan_user->id
        ]);

        $this->actingAs($this->admin)->delete("/postpones/{$postpone->id}");

        $this->assertDatabaseHas('plan_user', [
            'id'          => $plan_user->id,
            'finish_date' => today()->endOfDay()->addDays($remainingPlanDays)->format('Y-m-d H:i:s'),
        ]);
    }

    /** @test */
    public function it_once_plan_is_freezed_it_has_freezed_status()
    {
        $plan_user = PlanUser::withoutEvents(function() {
            return factory(PlanUser::class)->create([
                'plan_status_id' => PlanStatus::ACTIVE,
            ]);  
        });

        $this->assertDatabaseHas('plan_user', [
            'id'             => $plan_user->id,
            'plan_status_id' => PlanStatus::ACTIVE
        ]);

        $this->actingAs($this->admin)
            ->post("/plan-user/{$plan_user->id}/postpones", [
                "start_freeze_date" => today()->format('d-m-Y'),
                "end_freeze_date"   => today()->endOfDay()
            ]);

        $this->assertDatabaseHas('plan_user', [
            'id'             => $plan_user->id,
            'plan_status_id' => PlanStatus::FROZEN
        ]);
    }

    // despues de congelar un plan, este queda con estado congelado
    // la tabla freeze_plans tiene el revoked en false, y los days son correctos

    /** @test */
    public function it_plan_changes_to_active_status_at_unfreeze_it()
    {
        $plan_user = factory(PlanUser::class)->create();

        $remainingPlanDays = today()->diffInDays($plan_user->finish_date);

        $postpone = factory(PostponePlan::class)->create([
            'days'         => $remainingPlanDays,
            'plan_user_id' => $plan_user->id
        ]);

        $this->actingAs($this->admin)->delete("/postpones/{$postpone->id}");

        $this->assertDatabaseHas('plan_user', [
            'id'             => $plan_user->id,
            'plan_status_id' => PlanStatus::ACTIVE
        ]);
    }

    /** 
     * Being freezed the plan can't be edited until the admin unfreezed 
     * 
     * @test
     */
    public function plan_user_with_status_freezed_can_not_be_edited()
    {
        $plan_user = Model::withoutEvents(function () {
            return factory(PlanUser::class)->create([
                'plan_status_id' => PlanStatus::FROZEN,
            ]);
        });

        $response = $this->actingAs($this->admin)
            ->patch("users/{$plan_user->user_id}/plans/{$plan_user->id}" , [
                'class_numbers' => 1,
                'clases_by_day' => 1,
                'plan_user_id'  => $plan_user->id,
            ]);
                
        $response->assertSessionHasErrors([
            'plan_user_id' => self::PLAN_IS_FREEZED_MESSAGE
        ]);
    }

}
