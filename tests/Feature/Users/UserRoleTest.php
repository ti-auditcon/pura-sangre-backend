<?php

namespace Tests\Feature\Users;

use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function admin_can_see_dashboard()
    {
        $this->withoutExceptionHandling();

        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\PlanPeriodsTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);
        $this->seed(\UsersTableSeeder::class);
        $this->seed(\RoleUserTableSeeder::class);

        $admin = User::first();

        $response = $this->actingAs($admin)->get('/');

        $response->assertStatus(200);
    }
}
