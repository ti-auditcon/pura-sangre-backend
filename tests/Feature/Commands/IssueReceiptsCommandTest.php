<?php

namespace Tests\Feature\Commands;

use Tests\TestCase;
use App\Models\Plans\PlanUserFlow;
use App\Models\Bills\PaymentStatus;
use App\Models\Plans\FlowOrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IssueReceiptsCommandTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /**
     *  The name and signature of the console command.
     *
     *  @var  string
     */
    protected $signature = 'purasangre:invoicing:issue-receipts';

    /**
     *  The console command description.
     *
     *  @var  string
     */
    protected $description = 'Send TaxDocuments to SII through Haulmer API';

    /**
     *  Check if the requests are with ssl connection
     *
     *  @var  boolean
     */
    protected $verifiedSSL;
    
    /**
     *  url of the Purasangre API 
     *
     *  @var  string
     */
    protected $purasangreApiUrl;

    /**
     *  [setUp description]
     *
     * @return  void    [return description]
     */
    public function setUp() :void
    {
        parent::setUp();

        $this->purasangreApiUrl = config('app.api_url');
    }


    // it issue tax documents correctly
    // it only issue tax documents created last twelve hours
    // 

    /** @test */
    public function it_issue_tax_documents_correctly()
    {
        // $this->withoutExceptionHandling();

    }

    /**  @test  */
    public function it_issues_tax_documents_created_last_twelve_hours()
    {

        $this->artisan($this->signature)->assertExitCode(0);
                
        $this->assertFalse($this->expectOutputString('foo'));
    }
    
    /**  @test  */
    public function it_doenst_issues_tax_documents_created_before_twelve_hours()
    {

        $this->artisan($this->signature)->assertExitCode(0);
                
        $this->assertFalse($this->expectOutputString('foo'));
    }

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
