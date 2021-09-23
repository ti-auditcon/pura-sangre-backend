<?php

namespace Tests\Feature\Invoicing;

use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use App\Models\Invoicing\TaxDocument;
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

    /** @test */
    public function it_set_tax_document_data_correctly()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([new Response(200, [], 'All settled')]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        // dd($client->request('GET', '/')->getStatusCode());

        $taxDocument = new TaxDocument();
        // dump($taxDocument->getData());
        $this->assertNotNull($taxDocument->getData());
    }

    /** @test */
    public function tax_document_data_is_an_object()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([new Response(200, [], 'All settled')]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $taxDocument = new TaxDocument();
        $this->assertTrue(is_object($taxDocument->getData()));
    }

    // /** @test */
    // public function it_starts_guzzle_client_correctly()
    // {
    //     $this->httpRequest = new Client([
    //         'base_uri' => $this->baseUrl,
    //         'headers'  => [ "apikey" => $this->apiKey ]
    //     ]);
    // }

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

    // /** @test */
    // public function it_issue_tax_documents_correctly()
    // {
    //     //$this->withoutExceptionHandler();
    //     //test here
    // }
}
