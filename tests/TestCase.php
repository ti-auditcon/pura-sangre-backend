<?php

namespace Tests;

use App\Models\Users\User;
use App\Models\Users\RoleUser;
use App\Models\Settings\Setting;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Admin User
     */
    protected $admim;

    public function setUp() :void
    {
        parent::setUp();

        $this->getPurasangreReady();

        $this->manageSharedViewData();
    }

    public function getPurasangreReady()
    {
        $this->admin = User::withoutEvents(function () {
            return factory(User::class)->create();
        });

        RoleUser::create(['user_id' => $this->admin->id, 'role_id' => 1]);

        $setting = new Setting;
        $setting->id = 1;
        $setting->minutes_to_send_notifications = 30;
        $setting->minutes_to_remove_users = 45;
        $setting->save();
    }

        /**
     * [manageSharedViewData description]

     * @return  void
     */
    public function manageSharedViewData()
    {
        $birthdate_users = app(User::class)->birthdate_users();

        view()->share(compact('birthdate_users'));
    }
}
