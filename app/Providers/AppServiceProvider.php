<?php

namespace App\Providers;

use Session;
use App\Models\Wods\Wod;
use App\Models\Bills\Bill;
use App\Models\Users\User;
use App\Models\Wods\Stage;
use App\Models\Clases\Block;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Clases\Reservation;
use App\Observers\Wods\WodObserver;
use App\Observers\Bills\BillObserver;
use App\Observers\Users\UserObserver;
use App\Observers\Wods\StageObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\Clases\ClaseObserver;
use App\Observers\Clases\BlockObserver;
use App\Observers\Plans\PlanUserObserver;
use App\Observers\Clases\ReservationObserver;

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
      Bill::observe(BillObserver::class);


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
