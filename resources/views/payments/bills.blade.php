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
                    <div class="docsPagination">
                        <span class="docsPagination__text">1-30</span>

                        <button class="dteList-button" data-direction="-" disabled="true">
                            <span class="button-wrapper">
                                <
                            </span>
                        </button>
                        <button class="dteList-button" data-direction="+">
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
    let last_page = 1;

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    function getDTEsData() {
        let dteData = {};
        $.ajax({
            type: "GET",
            url: "invoices/dtes",
            cache: false,
            async: false,
        }).done(successResponse => {
            var data = JSON.parse(successResponse);
    
            last_page = data.last_page;
            dteData = data.data;
            return dteData;
        });

        return dteData;
    }

    $(document).ready(function() {
        // let dataDTETable = getDTEsData();
        dtesTable = $('#dtes-table').DataTable({
            "ajax": {
                type: 'GET',
                url: "invoices/dtes?page=" + current_page,
                dataType: 'json',
                error: function (e) {
                    console.log(e);
                    alert(`Se ha detectado un error, si al intentarlo de nuevo, vuelve a salir este mensaje,
                                por favor comuniquese con algun administrador`);
                },
                complete: function(data) {
                    last_page = data.responseJSON.last_page;
                }
            },
            "processing": true,
            "serverSide": false,
            "searching": false,
            "info": false,
            "paging": false,
            "language": {
                "loadingRecords": "Consultando datos al SII...",
                "processing": "Esta consulta puede tomar unos momentos...",
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
        });

        $(document).on("click", '.dteList-button', function (action) {
            let direction = $(this).data('direction');
            console.log(dtesTable);
            console.log(direction);
            if (direction === '+') {
                console.log(current_page, last_page);
                if (current_page === last_page) {
                    return;
                }
                addOneToCurrentPage();

                manageGetDtesToTable();

                
                return;
            }

            if (current_page === 1) {
                return;
            }
            subtractOneToCurrentPage();

            manageGetDtesToTable();
        });
    });

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

{{--  --}}
{{--  --}}
{{--  --}}
{{--  --}}
{{--  --}}
{{--  --}}
{{--  --}}
{{--  --}}

<script>


    function addOneToCurrentPage()
    {
        current_page++;
    }
    
    function subtractOneToCurrentPage() 
    {
        current_page--;
    }

    function manageGetDtesToTable() 
    {
        let resopnseJson = {};

        $.ajax({
            type: "GET",
            url: "invoices/dtes",
            cache: false,
            // async: false,
        }).done(successResponse => {
            dtesTable.ajax.reload();
            console.log('si');
            // var data = JSON.parse(successResponse);
    
            // last_page = data.last_page;
            // dteData = data.data;
            // return dteData;
        }).fail(error => {
            console.log(error)
        });

        // $.ajax({
        //     url: `invoices/dtes?page=${current_page}`,
        //     type: 'GET',
        // }).then(successResponse => {
        //     resopnseJson = JSON.parse(successResponse);
        // }).then(function (data) {
        //     console.log(resopnseJson.data);
        //     dataDTETable = resopnseJson.data;
        //     // console.log('second')
        // }).then(function (result) {
        //     console.log('third')
        //     dtesTable.ajax.reload();
        // }).fail(error => {
        //     console.log(error);
        //     alert(error.message);
        // });
    }
</script>

@endsection
