<?php

namespace App\Providers;

use App\Models\Plans\PlanUser;
use Illuminate\Support\ServiceProvider;
use App\Observers\Plans\PlanUserObserver;

/** [AppServiceProvider description]*/
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      PlanUser::observe(PlanUserObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
