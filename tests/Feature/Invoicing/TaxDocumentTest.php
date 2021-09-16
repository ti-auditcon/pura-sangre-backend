<?php

namespace Tests\Feature\Invoicing;

use Tests\TestCase;
use App\Models\Invoicing\TaxDocument;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TaxDocumentTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /** @test */
    public function it_fill_emisor_data_for_tax_issuer_correctly()
    {
        $taxDocument = new TaxDocument();

        $this->assertNotNull($taxDocument->getEmisor());

    }

        // $this->setTaxIssuerData(config('app.env'));

        // $this->initializeGuzzleClient();

        // $this->setToken($token);
        // $this->create();

    /** @test */
    public function it_set_url_and_keys_correctly()
    {
        $taxDocument = new TaxDocument();

        $this->assertNotNull($taxDocument->getBaseUri());
        $this->assertNotNull($taxDocument->getverifiedSsl());
        $this->assertNotNull($taxDocument->hasApiKey());
    }

    /** @test */
    public function emisor_data_for_tax_issuer_is_an_object()
    {
        $taxDocument = new TaxDocument();

        $this->assertTrue(is_object($taxDocument->getEmisor()), "emisor is not an object");
    }

    /** @test */
    public function it_issue_tax_documents_correctly()
    {
        //$this->withoutExceptionHandler();
        //test here
    }

            //         if ($environment === 'local' || $environment === 'testing') {
        //     $environment = 'sandbox';
        // }

        // $this->fillUrlAndKeys($environment);

        // $this->fillEmisor($environment);
    
    /** @test */
    // public function get_received_dtes()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
}
