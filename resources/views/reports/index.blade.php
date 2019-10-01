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
    <div class="col-xl-6">
        <div class="ibox">
            <div class="ibox-body">
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <h3 class="m-0">Cantidad de Planes</h3>
                        <div>Por tipo de Plan a nivel anual</div>
                    </div>
 {{--                    <ul class="nav nav-pills nav-pills-rounded nav-pills-air" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{now()->year}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">{{now()->subYear()->year}}</a>
                        </li>
                    </ul> --}}
                </div>
                <div class="table-responsive">
                    {{-- <div class="tab-content" id="myTabContent"> --}}
                        {{-- <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"> --}}
                            <table id="quantity-plans-table" class="table table-hover">
                                <thead class="thead-default">
                                    <tr>
                                        <th width="50%">Tipo de Plan</th>
                                        <th width="25%">{{ today()->year }}</th>
                                        <th width="25%">{{ today()->subYear()->year }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        {{-- </div> --}}
                    {{-- </}div> --}}
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
    $('#quantity-plans-table').DataTable( {
        "ajax": {
            "url": '{{ route("totalplans") }}',
            "dataType": "json",
            "type": "get",
        },
        "language": {
            "lengthMenu": "Mostrar _MENU_ elementos",
            "zeroRecords": "Sin resultados",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Sin resultados",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Filtrar:",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
        },
        "bLengthChange" : false,
        "bpageLength": false,
        "bPaginate": true,
        "searching": false,
        "bInfo":false,
        "columns": [
            { "data": "plan" },
            { "data": moment().year() },
            { "data": moment().subtract(1, 'years').year() }
        ]
    });
</script>

    <script src="{{ asset('js/Chart.min.js') }}"></script>

    <script src="{{ asset('js/purasangre-js/all-plans-incomes.js') }}"></script>

{{--     //////////////////  TOTAL QUANTITY PLAN ANUAL BY MONTH  ////////////////////////////////// --}}
<script>
var urltwo = "{{url('reports/secondchart')}}";

$(document).ready(function() {
    var Months =  new Array();
    var Quantities = new Array();
    var SubQuantities = new Array();
    
    $.get(urltwo, function(respuesta) {
        respuesta.q_anual.forEach(function(data) {
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
                { label: '2019', borderWidth: 1, borderColor: 'rgba(54, 162, 235, 1)',
                  backgroundColor: 'rgba(54, 162, 235, 1)', data: Quantities,
                },
                { label: '2018', borderWidth: 1, borderColor: 'rgba(180, 178, 180, 0.8)',
                  backgroundColor: 'rgba(180, 178, 180, 0.8)', data: SubQuantities, }
            ]
        };
        var chart_quantity = document.getElementById("quantity-plans").getContext('2d');
        
        var miChart = new Chart(chart_quantity, { type: 'bar', data: chartdata });
    });
});
</script>

<script>
var urlresrvs = "{{url('reports/thirdchart')}}";

$(document).ready(function(){
    var Monthss =  new Array();
    var reservs = new Array();
    var sub_reservs = new Array();
    
    $.get(urlresrvs, function(respuesta) {
        respuesta.rsrvs_anual.forEach(function(data) {
            reservs.push(data);
        });
        respuesta.rsrvs_sub_anual.forEach(function(data){
            sub_reservs.push(data);
        });
        respuesta.months.forEach(function(data) {
            Monthss.push(data);
        });

        var chartdata = {
            labels: Monthss,
            datasets: [{ label: '2019', borderWidth: 1, borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)', data: reservs,
            }, {
            label: '2018', borderWidth: 1, borderColor: 'rgba(180, 178, 180, 0.8)',
                backgroundColor: 'rgba(180, 178, 180, 0.8)', data: sub_reservs, }]
        };
        var chart_quantity = document.getElementById("quantity-rsrvs").getContext('2d');
        
        var miChart = new Chart(chart_quantity, {
            type: 'bar', data: chartdata, 
        });
    });
});
</script>


@endsection
{{-- <script>
  // Bar Chart example
$(document).ready(function() {
  //////////////////TOTAL SUMMARY//////////////////////////////////////////////////////
   var barData = {
      labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo",
               "Junio", "Julio", "Agosto", "septiembre",
               "Octubre", "Noviembre", "Diciembre"],
      datasets: [
         {
            label: "{!!(string)date("Y",strtotime("-1 year"))!!}",
            backgroundColor:'#DADDE0',
            data:
            [
               @for($i = 1; $i <= 12; $i++)
               {!!$summaries->where('month',$i)->where('year',date("Y",strtotime("-1 year")))->sum('amount'); !!},
               @endfor
            ]
         },
         {
            label: "{!!(string)date("Y")!!}",
            backgroundColor: '#18C5A9',
            borderColor: "#fff",
            data:
            [
               @for($i = 1; $i <= 12; $i++)
               {!!$summaries->where('month',$i)->where('year',date("Y"))->sum('amount'); !!},
               @endfor
            ]
         }
      ]
   };
   var barOptions = {
      responsive: true,
      tooltips: {
         callbacks: {
            label: function(tooltipItem, data) {
               var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';
               var label = tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
               return datasetLabel + ': ' + label;
            }
         }
      },
      scales: {
         yAxes: [{
            ticks: {
               // Include a dollar sign in the ticks
               callback: function(value, index, values) {
                  return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
               }
            }
         }]
      }
   };
   var ctx = document.getElementById("total_income").getContext("2d");
   new Chart(ctx, {type: 'bar', data: barData, options:barOptions});
  //////////////////END TOTAL SUMMARY/////////////////////////
  ////////////////// ACUMULATIVO //////////////////////////////////////////////////////
   var options_acumulative = {
      responsive: true,
      maintainAspectRatio: false,
   };
   var ctx = document.getElementById("acumulative").getContext("2d");
   new Chart(ctx, {type: 'line', data: data_acomulative, options:options_acumulative});
});
</script> --}}
