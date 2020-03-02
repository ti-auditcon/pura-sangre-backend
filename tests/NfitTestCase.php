<?php

namespace Tests;

use Tests\TestCase;
use App\Models\Users\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NfitTestCase extends TestCase
{
    use DatabaseMigrations;



    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed();
        
        $this->duringSetUp();
    }

    /**
     *  Allows implementation in a test.
     */
    protected function duringSetUp()
    {
        // ..
    }

    function createModel($class, $attributes = [], $times = 1) {
        return factory($class, $times > 1 ? $times : null)->create($attributes);
    }

    function makeModel($class, $attributes = [], $times = 1) {
        return factory($class, $times > 1 ? $times : null)->make($attributes);
    }
}
