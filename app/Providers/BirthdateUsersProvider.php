<?php

namespace App\Providers;

use App\Models\Users\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class BirthdateUsersProvider extends ServiceProvider
{
    /**
     *  Register services.
     *
     *  @return  void
     */
    public function register()
    {
    }

    /**
     *  Bootstrap services.
     *
     *  @return  void
     */
    public function boot()
    {
        if (Schema::hasTable('users')) { /** For instance put it here to avoid error in memory phpunits  */
            $birthdate_users = app(User::class)->birthdate_users();
            
            view()->share(compact('birthdate_users'));
        }
    }
}
