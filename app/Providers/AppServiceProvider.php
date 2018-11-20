<?php

namespace App\Providers;

use App\Models\Clases\Block;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Users\User;
use App\Models\Wods\Stage;
use App\Models\Wods\Wod;
use App\Models\Clases\Reservation;
use App\Observers\Clases\BlockObserver;
use App\Observers\Clases\ClaseObserver;
use App\Observers\Clases\ReservationObserver;
use App\Observers\Plans\PlanUserObserver;
use App\Observers\Users\UserObserver;
use App\Observers\Wods\StageObserver;
use App\Observers\Wods\WodObserver;
use Illuminate\Support\ServiceProvider;
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
      User::observe(UserObserver::class);


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
