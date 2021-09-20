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
                                <th width="10%">Acciones</th>
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

{{-- Javasctipt to get issued invoices --}}
<script src="{{ asset('js/purasangre/payments/manage-pdf.js') }}"></script>

<script>
    const taxDocuments = @json(App\Models\Invoicing\TaxDocumentType::list());
    const base_url = @json(url('/'));
    let previousTaxToken = 1;
    let currentTaxToken = 1;

    jQuery(document).on('click', ".cancel-bill-button", function() {
        $("#canceBillModal").modal();

        currentTaxToken = $(this).data("token");

        fillCancelBillModal($(this).data());
    });

    function fillCancelBillModal(billData)
    {
        Object.keys(billData).forEach(key => {
            $(`#${key}`).text(new BillData(billData[key])[key]());
        });
    }

    class BillData
    {
        constructor(data) {
            this.data = data;

            this.taxes = taxDocuments;
        }

        getTaxDocument(value) {
            return this.taxes[value] ? `${value} - ${this.taxes[value]}` : false;
        }

        iva() {
            return `${this.data}%`;
        }
        
        fchemis() {
            return moment(this.data).format('DD-MM-YYYY');
        }
        
        mnttotal() {
            return new Intl.NumberFormat("es-CL", { style: 'currency', currency: 'CLP' })
                            .format(this.data);
        }
        
        folio() {

            return this.data;
        }
        
        tipodte() {
            return this.getTaxDocument(this.data) || this.data;
        }

        token() { }
    }

    //
    //
    //  send credit note
    //
    //
    $("#issue-cancel-document").on('click', function(event) {
        event.preventDefault();
        // disable emitir button
        $(this).text("Emitiendo...");
        $(this).attr("disabled", true);

        issueElectronicNote();
        // url = $("#cancel-bill-form").attr('action');

        // new_url = url.replace(previousTaxToken, currentTaxToken);

        // $("#cancel-bill-form").attr('action', new_url);

        // previousTaxToken = currentTaxToken;

        // // send form
        // $("#cancel-bill-form").submit();

    });

    async function issueElectronicNote()
    {
        // issue document
        // reload the page on success
        // warning on failure
        issue(currentTaxToken).then(response => {
            if (response.status >= 400) {
                alert(response.message);

                return;
            }
            console.log('by-pass if (response.status >= 400) {');
            console.log(response.message);

            // window.location = "/invoices/issued";
        }).catch(error => {
            alert(error.responseJSON.message);
        });

        enableIssueButton(true);
    }

    function enableIssueButton(enable = false)
    {
        let button = $("#issue-cancel-document");

        if (enable) {
            return changeTextAndStatusToButton(button, "Emitir", false);
        }    

        changeTextAndStatusToButton(button, "Emitiendo...", true);
    }

    /**
     *
     */
    function issue(token)
    {
        return new Promise((resolve, reject) => {
            $.ajax({                        
                type: "POST",
                url: `/tax-documents/${token}/cancel`,
            }).done(successResponse => resolve(successResponse))
              .fail(errorResponse => reject(errorResponse));
        });
    }

    /** 
     *  the first one is the button itself
     *  the second is the text inside the button
     *  the last is the status of the button (true means is disabled) 
     *  
     *  @return  void
     */
    function changeTextAndStatusToButton(button, text, isDisabled)
    {
        button.text(text);
        
        button.prop('disabled', isDisabled);
    }
</script>


@endsection
