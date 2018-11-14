<?php

namespace App\Providers;

use App\Models\Users\User;
use App\Policies\UserPolicy;
use Laravel\Passport\Passport;
use App\Models\Plans\PlanUser;
use App\Policies\PlanUserPolicy;
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
         User::class => UserPolicy::class,
         PlanUser::class => PlanUserPolicy::class,
     ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::resource('Users', 'App\Policies\UserPolicy');

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
