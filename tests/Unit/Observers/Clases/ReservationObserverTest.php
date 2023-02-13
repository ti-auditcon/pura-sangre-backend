<?php

namespace Tests\Unit\Observers\Clases;

use Tests\TestCase;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Clases\Reservation;
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

    /** @test */
    public function it_user_can_book_a_special_clase_even_if_he_doesnt_have_more_quotas_for_the_day()
    {
        $user = factory(User::class)->create();

        $plan_user = PlanUser::withoutEvents(function () use ($user) {
            return factory(PlanUser::class)->create([
                'user_id' => $user->id,
                'plan_id' => factory(Plan::class)->create([
                    'class_numbers' => 1,
                    'daily_clases' => 1,
                ]),
                'counter' => 1,
            ]);
        });

        $this->assertDatabaseHas('plan_user', [
            'user_id' => $user->id,
            'plan_id' => $plan_user->plan_id,
            'counter' => 1,
        ]);

        $clase = factory(\App\Models\Clases\Clase::class)->create([
            'date' => '2018-01-01',
            'clase_type_id' => factory(\App\Models\Clases\ClaseType::class)->create()->id,
        ]);
        $other_clase = factory(\App\Models\Clases\Clase::class)->create([
            'date' => '2018-01-01',
            'clase_type_id' => factory(\App\Models\Clases\ClaseType::class)->create(['special' => true])->id,
        ]);

        $reservation = Reservation::withoutEvents(function () use ($user, $clase) {
            return factory(\App\Models\Clases\Reservation::class)->create([
                'user_id' => $user->id,
                'clase_id' => $clase->id,
            ]);
        });

        $observer = new \App\Observers\Clases\ReservationObserver();

        $new_reservation = factory(\App\Models\Clases\Reservation::class)->make([
            'user_id' => $user->id,
            'clase_id' => $other_clase->id,
        ]);

        $this->assertTrue(
            $observer->creating($new_reservation)
        );
    }
}
