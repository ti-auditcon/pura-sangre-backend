@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-xl-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">
                    <h4>Comparación de Ingresos</h4>
                </div>
                
                <div class="iboox-tools">
                    <div class="d-flex flex-row">
                        <input autocomplete="off" id="date-input" type="text" class="form-control">

                        <button id="compare-button" class="btn btn-success">Buscar</button>
                    </div>
                </div>
            </div>

            <div class="ibox-body">
                <table id="plans-summary-table" class="table table-hover">
                    <thead class="thead-default">
                        <tr>
                            <th width="6%">Día</th>
                            <th width="8%">Fechas</th>
                            <th width="10%">Usuarios Activos del día</th>
                            <th width="10%">Cantidad de Reservas del día</th>
                            <th width="15%">Reservaciones acumuladas a la Fecha</th>
                            <th width="12%">Ingresos del Día</th>
                            <th width="13%">Ingresos Acumulados a la Fecha</th>
                            <th width="10%">Cantidad Planes vendidos en el Día</th>
                            <th width="10%">Acumulado de Planes vendidos</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection



@section('scripts') {{-- scripts para esta vista --}}

<script src="{{ asset('js/moment.min.js') }}"></script>

<script src="{{ asset('js/datatables.min.js') }}"></script>

<script>
var today = moment().format('DD-MM-YYYY');

$('#date-input').datepicker({
    format: "dd-mm-yyyy",
    weekStart: 1,
    startDate: "03-03-2008",
    endDate: today,
    maxViewMode: 3,
    todayBtn: "linked",
    language: "es",
    autoclose: true,
    todayHighlight: true
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    var date = $('#date-input').val() ? $('#date-input').val() : today;

    $('#compare-button').click(function () {
        table.ajax.reload();
    });

    var table = $('#plans-summary-table').DataTable({
        "processing": true,
        // "serverSide": true,
        "ajax": {
            "url": "<?= route('data-plans-compare') ?>",
            "dataType": "json",
            "type": "POST",
            "data" : function( d ) {
                d.date = $('#date-input').val() ?
                         $('#date-input').val() :
                         moment().format('DD-MM-YYYY');
            },
        },
        "dom": '<"top">rt<"bottom"><"clear">',
        "lengthChange": false,
        "language": {
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
        "columns":[
            { "data": "day" },
            { "data": "date" },
            { "data": "active_users_day" },
            { "data": "reservations_day" }, 
            { "data": "cumulative_reservations" },
            { "data": "day_incomes" },
            { "data": "cumulative_incomes" },
            { "data": "day_plans_sold" },
            { "data": "cumulative_plans_sold" }
        ],
    });
});

</script>

@endsection