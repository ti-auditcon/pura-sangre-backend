<?php

namespace Tests;

use App\Models\Users\Role;
use App\Models\Users\User;
use App\Models\Users\RoleUser;
use Tests\Traits\FactoriesCrud;
use App\Models\Settings\Setting;
use Tests\Traits\PlanFactoryTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use PlanFactoryTrait;
    use FactoriesCrud;

    /**
     * Admin User
     */
    protected $admin;

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

        factory(Role::class)->create(['role' => 'admin']);

        RoleUser::create([
            'user_id' => $this->admin->id, 
            'role_id' => Role::ADMIN
        ]);

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
