<?php

namespace Tests\Feature\Commands\Clases;

use Tests\TestCase;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ClasesSendPushesTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /**
     * Create a user with role = admin in database with factory
     * and return it
     *
     * @return  \App\Models\Tenant\Users\User
     */
    public function createAdminAndBringIt()
    {
        return factory(User::class)->create(['role' => Role::ADMIN]);
    }

    /**
     * Create a user with factory with role = user,
     * and return it
     *
     * @return  \App\Models\Tenant\Users\User
     */
    public function createAUserAndBringIt()
    {
        return factory(User::class)->create();
    }

    /** @test */
    public function test_name()
    {
        // $this->withoutExceptionHandling();
        $this->assertTrue(true);
    }

}
