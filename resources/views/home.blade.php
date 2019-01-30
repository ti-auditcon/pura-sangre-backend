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
                        <div class="ibox-title">Crossfiteros del box</div>
                        <label id="my-label"></label>
                     </div>
                      <div class="ibox-body">
                         <canvas id="gender-chart" ></canvas>
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
                              <td><a href="{{url('/users/'.$pu['user_id'])}}">{{$pu['alumno']}}</a></td>
                              <td>{{$pu['plan']}}</td>
                              <td>{{$pu['fecha_termino']}}</td>
                              <td>{{$pu['telefono']}}</td>
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
                   <table id="students-table" class="table table-hover">
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
                         @foreach ($expired_plans->take(5) as $expired_plan)
                         <tr>
                           {{-- {{dd($expired_plan['alumno'])}} --}}
                            <td><a href="{{url('/users/'.$expired_plan['user_id'])}}">{{$expired_plan['alumno']}}</a></td>
                            <td>{{$expired_plan['plan']}}</td>
                            <td>{{$expired_plan['fecha_termino']}}</td>
                            <td>{{$expired_plan['telefono']}}</td>
                         </tr>
                         @endforeach
                      </tbody>
                   </table>
               </div>
             </div>
          </div>

        </div>


      </div>

      {{-- <div class="col-4">
         <div class="ibox">
            <div class="ibox-head">
               <div class="ibox-title">Alumnos activos e inactivos de {{today()->formatLocalized('%B')}} de {{today()->formatLocalized('%Y')}}</div>
            </div>
               <div class="ibox-body">
                  <canvas id="renewal-chart" height="280" width="600"></canvas>
               </div>
         </div>

        <div class="ibox">
            <div class="ibox-head">
               <div class="ibox-title">Crossfiteros del box</div>
               <label id="my-label"></label>
            </div>
             <div class="ibox-body">
                <canvas id="gender-chart" height="280" width="600"></canvas>
             </div>
         </div>

      </div> --}}

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
             var b = $('#calendar').fullCalendar('getDate');
             console.log(b.startOf('week').format('Y-M-D'));
             $('#calendar').fullCalendar( 'removeEventSources');
             //alert(b.format('Y-M-D'));

            $('#calendar').fullCalendar( 'addEventSource',
             {
               url: '/get-clases?datestart='+b.startOf('week').format('Y-M-D')+'&dateend='+b.endOf('week').format('Y-M-D'), // use the `url` property
               textColor: 'black'  // an option!
             }
            );
            $('#calendar').fullCalendar( 'addEventSource',
              {
                url: '/get-wods?datestart='+b.startOf('week').format('Y-M-D')+'&dateend='+b.endOf('week').format('Y-M-D'), // use the `url` property
                color: 'yellow',    // an option!
                textColor: 'black'  // an option!
              }
            );
          },
          // eventClick: function(calEvent, jsEvent, view) {
          //   $('#clase-resume').modal();
          // },

        });


    });
  </script>

  <script src="{{ asset('js/Chart.min.js') }}"></script>

  <script>
   var uri = "{{url('withoutrenewal')}}";
   $(document).ready(function(){
      $.get(uri, function(respuesta){
         // console.log(JSON.parse(respuesta).actives);

      var chartdata = {
         labels: ["Activos", "Inactivos"],
         datasets: [{
            data: [JSON.parse(respuesta).actives, JSON.parse(respuesta).inactives],
            backgroundColor: ["#009900", "#9EB1D1"]
         }]
      } ;
       var doughnutOptions = {
              responsive: true
          };

      var ctx4 = document.getElementById("renewal-chart").getContext("2d");
       new Chart(ctx4, {type: 'doughnut', data: chartdata, options:doughnutOptions});


      });
   });
  </script>

   <script>
   var url = "{{url('genders')}}";
   $(document).ready(function(){
      $.get(url, function(respuesta){
      var chartdata = {
         labels: ["Mujeres", "Hombres"],
         datasets: [{
            data: [JSON.parse(respuesta).mujeres, JSON.parse(respuesta).hombres],
            backgroundColor: ["#E74694", "#1F87EF"]
         }]
      } ;
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
      var crossfiteros = JSON.parse(respuesta).mujeres + JSON.parse(respuesta).hombres;
      $('#my-label').html(crossfiteros + " crossfiteros");
      });
   });
  </script>



@endsection
