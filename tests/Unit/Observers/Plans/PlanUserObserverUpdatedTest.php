<?php

namespace Tests\Unit\Observers\Plans;

use Tests\TestCase;
use App\Models\Plans\PlanStatus;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;
use App\Observers\Plans\PlanUserObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlanUserObserverUpdatedTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    // if 
    /** @test */
    public function it_pending_and_confirmed_reservations_are_deleted_if_planUser_is_being_canceled()
    {
        $planToCancel = $this->fakeActivePlanUser([
            'start_date' => now()->addMinute(),
            'finish_date' => now()->addDays(31),
            'plan_status_id' => PlanStatus::ACTIVE
        ]);

        $pendingRreservation = Reservation::withoutEvents(function() use ($planToCancel) {
            return factory(Reservation::class)->create([
                'user_id' => $planToCancel->user_id,
                'plan_user_id' => $planToCancel->id,
                'reservation_status_id' => ReservationStatus::PENDING
            ]);
        });

        $confirmedRreservation = Reservation::withoutEvents(function() use ($planToCancel) {
            return factory(Reservation::class)->create([
                'user_id' => $planToCancel->user_id,
                'plan_user_id' => $planToCancel->id,
                'reservation_status_id' => ReservationStatus::CONFIRMED
            ]);
        });

        $this->assertDatabaseHas('reservations', [
            'id' => $pendingRreservation->id,
            'reservation_status_id' => ReservationStatus::PENDING
        ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $confirmedRreservation->id,
            'reservation_status_id' => ReservationStatus::CONFIRMED
        ]);

        $planUserObserver = new PlanUserObserver();
        $planToCancel->plan_status_id = PlanStatus::CANCELED;
        $planUserObserver->updated($planToCancel);

        $this->assertDatabaseMissing('reservations', [
            'id' => $pendingRreservation->id,
        ]);

        $this->assertDatabaseMissing('reservations', [
            'id' => $confirmedRreservation->id,
        ]);
    }

    // if planUser is being canceled the consumed and lost reservations are not deleted but unlink from the planUser
    /** @test */
    public function it_consumed_and_lost_reservations_are_unlinked_from_the_plan_user()
    {
        $planToCancel = $this->fakeActivePlanUser([
            'start_date' => now()->addMinute(),
            'finish_date' => now()->addDays(31),
            'plan_status_id' => PlanStatus::ACTIVE
        ]);

        $consumedReservation = Reservation::withoutEvents(function() use ($planToCancel) {
            return factory(Reservation::class)->create([
                'user_id' => $planToCancel->user_id,
                'plan_user_id' => $planToCancel->id,
                'reservation_status_id' => ReservationStatus::CONSUMED
            ]);
        });

        $lostReservation = Reservation::withoutEvents(function() use ($planToCancel) {
            return factory(Reservation::class)->create([
                'user_id' => $planToCancel->user_id,
                'plan_user_id' => $planToCancel->id,
                'reservation_status_id' => ReservationStatus::LOST
            ]);
        });

        $this->assertDatabaseHas('reservations', [
            'id' => $consumedReservation->id,
            'reservation_status_id' => ReservationStatus::CONSUMED,
            'plan_user_id' => $planToCancel->id,
        ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $lostReservation->id,
            'reservation_status_id' => ReservationStatus::LOST,
            'plan_user_id' => $planToCancel->id,
        ]);

        $planUserObserver = new PlanUserObserver();
        $planToCancel->plan_status_id = PlanStatus::CANCELED;
        $planUserObserver->updated($planToCancel);

        $this->assertDatabaseHas('reservations', [
            'id' => $consumedReservation->id,
            'reservation_status_id' => ReservationStatus::CONSUMED,
            'plan_user_id' => null,
        ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $lostReservation->id,
            'reservation_status_id' => ReservationStatus::LOST,
            'plan_user_id' => null,
        ]);
    }
    // if planUser is not being canceled the reservations are fixed
    // it updates the status of the user 
}
