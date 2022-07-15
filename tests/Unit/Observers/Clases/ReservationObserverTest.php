<?php

namespace Tests\Unit\Observers\Clases;

use Tests\TestCase;
use App\Models\Clases\Clase;
use App\Models\Clases\ClaseType;
use App\Models\Clases\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use App\Observers\Clases\ReservationObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReservationObserverTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    /**
     *  If a use has a special class taken,
     *  it is not considered in hasReserve function
     *  
     *  @test
     */
    public function it_hasReserve_method_doesnt_count_special_clase_types()
    {
        $special_clase = factory(Clase::class)->create([
            'clase_type_id' => factory(ClaseType::class)->create([
                'special' => true
            ])->id,
        ]);

         // we call the hasReserve method from the observer and we expect it to return false
        $observer = app(ReservationObserver::class);
        
        $reservation = Reservation::withoutEvents(function () use ($special_clase) {
            return factory(Reservation::class)->create([
                'clase_id' => $special_clase->id,
            ]);
        });

        $this->assertFalse(
            $observer->hasReserve($special_clase, $reservation->user_id)
        );
    }
}
