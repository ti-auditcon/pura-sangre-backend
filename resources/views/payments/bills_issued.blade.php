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

<script>
    const dteNames = @json(App\Models\Invoicing\TaxDocument:: allTaxDocuments());
    const base_url = @json(url('/'));

    /** */
    async function managePDFRequest(event)
    {
        changeButtonToRequesting(event);        

        let parametersForPDF = transformDataParameters(event);
        requestIssuedPdfDataFromSii(parametersForPDF).then(success => {
            generatePdfFromBase64(success.data);

            changeButtonToCorrectFinishState(event);
        }).catch(error => {
            changeButtonToNormalState(event);
            
            return alert(error.responseJSON.message);
        });
    }

    function changeButtonToRequesting(button)
    {
        button.prop('disabled', true);  // disable button
        
        button.text('Solicitando...');
    }

    function changeButtonToNormalState(button)
    {
        button.text('Solicitar PDF');    //  change text button
        
        button.prop('disabled', false);  //  enable button
    }

    /**
     *  @argument 
     */
    function changeButtonToCorrectFinishState(button)
    {
        button.text('Listo');

        button.prop('disabled', true);  //  disable button
    }

    /**
     *  
     */
    function transformDataParameters(field)
    {
        return {
            "token": field.data('token'),
        }
    }

    /**
     *
     */
    function requestIssuedPdfDataFromSii(dte)
    {
        return new Promise((resolve, reject) => {
            $.ajax({                        
                type: "POST",                 
                url: "/dte/get-issued-pdf",                     
                data: { token: dte.token }, 
            }).done(successResponse => {
                resolve(successResponse);
            }).fail(errorResponse => {
                reject(errorResponse)
            });
        });
    }

    function generatePdfFromBase64(stringBase64)
    {
        var byteCharacters = atob(stringBase64);
        var byteNumbers = new Array(byteCharacters.length);
        for (var i = 0; i < byteCharacters.length; i++) {
            byteNumbers[i] = byteCharacters.charCodeAt(i);
        }
        var byteArray = new Uint8Array(byteNumbers);
        var file = new Blob([byteArray], { type: 'application/pdf;base64' });
        var fileURL = URL.createObjectURL(file);
        fileURL.download = "hola.pdf";
        window.open(fileURL);
    }
</script>


@endsection
