<?php

namespace Tests\Feature\Observers\Clases;

use Tests\TestCase;
use App\Models\Clases\Clase;
use Tests\Traits\CarbonTrait;
use App\Models\Clases\Reservation;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ClaseObserverTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    use CarbonTrait;

    /** @test */
    public function it_deletes_the_reservations_of_the_clase()
    {
        $this->travelTo('2019-01-01 10:00:00');

        $clase = factory(Clase::class)->create([
            'date' => '2019-01-01 10:01:00',
        ]);

        $reservation = Reservation::withoutEvents(function () use($clase) {
            return factory(Reservation::class)->create([
                'clase_id' => $clase->id,
            ]);
        });

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
        ]);

        $clase->delete();

        $this->assertDatabaseMissing('reservations', [
            'id' => $reservation->id,
        ]);
    }

    /** @test */
    public function it_deletes_several_reservations_of_the_clase()
    {
        $this->travelTo('2019-01-01 10:00:00');

        $clase = factory(Clase::class)->create([
            'date' => '2019-01-01 10:01:00',
        ]);

        $reservationOne = Reservation::withoutEvents(function () use($clase) {
            return factory(Reservation::class)->create([
                'clase_id' => $clase->id,
            ]);
        });

        $reservationTwo = Reservation::withoutEvents(function () use($clase) {
            return factory(Reservation::class)->create([
                'clase_id' => $clase->id,
            ]);
        });

        $this->assertDatabaseHas('reservations', [
            'id' => $reservationOne->id,
        ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservationTwo->id,
        ]);

        $clase->delete();

        $this->assertDatabaseMissing('reservations', [
            'id' => $reservationOne->id,
        ]);

        $this->assertDatabaseMissing('reservations', [
            'id' => $reservationTwo->id,
        ]);
    }

    /** @test */
    public function it_does_not_delete_the_reservations_of_the_clase_if_the_date_is_in_the_past()
    {
        $this->travelTo('2019-01-01 10:00:00');

        $clase = factory(Clase::class)->create([
            'date' => '2019-01-01 09:59:00',
        ]);

        $reservation = Reservation::withoutEvents(function () use($clase) {
            return factory(Reservation::class)->create([
                'clase_id' => $clase->id,
            ]);
        });

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
        ]);

        $clase->delete();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
        ]);
    }

    /** @test */
    public function it_does_not_delete_reservations_from_others_clases()
    {
        $this->travelTo('2019-01-01 10:00:00');

        $claseOne = factory(Clase::class)->create([
            'date' => '2019-01-01 10:01:00',
        ]);

        $otherClase = factory(Clase::class)->create([
            'date' => '2019-01-01 10:02:00',
        ]);

        $reservationOne = Reservation::withoutEvents(function () use($claseOne) {
            return factory(Reservation::class)->create([
                'clase_id' => $claseOne->id,
            ]);
        });

        $reservationOtherClase = Reservation::withoutEvents(function () use($otherClase) {
            return factory(Reservation::class)->create([
                'clase_id' => $otherClase->id,
            ]);
        });

        $this->assertDatabaseHas('reservations', [
            'id' => $reservationOne->id,
        ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservationOtherClase->id,
        ]);

        $claseOne->delete();

        $this->assertDatabaseMissing('reservations', [
            'id' => $reservationOne->id,
        ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservationOtherClase->id,
        ]);
    }

    /** @test */
    public function it_triggers_reservation_deleted_event()
    {
        $this->travelTo('2019-01-01 10:00:00');

        $clase = factory(Clase::class)->create([
            'date' => '2019-01-01 10:01:00',
        ]);

        $createdReservation = Reservation::withoutEvents(function () use($clase) {
            return factory(Reservation::class)->create([
                'clase_id' => $clase->id,
            ]);
        });

        $observerTriggered = false;

        Event::listen('eloquent.deleting: ' . Reservation::class, function (Reservation $reservation) use (&$observerTriggered, $createdReservation) {
            $observerTriggered = $reservation->id === $createdReservation->id;
        });

        $clase->delete();

        $this->assertTrue($observerTriggered);
    }
}
