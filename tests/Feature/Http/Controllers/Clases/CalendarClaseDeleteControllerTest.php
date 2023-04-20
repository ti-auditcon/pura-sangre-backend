<?php

namespace Tests\Feature\Http\Controllers\Clases;

use Tests\TestCase;
use App\Models\Clases\Clase;
use App\Models\Clases\ClaseType;
use Illuminate\Support\Facades\Event;
use App\Observers\Clases\ClaseObserver;
use Illuminate\Foundation\Testing\WithFaker;
use App\Observers\Clases\ReservationObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CalendarClaseDeleteControllerTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    /** @test */
    public function it_deletes_the_clases_of_the_chosen_date_and_type()
    {
        $clase = factory(Clase::class)->create([
            'date' => '2020-01-01 10:00:00',
            'clase_type_id' => 1,
        ]);

        $this->actingAs($this->admin)->post('calendar/clases/delete', [
            'date' => '2020-01-01',
            'type_clase' => 1
        ]);

        $this->assertSoftDeleted('clases', [
            'id' => $clase->id,
        ]);
    }

    /** @test */
    public function it_deletes_several_clases()
    {
        $claseType = factory(ClaseType::class)->create();

        $claseOne = factory(Clase::class)->create([
            'date' => '2020-01-01 10:00:00',
            'clase_type_id' => $claseType->id,
        ]);

        $claseTwo = factory(Clase::class)->create([
            'date' => '2020-01-01 11:00:00',
            'clase_type_id' => $claseType->id,
        ]);

        $this->actingAs($this->admin)->post('calendar/clases/delete', [
            'date' => '2020-01-01',
            'type_clase' => $claseType->id
        ]);

        $this->assertSoftDeleted('clases', [
            'id' => $claseOne->id,
        ]);

        $this->assertSoftDeleted('clases', [
            'id' => $claseTwo->id,
        ]);
    }

    /** @test */
    public function it_doesnt_delete_other_clases_types_of_the_given_date()
    {
        $otherClase = factory(Clase::class)->create([
            'date' => '2020-01-01 10:00:00',
            'clase_type_id' => factory(ClaseType::class)->create()->id,
        ]);

        $this->actingAs($this->admin)->post('calendar/clases/delete', [
            'date' => '2020-01-01',
            'type_clase' => 1
        ]);

        $this->assertDatabaseHas('clases', [
            'id' => $otherClase->id,
        ]);
    }

    /** @test */
    public function it_doesnt_delete_other_clases_of_others_days()
    {
        $dayPrevious = factory(Clase::class)->create([
            'date' => '2019-12-31 10:00:00',
            'clase_type_id' => 1,
        ]);

        $dayAfter = factory(Clase::class)->create([
            'date' => '2020-01-02 10:00:00',
            'clase_type_id' => 1,
        ]);

        $this->actingAs($this->admin)->post('calendar/clases/delete', [
            'date' => '2020-01-01',
            'type_clase' => 1
        ]);

        $this->assertDatabaseHas('clases', [
            'id' => $dayPrevious->id,
        ]);

        $this->assertDatabaseHas('clases', [
            'id' => $dayAfter->id,
        ]);
    }

    // it returns a success message
    /** @test */
    public function it_returns_a_success_message()
    {
        $this->actingAs($this->admin)->post('calendar/clases/delete', [
            'date' => '2020-01-01',
            'type_clase' => 1
        ])->assertJson([
            'success' => 'Clases del día 2020-01-01 eliminadas correctamente'
        ]);
    }

    // it ReservationObserver is called when deleting a clase
    /** @test */
    public function it_reservationObserver_is_called_when_deleting_a_clase()
    {
        $observerTriggered = false;

        $createdClase = factory(Clase::class)->create([
            'date' => '2020-01-01 10:00:00',
            'clase_type_id' => 1,
        ]);

        Event::listen('eloquent.deleting: ' . Clase::class, function (Clase $clase) use (&$observerTriggered, $createdClase) {
            $observerTriggered = $clase->id === $createdClase->id;
        });

        $this->actingAs($this->admin)->post('calendar/clases/delete', [
            'date' => '2020-01-01',
            'type_clase' => 1
        ]);

        $this->assertTrue($observerTriggered);
    }

    // I need to do the it_reservationObserver_is_called_when_deleting_a_clase but make it works for laravel 5.8
    // it ReservationObserver is called when deleting a clase
    // /** @test */
    // public function it_reservationObserver_is_called_when_deleting_a_clase()
    // {
    //     $this->spy(\App\Observers\Clases\ReservationObserver::class);
    
    //     $clase = factory(Clase::class)->create([
    //         'date' => '2020-01-01 10:00:00',
    //         'clase_type_id' => 1,
    //     ]);
        
        
    //     $this->actingAs($this->admin)->post('calendar/clases/delete', [
    //         'date' => '2020-01-01',
    //         'type_clase' => 1
    //     ]);
        

    //     \App\Observers\Clases\ReservationObserver::shouldHaveReceived('deleted')->with($clase);
    // }
}
