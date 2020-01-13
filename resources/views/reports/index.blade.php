@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-xl-6">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Total de Ingresos de todos los planes</div>

                <div class="ibox-tools">
                    <form action="{{ route('incomes.calibrate') }}" method="POST" id="chart-calibrate">
                        @csrf
                    </form>
                    
                        <button class="form-control" type="button" id="charts-recalculate">
                            Ajustar Gráficos
                        </button>
                </div>
            </div>
            <div class="ibox-body">
                <canvas id="all_plans_incomes" height="280" width="600"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-6">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">N° de planes vendidos por Mes</div>
            </div>

            <div class="ibox-body">
                <canvas id="quantity-plans" height="280" width="600"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-6">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Cantidad de reservas por mes en el año</div>
            </div>

            <div class="ibox-body">
                <canvas id="quantity-rsrvs" height="280" width="600"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-12">
        <div class="ibox">
            <div class="ibox-body">
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <h3 class="m-0">Cantidad de Planes por tipo año {{ today()->formatLocalized('%Y') }}</h3>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table id="plans-type-table" class="table table-hover">
                        <thead class="thead-default">
                            <tr>
                                <th width="16%">Tipo de Plan</th>
                                
                                <th width="7%">Enero</th>
                                
                                <th width="7%">Febrero</th>
                                
                                <th width="7%">Marzo</th>
                                
                                <th width="7%">Abril</th>
                                
                                <th width="7%">Mayo</th>
                                
                                <th width="7%">Junio</th>
                                
                                <th width="7%">Julio</th>
                                
                                <th width="7%">Agosto</th>
                                
                                <th width="7%">Septiembre</th>
                                
                                <th width="7%">Octubre</th>
                                
                                <th width="7%">Noviembre</th>
                                
                                <th width="7%">Diciembre</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection



@section('scripts') {{-- scripts para esta vista --}}

    <script src="{{ asset('js/datatables.min.js') }}"></script>

    <script src="{{ asset('js/moment.min.js') }}"></script>

<script>
    // var uno = #93d5ed;
    // var 50 = #45a5f5;
    // var color 75 = #4285f4;
    // var 100 = #2f5ec4;
    // var 0 = #c9c9c9

    // console.log(data.response);
    // 
    $(document).ready (function() {
        $.ajax({
            url: "{{ route("plansMonthType") }}",
            success : function(data) {
                var data = JSON.parse(data);

                $('#plans-type-table').DataTable( {
                    data : data.data,
                    columns: [
                        { "data": "plan" },
                        { "data": "Enero" }, 
                        { "data": "Febrero" },
                        { "data": "Marzo" },
                        { "data": "Abril" },
                        { "data": "Mayo" },
                        { "data": "Junio" },
                        { "data": "Julio" },
                        { "data": "Agosto" },
                        { "data": "Septiembre" },
                        { "data": "Octubre" },
                        { "data": "Noviembre" },
                        { "data": "Diciembre" }         
                    ],
                    "language": {
                        "lengthMenu": "Mostrar _MENU_ elementos",
                        "zeroRecords": "Sin resultados",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Sin resultados",
                        "paginate": {
                            "first": "Primero",
                            "last": "Último",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        },
                    },
                    "searching": false,
                    columnDefs: [{
                        targets: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ],
                        createdCell: function (td, cellData, rowData, row, col) {
                            var density = cellData * 100 / data.max;

                            
                            $(td).css("background-color", "#E0E0E0");
                            
                            if (density > 10) {
                                $(td).css("background-color", "#93d5ed");

                                $(td).css('color', 'white');
                            }      

                            if (density > 25) {
                                $(td).css("background-color", "#45a5f5");
                                
                                $(td).css('color', 'white');
                            }      

                            if (density > 50) {
                                $(td).css("background-color", "#4285f4");
                                
                                $(td).css('color', 'white');
                            }      

                            if (density > 75) {
                                $(td).css("background-color", "#2f5ec4");
                                
                                $(td).css('color', 'white');
                            }      
                            
                            $(td).addClass( "text-center" );
                        }
                    }],
                });
            }       
        });
    });
</script>

    <script src="{{ asset('js/Chart.min.js') }}"></script>

    <script src="{{ asset('js/purasangre-js/all-plans-incomes.js') }}"></script>

{{--     //////////////////  TOTAL QUANTITY PLAN ANUAL BY MONTH  ////////////////////////////////// --}}
<script>
var urltwo = "{{ url('reports/secondchart') }}";

$(document).ready(function() {
    var Months =  new Array();
    var Quantities = new Array();
    var SubQuantities = new Array();
    
    $.get(urltwo, function(respuesta) {
        respuesta.q_anual.forEach(function (data) {
            Quantities.push(data);
        });
        
        respuesta.q_sub_anual.forEach(function( data ) {
            SubQuantities.push(data);
        });

        respuesta.months.forEach(function( data ) {
            Months.push(data);
        });
        
        var chartdata = {
            labels: Months,
            datasets: [
                { label: moment().format("YYYY"), borderWidth: 1, borderColor: 'rgba(54, 162, 235, 1)',
                  backgroundColor: 'rgba(54, 162, 235, 1)', data: Quantities,
                },
                { label: moment().subtract(1, 'year').format("YYYY"), borderWidth: 1, borderColor: 'rgba(180, 178, 180, 0.8)',
                  backgroundColor: 'rgba(180, 178, 180, 0.8)', data: SubQuantities, }
            ]
        };
        var chart_quantity = document.getElementById("quantity-plans").getContext('2d');

        var miChart = new Chart(chart_quantity, { type: 'bar', data: chartdata });
    });
});
</script>

<script>
var urlresrvs = "{{ url('reports/thirdchart') }}";

$(document).ready(function(){
    var Monthss =  new Array();
    var reservs = new Array();
    var sub_reservs = new Array();
    
    $.get(urlresrvs, function(respuesta) {
        respuesta.rsrvs_anual.forEach(function(data) {
            reservs.push(data);
        });
        respuesta.rsrvs_sub_anual.forEach(function(data) {
            sub_reservs.push(data);
        });
        respuesta.months.forEach(function(data) {
            Monthss.push(data);
        });

        var chartdata = {
            labels: Monthss,
            datasets: [{ label: moment().format("YYYY"), borderWidth: 1, borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)', data: reservs,
            }, {
            label: moment().subtract(1, 'year').format("YYYY"), borderWidth: 1, borderColor: 'rgba(180, 178, 180, 0.8)',
                backgroundColor: 'rgba(180, 178, 180, 0.8)', data: sub_reservs, }]
        };
        var chart_quantity = document.getElementById("quantity-rsrvs").getContext('2d');
        
        var miChart = new Chart(chart_quantity, {
            type: 'bar', data: chartdata, 
        });
    });
});
</script>

{{-- <script src="{{ asset('js/sweetalert2.8.js') }}"></script> --}}

<script>
    $('#charts-recalculate').click(function(e) {
        e.preventDefault();
        swal({
            title: "Desea RECALCULAR?",
            text: "Esta acción puede tomar unos momentos, dependiendo de la cantidad de ingresos",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
            confirmButtonClass: 'btn-warning',
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            allowOutsideClick: true
        },function() {
            $('#chart-calibrate').submit();
        });
    });
</script>

@endsection