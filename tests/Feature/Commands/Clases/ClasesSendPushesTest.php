<?php

namespace Tests\Feature\Commands\Clases;

use Tests\TestCase;
use App\Models\Users\Role;
use App\Models\Users\User;
use App\Models\Clases\Clase;
use Tests\Traits\CarbonTrait;
use App\Models\Settings\Setting;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ClasesSendPushesTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    use CarbonTrait;
    
    /**
     * The name and signature of the console command.
     *
     * @var  string
     */
    protected $signature = 'purasangre:clases:send-notifications';

    /**
     * Create a user with role = admin in database with factory
     * and return it
     *
     * @return  \App\Models\Tenant\Users\User
     */
    public function createAdminAndBringIt()
    {
        return factory(User::class)->create(['role' => Role::ADMIN]);
    }

    /**
     * Create a user with factory with role = user,
     * and return it
     *
     * @return  \App\Models\Tenant\Users\User
     */
    public function createAUserAndBringIt()
    {
        return factory(User::class)->create();
    }

    /** 
     * This test can check if the handle() method can successfully retrieve the settings from the database, 
     * get the current date and time, and round it to the nearest multiple of five.
     * 
     * @test
     */
    public function it_can_retrieve_settings_and_clase_date_time_and_round_to_multiple_of_five()
    {
        $settings = factory(Setting::class)->create([
            'minutes_to_send_notifications' => 30,
            'minutes_to_remove_users' => 15,
        ]);

        $this->travelTo('2020-01-01 00:00:00');

        $this->artisan($this->signature)
        // we add the 30 minutes (minutes_to_send_notifications) to the current date and time
            ->expectsOutput('Clase date and time: 2020-01-01 00:30:00')
            ->assertExitCode(0);    
    }

    /** 
     * This test can check if the handle() method can successfully retrieve all pending reservations for notification for the rounded date and time.
     * 
     * @test
     */
    public function it_can_retrieve_pending_reservations_for_notification()
    {
        $settings = factory(Setting::class)->create([
            'minutes_to_send_notifications' => 30,
            'minutes_to_remove_users' => 15,
        ]);

        Reservation::withoutEvents(function() {
            factory(Reservation::class)->create([
                'reservation_status_id' => ReservationStatus::PENDING,
                'clase_id' => factory(Clase::class)->create([
                    'date' => '2000-01-01 00:30:00',
                ]),
            ]);
        });

        $this->travelTo('2000-01-01 00:00:00');

        $this->artisan($this->signature)
            ->expectsOutput('Reservations: 1')
            ->assertExitCode(0);
    }
}
