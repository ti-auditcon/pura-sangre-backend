<?php

use Tests\TestCase;

class NfitTestCase extends TestCase
{
    /**
     *
     */
    protected function setUp(): void
    {
        // $this->runSeeds();

        $this->duringSetUp();
    }

    /**
     *  Allows implementation in a test.
     */
    protected function duringSetUp()
    {
        // ..
    }

    /**
     *  Runs Seeds of dataTable
     *
     *  @return  void
     */
    protected function runSeeds()
    {
        // Run the DatabaseSeeder...
        $this->seed();
    }
}
