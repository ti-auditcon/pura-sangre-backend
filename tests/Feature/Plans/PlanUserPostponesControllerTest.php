<?php

namespace Tests\Feature\Plans;

use Tests\TestCase;
use App\Models\Users\Role;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Users\RoleUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlanUserPostponesControllerTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /**
     *  A created admin for tests
     *
     * @var  User
     */
    protected $admin;

    /**
     *  Before the tests are executed
     *
     * @return  void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->createAnAdminUser();

        $birthdate_users = app(User::class)->birthdate_users();
        view()->share(compact('birthdate_users'));

        // ClaseType::create([
        //     'clase_type' => 'CrossFit',
        //     'clase_color' => 'CrossFit',
        //     'icon' => 'crossfit.svg',
        //     'icon_white' => 'crossfit.svg',
        //     'active' => true,
        // ]);
    }

    /**
     *  Manage all the requirements to create a Admin for tests
     *
     *  @return  void
     */
    public function createAnAdminUser(): void
    {
        $user = factory(User::class)->create();
        $this->createAdminRole();
        $this->makeUserAnAdmin($user);
        $this->admin = $user;
    }

    /**
     *  @return  void
     */
    public function createAdminRole(): void
    {
        factory(Role::class)->create(['role' => 'admin']);
    }

    /**
     *  @param   User  $user
     */
    protected function makeUserAnAdmin($user)
    {
        factory(RoleUser::class)->create(['user_id' => $user->id, 'role_id' => Role::ADMIN]);
    }
    
    /** 
     *  Validations are:
     *    -  start and end dates are required
     *    -  end date must be equals or after start date 
     * 
     *  @test
     */
    public function at_freezing_plan_user_it_must_have_validations()
    {
        $plan_user = factory(PlanUser::class)->create();

        
        /**    -  start and end dates are required  */
        $this->actingAs($this->admin)
            ->post("/plan-user/{$plan_user->id}/postpones", [])
            ->assertSessionHasErrors([
                "start_freeze_date" => "Se requiere ingresar una fecha de inicio.",
                "end_freeze_date"   => "Se requiere ingresar una fecha de término."
            ]);
            

        /**    -  end date must be equals or after start date   */
        $this->actingAs($this->admin)
            ->post("/plan-user/{$plan_user->id}/postpones", [
                'start_freeze_date' => today(),
                'end_freeze_date'   => today()->subDay()
            ])->assertSessionHasErrors([
                "end_freeze_date" => "La fecha de término del congelamiento debe ser igual o mayor a la de inicio."
            ]);
    }

    /** @test */
    public function admin_can_freeze_a_plan_user_for_student()
    {
        // $this->withoutExceptionHandling();

    }
}
