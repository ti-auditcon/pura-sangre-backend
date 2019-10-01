<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations; 

    /** @test */
    public function user_can_log_into_system()
    {
        $this->withoutExceptionHandling();

        $this->seed(\StatusUsersTableSeeder::class);
        $this->seed(\ClaseTypesTableSeeder::class);
        $this->seed(\PlanPeriodsTableSeeder::class);
        $this->seed(\PlansTableSeeder::class);
        $this->seed(\UsersTableSeeder::class);
        $this->seed(\RoleUserTableSeeder::class);
        $this->seed(\RolesTableSeeder::class);

        $user = factory(\App\Models\Users\User::class)->create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('password')
        ]);


        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Ingresar')
                    ->assertPathIs('/');
        });
    }
}
