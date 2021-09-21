<?php

namespace Tests\Feature\Invoicing;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class InvoicingControllerTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;
    
    /** @test */
    // public function vat_tax_document_is_canceled_correctly()
    // {
    //     $this->withoutExceptionHandling();

    //     $this->actingAs($this->admin)
    //             ->post(
    //                 "/tax-documents/a570adf5c727bfc266ea5bb2416364f3dc6e8ddf304086bfa5ff408d698f1a7d/cancel"
    //             )->assertSuccessful();

    //     $this->assertTrue(true);
    // }

    // /** @test */
    // public function document_not_subject_to_vat_is_canceled_correctly()
    // {
    //     // this->withoutExceptionHandling();
    //     // write test here
    // }
}
