<?php

namespace Tests\Feature\Invoicing;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class InvoicingControllerTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;
    
    /** @test */
    public function vat_tax_document_is_canceled_correctly()
    {
        $this->actingAs($this->admin)
                ->post(
                    "/tax-documents/6af59abd745b870188f9a6d038d2459102459949d0ebdf5ec2b25109164936b5/cancel"
                )->assertSuccessful();

        $this->assertTrue(true);
    }

    /** @test */
    public function document_not_subject_to_vat_is_canceled_correctly()
    {
        // this->withoutExceptionHandling();
        // write test here
    }
}
