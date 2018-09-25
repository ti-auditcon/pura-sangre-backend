@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="ibox">
          <div class="ibox-head">
              <div class="ibox-title">Clases</div>

          </div>
          <div class="ibox-body">
              <div id="calendar"></div>
          </div>
      </div>
    </div>

  </div>

  {{-- <div class="modal fade" id="clase-resume" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog ">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Clase</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="row">
              <h4>Reservas</h4>
              <div class="easypie" data-percent="84" data-bar-color="#5c6bc0" data-size="80" data-line-width="8">
                 <span class="easypie-data h4 font-strong">20/25</span>

              </div>
            </div>
            <div class="row">
              <a href="" class="btn btn-info" >Ver clase</a>
            </div>

            <div class="row">
              <h4>Alumnos</h4>
            </div>

          </div>





        </div>

      </div>
    </div>
  </div> --}}

@endsection


@section('css') {{-- stylesheet para esta vista --}}
  <link href="{{asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet" />
	<link href="{{asset('css/fullcalendar.min.css')}}" rel="stylesheet" />
  <link href="{{asset('css/bootstrap-clockpicker.min.css')}}" rel="stylesheet" />
  <link href="{{asset('css/multi-select.css')}}" rel="stylesheet" />
  <style>
    .fc-axis.fc-widget-header{width:59px !important;}
    .fc-axis.fc-widget-content{width:51px !important;}
    .fc-scroller.fc-time-grid-container{height:100% !important;}
    .fc-time-grid.fc-event-container {left:10px}
  </style>
@endsection



@section('scripts') {{-- scripts para esta vista --}}
	{{--  full caslendar --}}
  <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('js/moment.min.js') }}"></script>
	<script src="{{ asset('js/fullcalendar.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap-clockpicker.min.js') }}"></script>
  <script src="{{ asset('js/jquery.multi-select.js') }}"></script>
  <script src="{{ asset('js/jquery.easypiechart.min') }}"></script>


  <script defer>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
          header: {
              right:  'today prev,next',
          },
          minTime: "07:00:00",
          maxTime: "21:00:00",
          events: {!! $clases !!},
          editable: false,
          defaultView: 'agendaWeek',
          // allDaySlot: false,
          slotDuration: '00:30:00',
          slotLabelFormat: 'h(:mm)a',
          hiddenDays: [0],
          eventColor: '#4c6c8b',
          eventClick: function(calEvent, jsEvent, view) {
            $('#clase-resume').modal();
          },

        });

    });
  </script>


@endsection
