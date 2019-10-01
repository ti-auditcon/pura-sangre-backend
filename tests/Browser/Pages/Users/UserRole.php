<?php

namespace Tests\Browser\Pages\Users;

use App\Models\Users\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class UserRole extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        $user = User::first();
        return '/role-user' . $user->id . '/edit';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }
}
