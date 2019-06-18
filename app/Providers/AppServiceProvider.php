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
use App\Observers\Users\UserObserver;
use App\Observers\Wods\StageObserver;
use App\Observers\Bills\BillObserver;
use App\Observers\Clases\BlockObserver;
use App\Observers\Clases\ClaseObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\Plans\PlanUserObserver;
use App\Providers\TelescopeServiceProvider;
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


      setlocale(LC_ALL, "es_CL.UTF-8");
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
