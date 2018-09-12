@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="ibox">
          <div class="ibox-head">
              <div class="ibox-title">Horarios</div>

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

{{-- modal para agrergar horario  --}}
        <div class="modal fade" id="blockadd" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
          <div class="modal-dialog ">
            {{Form::open([])}}
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Nuevo horario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <div class="input-group clockpicker">
                      <label>Inicio:</label>
                      <input type="text" class="form-control" value="" name="start">
                      <span class="input-group-addon">
                          <span class="la la-clock-o"></span>
                      </span>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group clockpicker">
                      <label>Termino:</label>
                      <input type="text" class="form-control" value="" name="start">
                      <span class="input-group-addon">
                          <span class="la la-clock-o"></span>
                      </span>
                  </div>
                </div>



              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
            {{Form::close()}}
          </div>
        </div>

@endsection


@section('css') {{-- stylesheet para esta vista --}}
  <link href="{{asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet" />
	<link href="{{asset('css/fullcalendar.min.css')}}" rel="stylesheet" />
  <link href="{{asset('css/bootstrap-clockpicker.min.css')}}" rel="stylesheet" />
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
  <script src="{{ asset('js/bootstrap-clockpicker.min.js') }}"></script>

  <script defer>
  $(document).ready(function() {
    $('.clockpicker').clockpicker({autoclose: true});
    $('#calendar').fullCalendar({
      header: {
          right:  'agendaWeek',
      },
      minTime: "07:00:00",
      maxTime: "21:00:00",
      events: {!! $blocks !!},
      editable: false,
      defaultView: 'agendaWeek',
      // allDaySlot: false,
      slotDuration: '01:00:00',
      slotLabelFormat: 'h(:mm)a',
      hiddenDays: [0],
      eventClick: function(calEvent, jsEvent, view) {
        console.log('eventClick');
      },
      dayClick: function(date, jsEvent, view) {
        console.log('dayClick:'+date.format());

        $('#blockadd').modal();
      },


    });
  });
  </script>


@endsection
