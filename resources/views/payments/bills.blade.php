@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar', ['page' => 'payments' ])
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Documentos tributarios electrónicos recibidos</div>
                


                <div class="tools">
                    {{-- <a class="btn btn-info btn-labeled btn-labeled-left btn-icon"
                       style="display: inline-block;" href="{{ route('bills.export') }}"
                    >
                        <span class="btn-label"><i class="la la-cloud-download"></i></span>
                        
                        Exportar pagos
                    </a> --}}
                </div>
            </div>
            <div class="ibox-body">

                    <table id="dtes-table" class="table table-hover">
                        <thead class="thead-default">
                            <tr>
                                <th width="30%">Nombre</th>
                                <th width="20%">Tipo de DTE</th>
                                <th width="15%">Monto toral</th>
                                <th width="10%">Fecha emisión</th>
                                <th width="10%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
            </div>
        </div>

    </div>

</div>

@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">    
@endsection

@section('scripts') {{-- scripts para esta vista --}}
{{--  datatable --}}
<script src="{{ asset('js/datatables.min.js') }}"></script>

{{-- To formatting dates --}}
<script src="{{ asset('js/moment.min.js') }}"></script>

<script>
    const dteNames = @json(App\Models\Invoicing\DTE::allDTES());
    const base_url = @json(url('/'));
    let current_page = 1;

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $(document).ready(function() {
        $('#dtes-table').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "info": false,
            "paging": false,
            "language": {
                "loadingRecords": "Consultando datos al SII...",
                "processing": "Consultando datos al SII...",
                "lengthMenu": "Mostrar _MENU_ elementos",
                "zeroRecords": "Sin resultados",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "Sin resultados",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Filtrar:",
                "paginate": {
                    "first":      "Primero",
                    "last":       "último",
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },
            },
            // "ajax": '../dtes.txt',
            "ajax": {
                "url": "invoices/dtes",
                "type": "GET",
            },
            "columns": [
                { "data": "RznSoc" },
                { "data": "TipoDTE",
                    "render": function (data, other, row) {
                        return dteNames[data];
                    }
                },
                { "data": "MntTotal",
                    "render": function(data, other, row) {
                        if (data > 0) {
                            return new Intl.NumberFormat("es-CL", {style: 'currency', currency: 'CLP'})
                                            .format(data);
                        }
                        return 'sin valor';
                    }    
                }, 
                { "data": "FchEmis",
                    "render": function(data, other, row) {
                        return data ? moment(data).format("DD-MM-YYYY") : 'sin fecha';
                    } },
                {
                    "data": "actions",
                    "render": function(data, other, row) {
                        return `<button class="dte-link btn btn-success text-white"
                                    data-rut="${row.RUTEmisor}"
                                    data-dv="${row.DV}"
                                    data-type="${row.TipoDTE}"
                                    data-document_number="${row.Folio}"
                                >
                                Soliciar PDF
                                </button>`
                    }
                }
            ],
            "drawCallback": function( settings ) {
                $(".dte-link").click(function (action) {
                    managePDFRequest($(this));
                });
            }
        } );
    } );

    /** */
    async function managePDFRequest(event)
    {
        changeButtonToRequesting(event);        

        let parametersForPDF = transformDataParameters(event);
        requestPdfDataFromSii(parametersForPDF).then(success => {
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
            "rut": field.data('rut'),
            "dv": field.data('dv'),
            "type": field.data('type'),
            "document_number": field.data('document_number')
        }
    }

    /**
     *
     */
    function requestPdfDataFromSii(dte)
    {
        return new Promise((resolve, reject) => {
            $.ajax({                        
                type: "POST",                 
                url: "dte/get-pdf",                     
                data: {
                    rut: dte.rut, dv: dte.dv, type: dte.type,
                    document_number: dte.document_number
                }, 
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
        window.open(fileURL);
    }
</script>

@endsection
