@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar', ['page' => 'payments' ])
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Documentos tributarios electrónicos emitidos</div>

                <div class="tools">
                    <div class="docsPagination">
                        <span class="docsPagination__text"></span>

                        <button class="dteList-button left-direction" data-direction="-" disabled="true">
                            <span class="button-wrapper">
                                <
                            </span>
                        </button>
                        <button class="dteList-button right-direction" data-direction="+" disabled="true">
                            <span class="button-wrapper">
                                >
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="ibox-body">

                    <table id="dtes-table" class="table table-hover">
                        <thead class="thead-default">
                            <tr>
                                <th width="20%">Nombre</th>
                                <th width="10%">Servicio</th>
                                <th width="20%">Tipo de documento</th>
                                <th width="15%">Monto total</th>
                                <th width="10%">Fecha emisión</th>
                                <th width="20%">Acciones</th>
                                <th hidden>fecha de emision</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
            </div>
        </div>

    </div>

</div>

@include('payments.modals.cancel_bill')

@include('payments.modals.assign_tax_document')

@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">    
@endsection

@section('scripts') {{-- scripts para esta vista --}}
{{--  datatable --}}
<script src="{{ asset('js/datatables.min.js') }}"></script>

{{-- To formatting dates --}}
<script src="{{ asset('js/moment.min.js') }}"></script>

{{-- Javasctipt to get issued invoices --}}
<script src="{{ asset('js/purasangre/payments/issued.js') }}"></script>

<script src="{{ asset('js/purasangre/payments/cancel-tax-documents.js') }}"></script>

{{-- Javasctipt to get issued invoices --}}
<script src="{{ asset('js/purasangre/payments/manage-pdf.js') }}"></script>

<script>
    const taxDocuments = @json(App\Models\Invoicing\TaxDocumentType::list());
    const cancellableTaxDocuments = @json(App\Models\Invoicing\Haulmer\TaxDocumentStatus::cancellableIds());
    const base_url = @json(url('/'));
    let currentTaxToken = 1;

    // jQuery(document).on('click', ".assign-tax-document-button", function () {
    //     $("#assignTaxDocumentModal").modal();

    //     currentTaxToken = $(this).data("token");

    //     fillCancelBillModal($(this).data());
    // });
</script>

@endsection
