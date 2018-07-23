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

  <div class="modal fade show" id="wod-modal" tabindex="-1" role="dialog" >
  			  <div class="modal-dialog" role="document">
  			    <div class="modal-content">

  			      <div class="modal-header">
  			        <h5 class="modal-title">Actualizar wod</h5>
  			      </div>
  			      <div class="modal-body">
                <div class="ibox shadow-wide">
                                        <div class="ibox-body text-center">
                                            <h3 class="font-strong">Warm up</h3>

                                            <div class="py-1">
                                              <textarea class="form-control" rows="5">
5 HS Push Ups
15 Pull Ups
25 Push Ups
25 Push Ups
                                              </textarea>
                                            </div>
                                            <h3 class="font-strong">Skills</h3>

                                            <div class="py-1">
                                              <textarea class="form-control" rows="5">
5 HS Push Ups
15 Pull Ups
25 Push Ups
25 Push Ups
                                              </textarea>
                                            </div>
                                            <h3 class="font-strong">WOD</h3>

                                            <div class="py-1">
                                              <textarea class="form-control" rows="5">
5 HS Push Ups
15 Pull Ups
25 Push Ups
25 Push Ups
                                              </textarea>
                                            </div>
                                        </div>
                                    </div>
  			      </div>

  			      <div class="modal-footer">
  			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
  			        <button type="button" class="btn btn-primary" onclick="this.form.submit();">Actualizar wood</button>
  			      </div>
  						</form>
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
      events:{!!$events!!},
      editable: false,
      defaultView: 'agendaWeek',
      // allDaySlot: false,
      slotDuration: '00:30:00',
      slotLabelFormat: 'h(:mm)a',
      hiddenDays: [0],
      eventClick: function(calEvent, jsEvent, view) {

        if(!calEvent.allDay){
          window.location.href = "{{url('/blocks/1')}}";
        }
        else{
          $('#wod-modal').modal('show');
        }

      }
    });
  });
  </script>


@endsection
