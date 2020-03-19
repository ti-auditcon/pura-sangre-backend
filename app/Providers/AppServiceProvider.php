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
        /** Wod observer */
        Wod::observe(WodObserver::class);

        /** Bill observer */
        Bill::observe(BillObserver::class);

        /** User observer */
        User::observe(UserObserver::class);

        /** Block observer */
        Block::observe(BlockObserver::class);

        /** Clase observer */
        Clase::observe(ClaseObserver::class);

        /** observer */
        Stage::observe(StageObserver::class);

        /** observer */
        PlanUser::observe(PlanUserObserver::class);

        /** Plan observer */
        Plan::observe(PlanObserver::class);

        /** observer */
        Reservation::observe(ReservationObserver::class);

        /** Clases type observer */
        ClaseType::observe(ClaseTypeObserver::class);

        /** Set language to Spanish Chile from Carbon */
        setlocale(LC_ALL, "es_CL.UTF-8");

        $birthdate_users = app(User::class)->birthdate_users();

        view()->share(compact('birthdate_users'));
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
