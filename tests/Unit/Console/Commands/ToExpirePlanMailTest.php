<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;
use App\Mail\ToExpireEmail;
use Tests\Traits\CarbonTrait;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ToExpirePlanMailTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;
    use CarbonTrait;

    protected $signature = 'purasangre:mails:plans-to-expire';

    // it send emails to users who has a plan about to expire in 3 days
    /** @test */
    public function it_send_emails_to_users_who_has_a_plan_about_to_expire_in_three_days()
    {
        Mail::fake();

        $this->travelTo('2020-01-01 00:00:00');

        PlanUser::withoutEvents(function() {
            factory(PlanUser::class)->create([
                'finish_date' => '2020-01-04 00:00:00',
                'plan_status_id' => PlanStatus::ACTIVE,
            ]);
            
            // end of the day
            factory(PlanUser::class)->create([
                'finish_date' => '2020-01-04 23:59:59',
                'plan_status_id' => PlanStatus::ACTIVE,
            ]);
        });

        $this->artisan($this->signature);

        Mail::assertSent(ToExpireEmail::class, 2);
    }
}
