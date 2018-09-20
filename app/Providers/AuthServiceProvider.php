<?php

namespace App\Providers;

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
        Passport::tokensExpireIn(now()->addMinutes(30));

        /**
         * [Passport expiration refresh token]
         * @var [type]
         */
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
