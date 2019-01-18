@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'reports'])
@endsection

@section('content')
  <div class="row">
      <div class="col-md-6">
        <div class="ibox">
          <div class="ibox-head">
            <div class="ibox-title">Total de Ingresos de todos los planes</div>
          </div>
          <div class="ibox-body">
              <canvas id="canvas" height="280" width="600"></canvas>
          </div>
        </div>
      </div>

       <div class="col-md-6">
        <div class="ibox">
          <div class="ibox-head">
            <div class="ibox-title">Cantidad total de planes vendidos en el año</div>
          </div>
          <div class="ibox-body">
              <canvas id="quantity-plans" height="280" width="600"></canvas>
          </div>
        </div>
      </div>

      <div class="col-md-6">
          <div class="ibox">
        <div class="ibox-head">
          <div class="ibox-title">
            Cantidad de alumnos por Plan
          </div>
        </div>
        <div class="ibox-body pagos-body">
          <div class="table-responsive">
            <table id="quantity-plans-table" class="table table-hover">
              <thead class="thead-default">
                <tr>
                  <th width="60%">Tipo de Plan</th>
                  <th width="40%">Total de Alumnos</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      </div>


  </div>
@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection



@section('scripts') {{-- scripts para esta vista --}}

 {{--  datatable --}}
  <script src="{{ asset('js/datatables.min.js') }}"></script>
  <script>

      $('#quantity-plans-table').DataTable({
         "processing": true,
         "serverSide": true,
         "ajax": {
            "url": "<?= route('totalplans') ?>",
            "dataType": "json",
            "type": "GET",
            "data": {"_token": "<?= csrf_token() ?>"}
         },
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
         "columns":[
            {"data": "plan"},
            {"data": "quantity"}, 
         ]
    } );

   </script> 
  {{--  End datatable --}}

<script src="{{ asset('js/Chart.min.js') }}"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script> --}}

{{--     //////////////////  TOTAL INCOMES PLAN ANUAL BY MONTH  VS PAST YEAR  ////////////////////////////////// --}}
  <script>
  var url = "{{url('report/firstchart')}}";
  var meses =  new Array();
  var Prices = new Array();
  var Prices_sub = new Array();
  $(document).ready(function(){
    $.get(url, function(response){
      console.log(response);
      response.anual.forEach(function(data){
          meses.push(data.month);
          Prices.push(data.amount);
      });
      response.anual_sub.forEach(function(data){
          Prices_sub.push(data.amount);
      });
      var barChartData = {
            labels:meses,
            datasets: [{
               label: '2019',
               borderWidth: 3,
               borderColor: 'rgba(54, 162, 235, 1)',
               backgroundColor: 'rgba(54, 162, 235, 1)',
               data: Prices,
               fill: false,
            }, {
               label: '2018',
               borderWidth: 3,
               borderColor: 'rgba(180, 178, 180, 0.6)',
               backgroundColor: 'rgba(180, 178, 180, 0.6)',
               data: Prices_sub,
               fill: false,
            }]
      };
      var ctx = document.getElementById("canvas").getContext('2d');
                 // console.log(ctx);
          var myChart = new Chart(ctx, {
            type: 'line',
            data: barChartData,
            options: {
               responsive: true,
               tooltips: {
                  mode: 'index',
                  callbacks: {
                     label: function(tooltipItem, data) {
                        var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';
                        var label = tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return datasetLabel + ': ' + label;
                     },
                     // Use the footer callback to display the sum of the items showing in the tooltip
                     footer: function(tooltipItems, data) {                      
                        var sum = 0;
                        tooltipItems.forEach(function(tooltipItem) {
                           if (sum == 0){ sum += tooltipItem.yLabel;
                           }else{ sum -= tooltipItem.yLabel; }
                        });
                        return 'Diferencia: ' + sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                     },
                  },
               },
               scales: { yAxes: [{ ticks: {
                        // Include a dollar sign in the ticks
                        callback: function(value, index, values) {
                           return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }}}]}
            }
        });
    });
  });
  </script>

{{--     //////////////////  TOTAL QUANTITY PLAN ANUAL BY MONTH  ////////////////////////////////// --}}
  <script>
   var urltwo = "{{url('report/secondchart')}}";
  $(document).ready(function(){
   var Months =  new Array();
   var Quantities = new Array();
   var SubQuantities = new Array();
    $.get(urltwo, function(respuesta){
      console.log(respuesta);
      respuesta.q_anual.forEach(function(data){
          Months.push(data.month);
          Quantities.push(data.quantity);
      });
      respuesta.q_sub_anual.forEach(function(data){
          SubQuantities.push(data.quantity);
      });

      var chartdata = {
            labels: Months,
            datasets: [{ label: '2019', borderWidth: 1, borderColor: 'rgba(54, 162, 235, 1)',
               backgroundColor: 'rgba(54, 162, 235, 1)', data: Quantities,
            }, {
               label: '2018', borderWidth: 1, borderColor: 'rgba(180, 178, 180, 0.8)',
               backgroundColor: 'rgba(180, 178, 180, 0.8)', data: SubQuantities, }]
      };
      var chart_quantity = document.getElementById("quantity-plans").getContext('2d');
         var miChart = new Chart(chart_quantity, {
            type: 'bar', data: chartdata, });
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

{{-- 

<div class="col-md-6">
      <div class="ibox">
         <div class="ibox-head">
            <div class="ibox-title">Planes</div>
            <div class="ibox-tools">
               <a class="dropdown-toggle" data-toggle="dropdown"><i class="ti-more-alt"></i></a>
            </div>
         </div>
         <div class="ibox-body">
            <div class="h2 m-0">$12,400<sup>.60</sup><i class="ti-stats-up float-right text-success font-40"></i></div>
            <div class="text-muted m-t-5">MONTH INCOME</div>
            <div class="my-4">
               <div class="h4 m-0">220</div>
               <div class="flexbox"><small>Plan Full Mensual</small>
                  <span class="text-success font-12" style="font-size: 12px;"><i class="fa fa-level-up"></i> +24%</span>
               </div>
               <div class="progress mt-1">
                  <div class="progress-bar bg-success" role="progressbar" style="width:52%; height:5px;" aria-valuenow="52" aria-valuemin="0" aria-valuemax="100"></div>
               </div>
            </div>
            <div class="mb-4">
               <div class="h4 m-0">86</div>
               <div class="flexbox"><small>Plan 12 Sesiones Mensual</small>
                  <span class="text-warning font-12" style="font-size: 12px;"><i class="fa fa-level-down"></i> -12%</span>
               </div>
               <div class="progress mt-1">
                  <div class="progress-bar bg-warning" role="progressbar" style="width:45%; height:5px;" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
               </div>
            </div>
         </div>
      </div>
   </div> --}}