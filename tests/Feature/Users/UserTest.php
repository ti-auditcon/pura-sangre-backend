<?php

namespace Tests\Feature\Users;

use Carbon\Carbon;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

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
        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);

        $user = factory(\App\Models\Users\User::class)->create();
        $user->roles()->attach(1);

        $client_user = factory(\App\Models\Users\User::class)->make([
            'avatar' => null,
        ]);
        $client_user = $client_user->toArray();
        $client_user['birthdate'] = Carbon::parse($client_user['birthdate'])->format('Y-m-d');

        $response = $this->actingAs($user)->post(route('users.store'), array_merge($client_user, ['test_user' => 'on']));

        $client_user = array_diff_key($client_user, [
            'rut_formated' => 1, 'full_name' => 2, 'avatar' => 3, 'rut' => 4,
        ]);

        $this->assertDatabaseHas('users', $client_user);
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