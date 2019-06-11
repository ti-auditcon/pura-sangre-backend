<?php

namespace App\Providers;

use App\Models\Bills\Bill;
use App\Models\Clases\Block;
use App\Models\Clases\Clase;
use App\Models\Clases\Reservation;
use App\Models\Plans\PlanUser;
use App\Models\Users\User;
use App\Models\Wods\Stage;
use App\Models\Wods\Wod;
use App\Observers\Bills\BillObserver;
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
        //
    }
}
