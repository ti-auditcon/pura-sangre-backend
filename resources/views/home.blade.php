@extends('layouts.app')

@section('sidebar')
  @include('layouts.sidebar',['page'=>'home'])
@endsection

@section('content')
   <div class="row justify-content-center">
      <div class="col-4">
         <div class="ibox">
            <div class="ibox-head">
               <div class="ibox-title">Clases de hoy</div>
            </div>
            <div class="ibox-body">
               <div id="calendar"></div>
            </div>
         </div>
      </div>
      <div class="col-4">
         <div class="ibox">
            <div class="ibox-head">
               <div class="ibox-title">Alumnos con planes próximos a vencer</div>
            </div>
            <div class="ibox-body">
               {{-- <div class="ibox-fullwidth-block"> --}}
                  <table id="students-table" class="table table-hover">
                     <thead class="thead-default">
                        <tr>
                          <th>Alumno</th>
                          <th>Plan</th>
                          <th>Vence en</th>
                          <th>Acciones</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach (App\Models\Plans\PlanUser::all()->where('plan_status_id', 1)->where('finish_date','>=', now())->sortBy('finish_date')->take(5) as $pu)
                          <tr>
                            <td>{{$pu->user->first_name}} {{$pu->user->last_name}}</td>
                            <td>{{$pu->plan->plan}}</td>
                            <td> {{ $pu->finish_date->diffForHumans() }}</td>
                            <td></td>
                          </tr>
                        @endforeach
                     </tbody>
                  </table>
               {{-- </div> --}}
            </div>
         </div>
         <div class="ibox">
            <div class="ibox-head">
               <div class="ibox-title">Alumnos recientemente inactivos</div>
            </div>
            <div class="ibox-body">
               {{-- <div class="ibox-fullwidth-block"> --}}
                  <table id="students-table" class="table table-hover">
                     <thead class="thead-default">
                        <tr>
                           <tr>
                              <th>Alumno</th>
                              <th>Plan</th>
                              <th>Desde el</th>
                              <th>N° Teléfono</th>
                           </tr>
                        </tr>
                     </thead>
                     <tbody>

                     </tbody>
                  </table>
               {{-- </div> --}}
            </div>
         </div>
      </div>
      <div class="col-4">
         <div class="ibox">
            <div class="ibox-head">
               <div class="ibox-title">Reporte de Julio</div>
            </div>
            <div class="ibox-body">
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
	<script src="{{ asset('js/fullcalendar.min.js') }}"></script>

  <script>
  $(document).ready(function() {
    $('#calendar').fullCalendar({
      header: {
          left: 'prev,next today',
          center: 'title',
      },
      minTime: "07:00:00",
      maxTime: "21:00:00",
      events:{!!json_encode(App\Models\Clases\Clase::all())!!},
      editable: false,
      defaultView: 'agendaDay',
      // allDaySlot: false,
      slotDuration: '00:30:00',
      slotLabelFormat: 'h(:mm)a',
      hiddenDays: [0],
      eventColor: '#4c6c8b',
      eventRender: function( event, element, view ) {
        element.find('.fc-title').append('<span > '+event.reservation_count+'/25</span> ');
      },
    });
  });
  </script>


@endsection
