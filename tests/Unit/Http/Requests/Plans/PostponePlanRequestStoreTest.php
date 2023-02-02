<?php

namespace Tests\Unit\Http\Requests\Plans;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PostponePlanRequestStoreTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    protected $planUser;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->planUser = $this->fakeActivePlanUser();
    }

    /** @test */
    public function it_start_date_is_required()
    {
        $this->actingAs($this->admin)
            ->post("/plan-user/{$this->planUser->id}/postpones", [])
            ->assertSessionHasErrors([
                "start_freeze_date" => "Se requiere ingresar una fecha de inicio.",
            ]);
    }

    /** @test */
    public function it_end_date_is_required()
    {
        $this->actingAs($this->admin)
            ->post("/plan-user/{$this->planUser->id}/postpones", [])
            ->assertSessionHasErrors([
                "end_freeze_date" => "Se requiere ingresar una fecha de término.",
            ]);
    }

    /** @test */
    public function it_start_date_must_be_a_date()
    {
        $response = $this->actingAs($this->admin)
            ->post("/plan-user/{$this->planUser->id}/postpones", [
                'start_freeze_date' => 'not a date',
                'end_freeze_date'   => today()->addDay()
            ]);

            $response->assertSessionHasErrors([
                "start_freeze_date" => "El campo fecha de inicio no corresponde con una fecha válida."
            ]);
    }

    /** @test */
    public function it_end_date_must_be_a_date()
    {
        $response = $this->actingAs($this->admin)
            ->post("/plan-user/{$this->planUser->id}/postpones", [
                'start_freeze_date' => today(),
                'end_freeze_date'   => 'not a date'
            ]);

            $response->assertSessionHasErrors([
                "end_freeze_date" => "El campo fecha de término no corresponde con una fecha válida."
            ]);
    }

    /** @test */
    public function it_end_date_must_be_equals_or_after_start_date()
    {
        $this->actingAs($this->admin)
            ->post("/plan-user/{$this->planUser->id}/postpones", [
                'start_freeze_date' => today(),
                'end_freeze_date'   => today()->subDay()
            ])->assertSessionHasErrors([
                "end_freeze_date" => "La fecha de término del congelamiento debe ser igual o mayor a la de inicio."
            ]);
    }
}
