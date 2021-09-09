<?php

namespace Tests\Feature\Commands;

use Tests\TestCase;
use App\Models\Invoicing\TaxDocument;
use App\Mail\NewPlanUserEmail;
use App\Models\Plans\PlanUserFlow;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IssueReceiptsTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /** @test */
    // public function bill_pdf_is_stored_through_api_correctly()
    // {
    //     $planUserFlow = factory(PlanUserFlow::class)->create([
    //         /**  this dte token is just for haulmer developer environment, does not works with purasangre credentials  */
    //         'sii_token' => '7ee8a1bf916af5255bc2e23f5e06b499360ae10384bfb1d36085593fbea5dcf1' 
    //     ]);

    //     $response = (new TaxDocument)->getReceipt($planUserFlow->sii_token);

    //     $this->actingAs($this->admin)
    //             ->post("/dte/{$planUserFlow->id}/save-pdf", [
    //                 'pdf' => $response->pdf,
    //             ])->assertJson(['message' => 'El PDF se ha guardado correctamente.'])
    //                 ->assertSuccessful();

    //     $this->assertDatabaseHas('plan_user_flows', [
    //         'id'        => $planUserFlow->id,
    //         'sii_token' => $planUserFlow->sii_token,
    //         'bill_pdf'  => config('app.api_url') . "/storage/boletas/boleta_{$planUserFlow->id}_{$planUserFlow->user->first_name}.pdf"
    //     ]);
    // }

    /** @test */
    // public function receipt_is_issued_correctly()
    // {
    //     Mail::fake();
        
    //     $plan = factory(PlanUserFlow::class)->create();

    //     $this->assertDatabaseHas('plan_user_flows', [
    //         'id'        => $plan->id,
    //         'bill_pdf'  => null,
    //         'sii_token' => null,
    //     ]);

    //     $this->artisan('purasangre:invoicing:issue-receipts')
    //             ->expectsOutput('Receipts issued')
    //             ->assertExitCode(0);

    //     Mail::assertSent(NewPlanUserEmail::class, 1);

    //     /**
    //      *  We request the plan user flow again, to get the most recent copy of the model in database
    //      *  Then we check if the model retrieved has bill_pdf and token
    //      */

    //     $planUserFlow = PlanUserFlow::find($plan->id);
    //     $this->assertFalse(is_null($planUserFlow->bill_pdf));
    //     $this->assertFalse(is_null($planUserFlow->sii_token));
    // }
}
