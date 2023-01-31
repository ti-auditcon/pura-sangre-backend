<?php

namespace Tests\Feature\Commands\Clases;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Users\User;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Settings\Setting;
use App\Models\Clases\Reservation;
use Illuminate\Support\Facades\Bus;
use App\Models\Clases\ReservationStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ClasesClearTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "purasangre:clases:clear";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Remove users that don't confirm assistance xx min before class start";

    /**
     * Get the rounded minute from an specific time,
     * useful in case of server trigger after the specific hour and minute
     * Also add the 0
     *
     * @param   Carbon\Carbon|string  $time
     *
     * @return  Carbon\Carbon
     */
    public function roundMinutesToMultipleOfFive($time) {
        $minutes = date('i', strtotime($time));

        return $time->setTime($time->format('H'), $minutes - ($minutes % 5));
    }

    /**
     * [createReservationsForClass description]
     *
     * @param   [type]  $claseId  [$claseId description]
     * @param   [type]  $times    [$times description]
     *
     * @return  [type]            [return description]
     */
    public function createReservationsForClass($claseId, $times)
    {
        for ($i = 0; $i < $times; $i++) { 
            $planUser = factory(PlanUser::class)->create();

            factory(Reservation::class)->create([
                'reservation_status_id' => ReservationStatus::PENDING,
                'clase_id' => $claseId,
                'plan_user_id' => $planUser->id,
                'user_id' => $planUser->user_id
            ]);
        }
    }

    /**
     * [createActivePlanUserFor description]
     *
     * @param   [type]  $userId  [$userId description]
     *
     * @return  [type]           [return description]
     */
    public function createActivePlanUserFor($userId)
    {
        return factory(PlanUser::class)->create([
            'start_date'     => today()->startOfMonth()->format('Y-m-d'),
            'finish_date'    => today()->endOfMonth()->format('Y-m-d'),
            'plan_status_id' => PlanStatus::ACTIVO,
            'user_id'        => $userId
        ]);
    }

    //
    // las reservaciones iteradas son solo las que tienen estado pendientes, no cuenta las confirmadas
    // el alumno se le devuelve el cupo correctamente
    // el alumno confirmado no se le borra la clase
    // si se elimina la reserva, esta queda softdeletes en la base de datos
    // chquear  estan biensss
    // si el plan termina hoy y su ultima reserva es hoy no se puede cerrar el plan hasta que su ultima reserva sea consumida

    // usuario con plan con 0 counters no se le cierra el plan y tampoco le elimina la clase
    /**
     * la clase a limpiar es la de la hora correspondiente,
     * según los minutos para remover a los alumnos de la tabla Settings
     *
     * @test
     */
    public function clase_hour_corresponds_to_hour_by_minutes_to_remove_users_from_settings_table()
    {
        Bus::fake();

        $planuser = factory(Planuser::class)->create([
            'plan_status_id' => Planstatus::ACTIVO
        ]);

        $settings = Setting::first(['id', 'minutes_to_remove_users']);

        /**
         * igual necesitamos redondear a un multiplo de 5,
         * debido a que el rango minimo de diferencia es de 5 minutos
         */
        $clase_hour = $this->roundMinutesToMultipleOfFive(
            now()->startofminute()->addminutes($settings->minutes_to_remove_users)
        )->format('H:i');

        // create a class for today
        $clase = factory(Clase::class)->create([
            'date'      => today(),
            'start_at'  => Carbon::parse($clase_hour)->format('H:i:s'),
            'finish_at' => Carbon::parse($clase_hour)->copy()->startofhour()->addhours(1)->format('h:i:s')
        ]);

        factory(Reservation::class)->create([
            'reservation_status_id' => ReservationStatus::PENDING,
            'plan_user_id'          => $planuser->id,
            'user_id'               => $planuser->user_id,
            'clase_id'              => $clase->id
        ]);

        $this->artisan($this->signature)
                ->expectsOutput("The class hour being iterated is: {$clase_hour}")
                ->assertExitCode(0);
    }

    public function createTodayClassAt($clase_hour)
    {
        $clase_hour = Carbon::parse($clase_hour);

        return factory(Clase::class)->create([
            'date'      => today(),
            'start_at'  => $clase_hour->copy()->format('H:i:s'),
            'finish_at' => $clase_hour->copy()->addHour()->format('H:i:s')
        ]);

    }

    /** @test */
    public function pending_reservations_are_deleted()
    {
        Bus::fake();

        $planUser = factory(PlanUser::class)->create(['plan_status_id' => PlanStatus::ACTIVO]);

        $settings = Setting::first(['id', 'minutes_to_remove_users']);

        /**
         * Igual necesitamos redondear a un multiplo de 5,
         * debido a que el rango minimo de diferencia es de 5 minutos
         */
        $clase_hour = $this->roundMinutesToMultipleOfFive(
            now()->startOfMinute()->addMinutes($settings->minutes_to_remove_users)
        )->format('H:i');

        // create a class for today
        $clase = $this->createTodayClassAt($clase_hour);

        $pending_reservation = factory(Reservation::class)->create([
            'reservation_status_id' => ReservationStatus::PENDING,
            'plan_user_id'          => $planUser->id,
            'user_id'               => $planUser->user_id,
            'clase_id'              => $clase->id
        ]);

        $this->artisan($this->signature)
                ->expectsOutput("The class hour being iterated is: {$clase_hour}")
                ->assertExitCode(0);

        $this->assertDatabaseMissing('reservations', ['id' => $pending_reservation->id]);
    }

    /** @test */
    public function confirmed_resevations_arent_deleted()
    {
        Bus::fake();

        $settings = Setting::first(['id', 'minutes_to_remove_users']);

        $planUser = factory(PlanUser::class)->create(['plan_status_id' => PlanStatus::ACTIVO]);

        /**
         * Igual necesitamos redondear a un multiplo de 5,
         * debido a que el rango minimo de diferencia es de 5 minutos
         */
        $clase_hour = $this->roundMinutesToMultipleOfFive(
            now()->startOfMinute()->addMinutes($settings->minutes_to_remove_users)
        )->format('H:i');
        // create a class for today
        $clase = $this->createTodayClassAt($clase_hour);

        $confirmed_reservation = factory(Reservation::class)->create([
            'reservation_status_id' => ReservationStatus::CONFIRMED,
            'clase_id'              => $clase->id,
            'plan_user_id'          => $planUser->id,
            'user_id'               => $planUser->user_id,
        ]);

        $this->assertDatabaseHas('reservations', [
            'id'                    => $confirmed_reservation->id,
            'reservation_status_id' => ReservationStatus::CONFIRMED
        ]);

        $this->artisan($this->signature)
                ->expectsOutput("The class hour being iterated is: {$clase_hour}")
                ->assertExitCode(0);

        $this->assertDatabaseHas('reservations', [
            'id'         => $confirmed_reservation->id,
        ]);
    }


    /** @test */
    public function it_return_quota_to_plan_user_when_is_removed()
    {
        $user = factory(User::class)->create();
        $planUser = $this->createActivePlanUserFor($user->id);

        $quotas = $planUser->counter;
        $this->assertDatabaseHas('plan_user', [
            'id' => $planUser->id, 'counter' => $quotas
        ]);

        $settings = Setting::first(['id', 'minutes_to_remove_users']);

        /**
         * Igual necesitamos redondear a un multiplo de 5,
         * debido a que el rango minimo de diferencia es de 5 minutos
         */
        $clase_hour = $this->roundMinutesToMultipleOfFive(
            now()->startOfMinute()->addMinutes($settings->minutes_to_remove_users)
        )->format('H:i');

        // create a class for today
        $clase = $this->createTodayClassAt($clase_hour);

        factory(Reservation::class)->create([
            'clase_id'              => $clase->id,
            'reservation_status_id' => ReservationStatus::PENDING,
            'user_id'               => $planUser->user_id,
        ]);

        $this->artisan($this->signature)->assertExitCode(0);

        $this->assertDatabaseHas('plan_user', [
            'id'      => $planUser->id,
            'counter' => $quotas
        ]);
    }
}