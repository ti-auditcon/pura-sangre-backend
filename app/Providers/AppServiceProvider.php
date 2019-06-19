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
        /** observer */
        Wod::observe(WodObserver::class);
        
        /** observer */
        Bill::observe(BillObserver::class);
        
        /** observer */
        User::observe(UserObserver::class);
        
        /** observer */
        Block::observe(BlockObserver::class);
        
        /** observer */
        Clase::observe(ClaseObserver::class);
        
        /** observer */
        Stage::observe(StageObserver::class);
        
        /** observer */
        PlanUser::observe(PlanUserObserver::class);
        
        /** observer */
        Reservation::observe(ReservationObserver::class);

        /** Set language to Spanish Chile from Carbon */
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
