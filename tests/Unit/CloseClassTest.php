<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Clases\Clase;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;
use App\Console\Commands\Clases\CloseClass;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CloseClassTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    /**
     *  The minutes of difference between the start of the class,
     *  and the moment that the list has to be pass
     *
     *  @var  integer
     */
    const MINUTES_TO_PASS_LIST = CloseClass::MINUTES_TO_PASS_LIST;

    /**
     *  Because we round to five minutes the list, that means if the current minute is in 15 to 20,
     *  it should pass list, but any other don't
     *
     *  @var  array
     */
    const MINUTES_NOT_PASS_LIST = [14, 20];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clases:close';

    /**
     *  Get the rounded minute from an specific time,
     *  useful in case of server trigger after the specific hour and minute
     *  Also add the 0
     *
     *  @param   Carbon\Carbon|string  $time
     *
     *  @return  Carbon\Carbon
     */
    public function roundMinutesToMultipleOfFive($time) {
        $minutes = date('i', strtotime($time));

        return $time->setTime($time->format('H'), $minutes - ($minutes % 5));
    }

    // the iterated clases are just those which meet with the 15 minutes difference between,
    //   start and the moment of the passing list

    /** @test */
    public function classes_that_started_fifteen_minutes_ago_are_iterated()
    {
        $current_dateTime = now()->copy();

        /**
         *  Igual necesitamos redondear a un multiplo de 5,
         *  debido a que el rango minimo de diferencia es de 5 minutos
         */
        $clase_hour = $this->roundMinutesToMultipleOfFive(
            $current_dateTime->copy()->subMinutes(self::MINUTES_TO_PASS_LIST)
        );

        // create a class for today
        $clase = factory(Clase::class)->create([
            'date' => today()->format('Y-m-d'),
            'start_at' => $clase_hour,
        ]);

        $reservation = Reservation::withoutEvents(function () use ($clase) {
            return factory(Reservation::class)->create([
                'reservation_status_id' => ReservationStatus::CONFIRMED,
                'clase_id'              => $clase->id
            ]);
        });

        $this->assertDatabaseHas('reservations', [
            'id'                    => $reservation->id,
            'reservation_status_id' => ReservationStatus::CONFIRMED
        ]);

        $this->artisan('clases:close');

        $this->assertDatabaseHas('reservations', [
            'id'                    => $reservation->id,
            'reservation_status_id' => ReservationStatus::CONSUMED
        ]);
    }

    /** @test */
    public function it_reservations_with_classes_that_started_less_than_fifteen_minutes_ago_are_not_iterated()
    {
        $current_dateTime = now()->copy();

        $clase_hour = $this->roundMinutesToMultipleOfFive(
            $current_dateTime->copy()->subMinutes(self::MINUTES_TO_PASS_LIST)
        );

        $clase = factory(Clase::class)->create([
            'date'      => today()->format('Y-m-d'),
            'start_at'  => $clase_hour->copy()->addMinutes(1)->format('H:i:s'),
        ]);

        $reservation = Reservation::withoutEvents(function () use ($clase) {
            return factory(Reservation::class)->create([
                'reservation_status_id' => ReservationStatus::CONFIRMED,
                'clase_id'              => $clase->id
            ]);
        });

        $this->assertDatabaseHas('reservations', [
            'id'  => $reservation->id,
            'reservation_status_id' => ReservationStatus::CONFIRMED,
        ]);

        $this->artisan($this->signature)
                ->assertExitCode(0);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'reservation_status_id' => ReservationStatus::CONFIRMED,
        ]);
    }
}
