<?php

namespace Tests\Feature\Commands\Clases;

use Tests\TestCase;
use App\Models\Users\Role;
use App\Models\Users\User;
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

        $tokens = [
            // // pablo's token
            // "fy5XNRPh704:APA91bGDOiJy0oseAKA7kWLSjhtTVjfDD2Lh3XuIrWTw636oZFh5T4nPjcZBBUrpzZbKpvbykwag75e5B0QYJ4MYOEJO-UFgeuZwcd2CR81r7C75ptrJMHZ3-VUMAisJztJUoVdHDHTB",
            // // raul's token
            "f7R7RPyWTwuE3JKHdmytvZ:APA91bG0gmfOlwjhe2NEmFsh04c843QjBMuua4LXRJx55ESiO6nHBqgSqeeBHAd-y6HUXX-xk23SxvHyinz2s2W3uQ_FImPGzvqEa0TfguUs8K7ih2XbBlS7JeScX2CzurgSK8obpBd7"
        ];

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

    
}


