<?php

namespace Tests\Feature\Users;

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function admin_can_see_role_user_view()
    {
        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\PlanPeriodsTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);
        $this->seed(\UsersTableSeeder::class);
        $this->seed(\RoleUserTableSeeder::class);

        $admin = User::first();

        $client_user = factory(\App\Models\Users\User::class)->create();

        $response = $this->actingAs($admin)->get('/role-user/' . $client_user->id . '/edit');

        $response->assertOk();

        $response->assertSee('Gestionar Roles');
    }

    /** @test */
    public function admin_can_sync_user_roles()
    {
        $this->withoutExceptionHandling();
        
        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\PlanPeriodsTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);
        $this->seed(\UsersTableSeeder::class);
        $this->seed(\RoleUserTableSeeder::class);
        $this->seed(\RolesTableSeeder::class);

        $admin = User::first();

        $client_user = factory(\App\Models\Users\User::class)->create();

        $roles = Role::pluck('id')->toArray();

        $request = [
            "user_id" => $client_user->id,
            "role" => $roles
        ];

        $response = $this->actingAs($admin)->post('/role-user', $request);

        $response->assertRedirect('/role-user/' . $client_user->id . '/edit');

        foreach ($roles as $role) {
            $this->assertDatabaseHas('role_user', [
                'user_id' => $client_user->id,
                'role_id' => $role
            ]);            
        }

        $url = $this->get('/role-user/' . $client_user->id . '/edit');

        $url->assertSee('Roles Disponibles:');
    }
}
        // $this->withoutExceptionHandling();