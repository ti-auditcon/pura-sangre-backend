<?php

namespace App\Providers;

use Carbon\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/** [AuthServiceProvider description] */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        /**
         * [Passport expiration tokens "first token"]
         * @var [type]
         */
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));

        /**
         * [Passport expiration refresh token]
         * @var [type]
         */
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}
