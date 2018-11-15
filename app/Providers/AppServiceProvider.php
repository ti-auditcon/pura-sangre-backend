<?php

namespace App\Providers;

use App\Models\Clases\Block;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Wods\Stage;
use App\Models\Wods\Wod;
use App\Models\clases\Reservation;
use App\Observers\Clases\BlockObserver;
use App\Observers\Clases\ClaseObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\Plans\PlanUserObserver;
use App\Observers\Wods\StageObserver;
use App\Observers\Wods\WodObserver;
use App\Observers\Clases\ReservationObserver;
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
      Reservation::observe(ReservationObserver::class);
      Stage::observe(StageObserver::class);
      Wod::observe(WodObserver::class);


      \Carbon\Carbon::setLocale(config('app.locale'));


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
