<?php

namespace Tests\Feature\Users;

use Tests\TestCase;
use App\Models\Users\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /** @test */
    public function it_stores_a_new_password_reset()
    {
        $password_reset = factory(PasswordReset::class)->create();

        $this->assertDatabaseHas('password_resets', [
            'token' => $password_reset->token,
            'email' => $password_reset->email,
            'expired' => $password_reset->expired,
        ]);
    }
}
