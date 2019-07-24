@extends('layouts.app')

@section('sidebar')
  @include('layouts.sidebar',['page'=>'home'])
@endsection

@section('content')
   <div class="row justify-content-center">
      <div class="col-12 col-md-5">

        <div class="ibox">
          <div class="ibox-head">
            <div class="ibox-title">Clases de hoy</div>
          </div>
          <div class="ibox-body" >
            <div id="calendar"></div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-7">
        <div class="row">
          <div class=" col-md-6 col-sm-12">
            <div class="ibox">
               <div class="ibox-head">
                  <div class="ibox-title">Actividad de alumnos <span style="text-transform: capitalize;">{{today()->formatLocalized('%B')}}</span></div>
               </div>
                  <div class="ibox-body">
                     <canvas id="renewal-chart"></canvas>
                  </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-12 ">
             <div class="ibox">
                 <div class="ibox-head">
                    <div class="ibox-title">Crossfiteros activos del box</div>
                    <label id="my-label"></label>
                 </div>
                  <div class="ibox-body">
                     <canvas id="gender-chart" ></canvas>
                  </div>
              </div>
          </div>

        </div>
        <div class="row">
          <div class="col-12">
             <div class="ibox">
                  <div class="ibox-head">
                  <div class="ibox-title">Planes vendidos hoy vs. mes <span style="text-transform: capitalize;">{{today()->formatLocalized('%B')}}</span></div>
               </div>
                  <div class="ibox-body" id="incomes-summary">
                  </div>
              </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="ibox">
               <div class="ibox-head">
                  <div class="ibox-title">Alumnos con planes próximos a vencer</div>
               </div>
               <div class="ibox-body">
                  <div class="table-responsive">
                     <table id="students-table" class="table table-hover">
                        <thead class="thead-default thead-lg">
                           <tr>
                              <th>Alumno</th>
                              <th>Plan</th>
                              <th>Vencimiento</th>
                              <th>Teléfono</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($plan_users->take(5) as $pu)
                           <tr>
                              <td><a href="{{ url('/users/'.$pu['user_id']) }}">{{ $pu['alumno'] }}</a></td>
                              <td>{{ $pu['plan'] }}</td>
                              <td>{{ $pu['fecha_termino'] }}</td>
                              <td>{{ $pu['telefono'] }}</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <div class="ibox">
               <div class="ibox-head">
                  <div class="ibox-title">Alumnos recientemente inactivos</div>
               </div>
               <div class="ibox-body">
                   <table id="inactive-users-table" class="table table-hover">
                      <thead class="thead-default">
                         <tr>
                            <tr>
                               <th>Alumno</th>
                               <th>Plan</th>
                               <th>Fecha</th>
                               <th>N° Teléfono</th>
                            </tr>
                         </tr>
                      </thead>
                      <tbody>
                       {{--   @foreach ($expired_plans->take(5) as $expired_plan)
                           <tr>
                              <td><a href="{{url('/users/'.$expired_plan['user_id'])}}">{{$expired_plan['alumno']}}</a></td>
                              <td>{{$expired_plan['plan']}}</td>
                              <td>{{$expired_plan['fecha_termino']}}</td>
                              <td>{{$expired_plan['telefono']}}</td>
                           </tr>
                         @endforeach --}}
                      </tbody>
                   </table>
               </div>
             </div>
          </div>
        </div>
      </div>


   </div>

@endsection

@section('css') {{-- stylesheet para esta vista --}}
  <link href="{{asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet" />
	<link href="{{asset('css/fullcalendar.min.css')}}" rel="stylesheet" />
  <style>
    .fc-axis.fc-widget-header{width:59px !important;}
    .fc-axis.fc-widget-content{width:51px !important;}
    .fc-scroller.fc-time-grid-container{height:100% !important;}
    .fc-time-grid.fc-event-container {left:10px}
  </style>
@endsection



