<?php

namespace App\Providers;

use App\Models\Clases\Block;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Wods\Stage;
use App\Models\Wods\Wod;
use App\Observers\Clases\BlockObserver;
use App\Observers\Clases\ClaseObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\Plans\PlanUserObserver;
use App\Observers\Wods\StageObserver;
use App\Observers\Wods\WodObserver;
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
      Wod::observe(WodObserver::class);

      // if(!Session::has('clases-type-id')){
      //   Session::put('clases-type-id',1);
      //   Session::put('clases-type-name',Clase::find(1)->clase_type);
      // }
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
