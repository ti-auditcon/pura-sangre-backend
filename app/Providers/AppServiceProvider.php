<?php

namespace App\Providers;

use App\Models\Clases\Block;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Exercises\Stage;
use App\Observers\Clases\BlockObserver;
use App\Observers\Clases\ClaseObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\Plans\PlanUserObserver;
use App\Observers\Exercises\StageObserver;
use Session;

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
      Stage::observe(StageObserver::class);

      if(!Session::has('clases-type')){ Session::put('clases-type',1); }
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
