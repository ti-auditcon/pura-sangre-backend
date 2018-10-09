@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="ibox">
          <div class="ibox-head">
              <div class="ibox-title">CALENDAR</div>
              <button class="btn btn-primary btn-rounded btn-air my-3" data-toggle="modal" data-target="#new-event-modal">
                  <span class="btn-icon"><i class="la la-plus"></i>Nueva clase</span>
              </button>
          </div>
          <div class="ibox-body">
              <div id="calendar"></div>
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
          left: 'prev,next',
          center: 'title',
      },
      minTime: "07:00:00",
      maxTime: "21:00:00",
      events:{!!$events!!},
      editable: false,
      defaultView: 'agendaWeek',
      // allDaySlot: false,
      slotDuration: '00:30:00',
      slotLabelFormat: 'h(:mm)a',
      hiddenDays: [0]
    });
  });
  </script>


@endsection
