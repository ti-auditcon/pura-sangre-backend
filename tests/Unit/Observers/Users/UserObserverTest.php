<?php

namespace Tests\Unit\Observers\Users;

use Tests\TestCase;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanStatus;
use App\Models\Users\StatusUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserObserverTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();

        $this->plan = factory(Plan::class)->create([
            'plan'           => 'Test Plan',
            'description'    => 'Test Plan',
            'has_clases'     => true,
            'class_numbers'  => 1,
            'amount'         => 0,
            'custom'         => false,
            'convenio'       => false,
            'daily_clases'   => 1,
            'plan_status_id' => PlanStatus::ACTIVE,
        ]);

        for ($i = 0; $i < 3; $i++) { 
            factory(StatusUser::class)->create([
                'status_user' => 'Test number' . $i,
            ]);

            factory(PlanStatus::class)->create([
                'plan_status' => 'Test number' . $i,
            ]);
        }
    }

    /**
     *  todo: this need to be refactored 
     * @test
     *  */
    public function it_assign_test_user_plan_correctly()
    {
        $user = User::withoutEvents(function () {
            return factory(User::class)->make();
        });

        $this->actingAs($this->admin)
                ->post('/users', array_merge(
                    $user->toArray(),
                    [
                        'rut' => '11.111.111-1',
                        'since' => today()->format('Y-m-d'),
                        'test_user' => true
                    ]
                ))
                ->assertSessionDoesntHaveErrors();

        $user = User::orderBy('id', 'desc')->first();

        $this->assertEquals($user->status_user_id, StatusUser::TEST);

        $this->assertDatabaseHas('plan_user', [
            'user_id' => $user->id,
            'plan_id' => 1,
            'counter' => $this->plan->class_numbers,
        ]);
    }
}
