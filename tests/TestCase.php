<?php

namespace Tests;

use App\Models\Users\User;
use App\Models\Users\RoleUser;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     *  Admin User
     */
    protected $admim;

    public function setUp() :void
    {
        parent::setUp();

        $this->admin = factory(User::class)->create();
        
        RoleUser::create([
            'user_id' => $this->admin->id,
            'role_id' => 1
        ]);

        $this->manageSharedViewData();
    }

        /**
     *  [manageSharedViewData description]

     *  @return  void
     */
    public function manageSharedViewData()
    {
        $birthdate_users = app(User::class)->birthdate_users();

        view()->share(compact('birthdate_users'));
    }
}
