<?php

namespace App\Providers;

use Session;
use App\Models\Wods\Wod;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Wods\Stage;
use App\Models\Clases\Block;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Clases\ClaseType;
use App\Models\Clases\Reservation;
use App\Observers\Wods\WodObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Observers\Bills\BillObserver;
use App\Observers\Plans\PlanObserver;
use App\Observers\Users\UserObserver;
use App\Observers\Wods\StageObserver;
use App\Observers\Clases\BlockObserver;
use App\Observers\Clases\ClaseObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\Plans\PlanUserObserver;
use App\Observers\Clases\ClaseTypeObserver;
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
        Bill::observe(BillObserver::class);
        Block::observe(BlockObserver::class);
        Clase::observe(ClaseObserver::class);
        ClaseType::observe(ClaseTypeObserver::class);
        Plan::observe(PlanObserver::class);
        PlanUser::observe(PlanUserObserver::class);
        Reservation::observe(ReservationObserver::class);
        Stage::observe(StageObserver::class);
        User::observe(UserObserver::class);
        Wod::observe(WodObserver::class);
        /** Set language to Spanish Chile from Carbon */
        setlocale(LC_ALL, "es_CL.UTF-8");

        URL::forceScheme('https');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
