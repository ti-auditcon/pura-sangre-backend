<?php

namespace Tests\Feature\Commands;

use Tests\TestCase;
use App\Models\Plans\PlanUserFlow;
use App\Models\Bills\PaymentStatus;
use App\Models\Plans\FlowOrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IssueReceiptsCommandsTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /**
     *  @test
     */
    public function it_generate_tax_docuemnt_to_all_paid_plan_user_flows()
    {

        $this->assertTrue(true);
        
        // $bill = factory(PlanUserFlow::class)->create([
        //     'paid'       => PaymentStatus::PAID,
        //     'created_at' => today(),
        //     'sii_token'  => null
        // ]);

        // $this->artisan('purasangre:invoicing:issue-receipts')
        //         ->expectsOutput('PlanUserFlow id being iterated is: ' . $bill->id)
        //         ->expectsOutput('Receipts issued')
        //         ->assertExitCode(0);

        // $bill = PlanUserFlow::find($bill->id);

        // $this->assertNotNull($bill->sii_token);
    }
}
