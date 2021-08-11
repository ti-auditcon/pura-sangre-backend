<?php

namespace Tests\Feature\Invoicing;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DTETest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;
    
    /** @test */
    public function get_received_dtes()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
