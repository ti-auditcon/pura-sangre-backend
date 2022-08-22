<?php

namespace Tests\Unit\Observers\Clases;

use Tests\TestCase;
use App\Models\Users\User;
use App\Models\Clases\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReservationObserverTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    /** @test */
    public function it_hasReserve_method_responds_with_false_if_user_hasnt_a_reservation_the_given_date()
    {
        $user = factory(User::class)->create();

        $clase = factory(\App\Models\Clases\Clase::class)->create();

        $observer = new \App\Observers\Clases\ReservationObserver();

        $this->assertFalse($observer->hasReserve($clase, $user->id));
    }

    /** @test */
    public function it_hasReserve_method_responds_false_if_user_hasnt_reservation_for_given_clase_type_and_date()
    {
        $user = factory(User::class)->create();
        $clase = factory(\App\Models\Clases\Clase::class)->create([
            'date' => '2018-01-01',
        ]);
        $other_clase = factory(\App\Models\Clases\Clase::class)->create([
            'date' => '2018-01-01',
        ]);

        // we ensure that both clases have different types
        $this->assertNotEquals($clase->clase_type_id, $other_clase->clase_type_id);

        $reservation = Reservation::withoutEvents(function () use ($user, $clase) {
            return factory(\App\Models\Clases\Reservation::class)->create([
                'user_id' => $user->id,
                'clase_id' => $clase->id,
            ]);
        });

        $observer = new \App\Observers\Clases\ReservationObserver();
        $this->assertFalse($observer->hasReserve($other_clase, $user->id));
    }

    /** @test */
    public function it_hasReserve_method_responds_with_message_if_user_has_reservation_for_given_clase_type_and_date()
    {
        $user = factory(User::class)->create();
        $clase = factory(\App\Models\Clases\Clase::class)->create([
            'date' => '2018-01-01',
            'clase_type_id' => factory(\App\Models\Clases\ClaseType::class)->create()->id,
        ]);
        $other_clase = factory(\App\Models\Clases\Clase::class)->create([
            'date' => '2018-01-01',
            'clase_type_id' => $clase->clase_type_id,
        ]);
        $reservation = Reservation::withoutEvents(function () use ($user, $clase) {
            return factory(\App\Models\Clases\Reservation::class)->create([
                'user_id' => $user->id,
                'clase_id' => $clase->id,
            ]);
        });
        $observer = new \App\Observers\Clases\ReservationObserver();
        
        $this->assertEquals(
            "Ya tiene una clase tomada para {$clase->claseType->clase_type} este día.",
            $observer->hasReserve($clase, $user->id)
        );
    }
}
