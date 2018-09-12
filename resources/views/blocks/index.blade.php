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
            {{Form::open(['route'=>'blocks.store'])}}
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
                      <label class="col-sm-2 col-form-label">Inicio:</label>
                      <input type="text" class="form-control" value="" name="start">
                      <span class="input-group-addon">
                          <span class="la la-clock-o"></span>
                      </span>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group clockpicker">
                      <label class="col-sm-2 col-form-label">Termino:</label>
                      <input type="text" class="form-control" value="" name="end">
                      <span class="input-group-addon">
                          <span class="la la-clock-o"></span>
                      </span>
                  </div>
                </div>
                <div class="form-group" id="daycheckbox">
                    <div class="mb-2">
                        <label class="checkbox checkbox-inline">
                            <input type="checkbox" name="day[]" value="1">
                            <span class="input-span"></span>Lunes</label>
                        <label class="checkbox checkbox-inline">
                            <input type="checkbox" name="day[]" value="2">
                            <span class="input-span"></span>Martes</label>
                        <label class="checkbox checkbox-inline">
                            <input type="checkbox" name="day[]" value="3">
                            <span class="input-span"></span>Miercoles</label>
                        <label class="checkbox checkbox-inline">
                            <input type="checkbox" name="day[]" value="4">
                            <span class="input-span"></span>Jueves</label>
                        <label class="checkbox checkbox-inline">
                            <input type="checkbox" name="day[]" value="5">
                            <input type="checkbox" >
                            <span class="input-span"></span>Viernes</label>
                        <label class="checkbox checkbox-inline">
                            <input type="checkbox" name="day[]" value="6">
                            <input type="checkbox" >
                            <span class="input-span"></span>Sabado</label>
                    </div>
                </div>
                <div class="form-group mb-4">

                    <select multiple="multiple" id="plan-select-add" name="plans[]">
                      @foreach (App\Models\Plans\Plan::all() as $plan)
                        <option value="{{$plan->id}}">{{$plan->plan}} {{$plan->plan_period->period}}</option>
                      @endforeach
                    </select>
                </div>

              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar horario</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
            {{Form::close()}}
          </div>
        </div>

        <div class="modal fade" id="blockedit" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog ">

            <div class="modal-content">
              <div class="modal-body">
                <div class="form-group mb-4">

                    <select multiple="multiple" id="plan-select-edit" name="plans[]">
                      @foreach (App\Models\Plans\Plan::all() as $plan)
                        <option value="{{$plan->id}}">{{$plan->plan}} {{$plan->plan_period->period}}</option>
                      @endforeach
                    </select>
                </div>
              </div>
            </div>

          </div>
        </div>

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

  <script defer>
  $(document).ready(function() {
    $('.clockpicker').clockpicker({autoclose: true});

    $('#plan-select-add').multiSelect();
    $('#plan-select-edit').multiSelect();


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


        ids = Object.values(calEvent.plans_id);
        console.log(Object.values(calEvent.plans_id));
        $('#plan-select-edit').multiSelect('deselect_all');
        $('#plan-select-edit').multiSelect('select',ids.map(String));
        $('#blockedit').modal();
      },
      dayClick: function(date, jsEvent, view) {

        console.log('dayClick:'+date.format());
        $('#plan-select-add').multiSelect('deselect_all');
        $('#blockadd input[name="start"]').val(date.format('H:mm'));
        $('#blockadd input[name="end"]').val(date.add(1, 'hours').format('H:mm'));
        $('#daycheckbox input').prop('checked', false);
        $('#daycheckbox input[value="'+date.day()+'"]').prop('checked', true);
        $('#blockadd').modal();
        console.log('dow:'+date.day());
      },


    });
  });
  </script>


@endsection
