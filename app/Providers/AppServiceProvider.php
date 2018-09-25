<?php

namespace App\Providers;

use App\Models\Clases\Clase;
use App\Models\Clases\Block;
use App\Models\Plans\PlanUser;
use App\Observers\Clases\ClaseObserver;
use App\Observers\Clases\BlockObserver;
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
      Block::observe(BlockObserver::class);
      Clase::observe(ClaseObserver::class);
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
