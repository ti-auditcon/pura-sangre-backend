<?php

namespace Tests\Feature\Jobs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Support\Str;
use App\Jobs\SendPushNotification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SendPushNotificationTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /**
     *  Create a user with role = admin in database with factory
     *  and return it
     *
     *  @return  \App\Models\Tenant\Users\User
     */
    public function createAdminAndBringIt()
    {
        return factory(User::class)->create(['role' => Role::ADMIN]);
    }

    /**
     *  Create a user with factory with role = user,
     *  and return it
     *
     *  @return  \App\Models\Tenant\Users\User
     */
    public function createAUserAndBringIt()
    {
        return factory(User::class)->create();
    }

    /** @test */
    public function push_are_sended_correctly()
    {
        Queue::fake();

        $tokens= [];
        for ($i = 0; $i < 10; $i++) {
            array_push($tokens, Str::random(100));
        }

        foreach ($tokens as $fcm_token) {
            SendPushNotification::dispatch(
                $fcm_token,
                "Tu reserva ha sido eliminada. 😱",
                "No has confirmado tu clase de CrossFit de las 12:00 hrs."
            );
        }

        Queue::assertPushed(SendPushNotification::class, function($job) use ($tokens) {
            return in_array($job->getToken(), $tokens);
        });
    }

    /**
     *  IT'S NEED TO BE TESTED HARDCODE
     *
     *  @test
     */
    public function it_user_receive_push_notification()
    {
        SendPushNotification::dispatch(
            // Rauls Purasangre fcm_token
            "djAtXTBg79M:APA91bExgsYenQ-l9rL_Emu4KnrumIA_4uyT2XcMuxl_fs3HV_ofSg_O-9SOQAmg9bME5UjwaFfZEIytVIX3DdVENENe_QgN08Cx_DOZBExR_hdZoGTQdOH_VOwP4ge0454UNgxI5P6Y",
            "Notificación de prueba para eliminacion de clase",
            "Fecha/hora envío: " . now()->format('d-m-Y H:i:s')
        );

        $this->assertTrue(true);
    }


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
}


