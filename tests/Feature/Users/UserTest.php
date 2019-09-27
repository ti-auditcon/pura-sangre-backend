<?php

namespace Tests\Feature\Users;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_see_login_page()
    {
        $response = $this->get('/login');

        $response->assertSee('Correo')->assertSee('Contraseña');
    }
    
    /** @test */
    public function admin_user_can_log_into_system()
    {
        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);

        $user = factory(\App\Models\Users\User::class)->create();

        $user->roles()->attach(1);

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
    }

    /** @test */
    public function admin_can_create_a_new_user()
    {
        // $this->withoutExceptionHandling();

        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);

        $user = factory(\App\Models\Users\User::class)->create();

        $user->roles()->attach(1);

        $client_user = [
            'rut' => '19.007.597-4',
            'first_name' => 'Casimer',
            'last_name' => 'Stroman',
            'email' => 'josefa87@example.net',
            'birthdate' => '1975-02-09',
            'gender' => 'female',
            'phone' => 69547003,
            'address' => '288 Goldner Extensions Apt. 396',
            'status_user_id' => 3,
        ];

        $response = $this->actingAs($user)->post(route('users.store'), $client_user);

        $this->assertDatabaseHas('users', array_splice($client_user, 1));
    }

    /** @test */
    public function create_a_new_client_user_has_required_fields()
    {
        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);

        $user = factory(\App\Models\Users\User::class)->create();

        $user->roles()->attach(1);

        $this->actingAs($user)
             ->post(route('users.store'), [])
             ->assertSessionHasErrors([
                'first_name', 'last_name', 'email',
                'birthdate', 'gender'
            ]);
    }

    /** @test */
    public function admin_can_see_a_client_user()
    {
        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);

        $user = factory(\App\Models\Users\User::class)->create();

        $user->roles()->attach(1);

        $client_user = factory(\App\Models\Users\User::class)->create();

        $this->actingAs($user)->get('/users/' . $client_user->id)
                              ->assertSee($client_user->first_name);
    }
}

        // $this->withoutExceptionHandling();