@section('scripts') {{-- scripts para esta vista --}}
	{{--  datatable --}}
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
    
    <script src="{{ asset('js/moment.min.js') }}"></script>
	
    <script src="{{ asset('js/fullcalendar/fullcalendar.min.js') }}"></script>
    
    <script src="{{ asset('js/fullcalendar/lang/es.js') }}"></script>

  <script defer>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
          header: {
              right:  'today',
          },
          minTime: "07:00:00",
          maxTime: "22:00:00",
          editable: false,

          defaultView: 'agendaDay',
          // allDaySlot: false,
          slotDuration: '00:30:00',
          slotLabelFormat: 'h(:mm)a',
          hiddenDays: [0],
          eventColor: '#4c6c8b',
          eventRender: function( event, element, view ) {
            element.find('.fc-time').append('<div> reservas: ' +event.reservation_count+'/'+event.quota+'</div> ');
          },
          viewRender: function (view, element,start,end) {
             // var b = $('#calendar').fullCalendar('getDate');
             $('#calendar').fullCalendar( 'removeEventSources');

            $('#calendar').fullCalendar( 'addEventSource',
             {
               url: '/get-clases?datestart='+moment().startOf('day').format('Y-M-D')+'&dateend='+moment().startOf('day').format('Y-M-D'), // use the `url` property
               textColor: 'black'  // an option!
             }
            );
            $('#calendar').fullCalendar( 'addEventSource',
              {
                url: '/get-wods?datestart='+moment().startOf('day').format('Y-M-D')+'&dateend='+moment().startOf('day').format('Y-M-D'), // use the `url` property
                color: 'yellow',    // an option!
                textColor: 'black'  // an option!
              }
            );
          },
        });
    });
  </script>

  {{-- <script src="{{ asset('js/Chart.min.js') }}"></script> --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.1/dist/Chart.min.js"></script>

<script>
    var uri = "{{ url('withoutrenewal') }}";
    
    $(document).ready(function(){
        $.get(uri, function(respuesta){
            var chartdata = {
                labels: ["Activos", "Inactivos", "Prueba"],
                datasets: [{
                    data: [
                        respuesta.actives,
                        respuesta.inactives,
                        respuesta.tests
                    ],
                    backgroundColor: ["#009900", "#9EB1D1", "#F49D36"]
                }]
            };

            var doughnutOptions = {
                responsive: true
            };

            var ctx4 = document.getElementById("renewal-chart").getContext("2d");
            new Chart(ctx4, {type: 'doughnut', data: chartdata, options:doughnutOptions});
        });
    });
  </script>

<script>
    var url = "{{ url('withoutrenewal') }}";
    
    $(document).ready(function(){
        $.get(url, function(respuesta){
            var chartdata = {
                labels: ["Mujeres", "Hombres"],
                datasets: [{
                    data: [
                        respuesta.mujeres, 
                        respuesta.hombres
                    ],
                    backgroundColor: ["#E74694", "#1F87EF"]
                }]
            };
            
            var doughnutOptions = {
                responsive: true,
                rotation: -Math.PI,
                cutoutPercentage: 30,
                circumference: Math.PI,
                legend: {
                    position: 'right'
                }
            };

            var ctx4 = document.getElementById("gender-chart").getContext("2d");
            
            new Chart(ctx4, {type: 'doughnut', data: chartdata, options:doughnutOptions});
      
            var crossfiteros = respuesta.mujeres + respuesta.hombres;
      
            $('#my-label').html(crossfiteros + " crossfiteros");
        });
    });
</script>

  <script>

$(document).ready(function(){
      var op = "";
      $.ajax({
         type:'get',
         url: '/incomes-summary/',
         success: function(resp){
            // console.log(resp);
            var obj = JSON.parse(resp);
            op+='<table class="table table-striped">';
            op+='<tr><th width="40%">Período</th><th width="15%">Cantidad</th><th width="45%">Ingresos</th></tr>';
            for(var i=0;i<obj.length;i++){
               op += '<tr>';
               op += '<td>'+obj[i].periodo+'</td>'+
                     '<td>'+obj[i].cantidad+'</td>'+
                     '<td>'+obj[i].ingresos+'</td></tr>';
            }
            op+='</table>';
            $('#incomes-summary').html(op);
         },
         error: function(){
            console.log("Error Occurred");
         }
      });

});


</script>

<script src="{{ asset('js/datatables.min.js') }}"></script>

<script>
    $('#inactive-users-table').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [[ 0, "desc" ]],
        "ajax": {
            "url": "<?= route('expiredplans') ?>",
            "dataType": "json",
            "type": "POST",
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
                "first":    "Primero",
                "last":     "último",
                "next":     "Siguiente",
                "previous": "Anterior"
            },
        },
        "columns":[
            { "data": "first_name" },
            { "data": "plan" },
            { "data": "date" }, 
            { "data": "phone" }
        ]
    });
</script> 

@endsection
