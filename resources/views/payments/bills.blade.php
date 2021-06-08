@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar', ['page' => 'payments' ])
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Documentos tributarios electrónicos sdfsd</div>

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

@section('scripts') {{-- scripts para esta vista --}}
{{--  datatable --}}
<script src="{{ asset('js/datatables.min.js') }}"></script>

{{-- To formatting dates --}}
<script src="{{ asset('js/moment.min.js') }}"></script>

<script>
    const dteNames = @json(App\Models\Invoicing\DTE::allDTES());
    const base_url = @json(url('/'));
    console.log(dteNames);
    // dteNames.push([43 => 'Factura exenta'])

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $(document).ready(function() {
        $('#dtes-table').DataTable( {
            "processing": true,
            "serverSide": true,
            "language": {
                "loadingRecords": "Cargando datos...",
                "processing": "Cargando datos...",
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
            "ajax": {
                "url": "invoices/dtes",
                "type": "POST",
            },
            "columns": [
                { "data": "RznSoc" },
                { "data": "TipoDTE",
                    "render": function (data, other, row) {
                        return dteNames[43];
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
                        return `<a class="dte-link" data-rut="${row.RUTEmisor}"
                                    data-type="${row.TipoDTE}"
                                    data-document_number="${row.Folio}"
                                    >Solicitar PDF</a>`
                    }
                }
            ],
            "drawCallback": function( settings ) {
                $(".dte-link").click(function () {
                    let dte_data = transformData($(this));
                    console.log(dte_data);
                    var decoded_pdf = getDteInPdf(dte_data);

                    $('#modal-dte-show').modal('show');
                });
            }
        } );
    } );

    function transformData(field)
    {
        return {
            "rut": field.data('rut'),
            "type": field.data('type'),
            "document_number": field.data('document_number')
        }
    }

    function getDteInPdf(dte)
    {
        $.ajax({                        
            type: "POST",                 
            url: "dte/get-pdf",                     
            data: {
                rut: dte.rut,
                type: dte.type,
                document_number: dte.document_number
            }, 
        }).done(function(response) {
            var byteCharacters = atob(response.data);
            var byteNumbers = new Array(byteCharacters.length);
            for (var i = 0; i < byteCharacters.length; i++) {
            byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            var byteArray = new Uint8Array(byteNumbers);
            var file = new Blob([byteArray], { type: 'application/pdf;base64' });
            var fileURL = URL.createObjectURL(file);
            window.open(fileURL);
        });
    }
</script>

@endsection
