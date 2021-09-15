<?php

namespace Tests\Feature\Invoicing;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class InvoicingControllerTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;
    
    /** @test */
    public function iva_tax_document_is_canceled_correctly()
    {
        $this->actingAs($this->admin)
                ->post(
                    "/tax-documents/47fff2b7c0c14c3a3b2e31d33535b513cf888a92092c81da55c7860bb8c505c0/cancel"
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
