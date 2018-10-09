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
            <input type="text" hidden class="form-control" value="" name="date">

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
                <div class="form-group mb-4">
                  <select multiple="multiple" id="plan-select-add" name="plans[]">
                    @foreach (App\Models\Plans\Plan::all() as $plan)
                      <option value="{{$plan->id}}">{{$plan->plan}} {{$plan->plan_period->period}}</option>
                    @endforeach
                  </select>
                </div>

                 <div class="form-group mb-12">
                    <label class="col-sm-2 col-form-label">Profesor:</label>
                    <input type="text" class="form-control" value="" name="profesor_id">
                    <span class="input-group-addon">
                    </span>
                  </div>

                <div class="form-group mb-12">
                  <label class="radio radio-grey radio-primary">
                    <input id="recurrent" type="radio" name="repetition" value="multiple" checked><span class="input-span"></span>Recurrente
                  </label>
                    <label class="radio radio-grey radio-primary">
                    <input id="unique" type="radio" name="repetition" value="unique"><span class="input-span"></span>Unico
                  </label>
                </div>

                <div class="tab-content">
                  <div id="recurrent-tab" >
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
                  </div>
                  <div id="unique-tab"  >
                    <div class="form-group mb-12">
                      <div class="input-group date ">

                          <input type="text" class="form-control" value="" name="date">
                          <span class="input-group-addon">
                              <span class="la la-clock-o"></span>
                          </span>
                      </div>
                    </div>
                  </div>
                </div>



              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary"  onClick="this.disabled=true; this.value='Guardando…';this.form.submit();">Guardar horario</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
            {{Form::close()}}
          </div>
        </div>

        <div class="modal fade" id="blockedit" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog ">

            <div class="modal-content">

              <div class="modal-header">
                <h5 class="modal-title">Editars horario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group mb-4">
                  {{Form::open(['route' => ['blocks.update', 1 ],'method' => 'put', 'id' => 'block-update'])}}
                    <select multiple="multiple" id="plan-select-edit" name="plans[]">
                      @foreach (App\Models\Plans\Plan::all() as $plan)
                        <option value="{{$plan->id}}">{{$plan->plan}} {{$plan->plan_period->period}}</option>
                      @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary" onClick="this.disabled=true; this.value='Editando…';this.form.submit(); ">Editar planes</button>
                  {{Form::close()}}
                </div>
                <div class="form-group mb-4">
                  {{Form::open(['route' => ['blocks.destroy', 1 ],'method' => 'delete' , 'id' => 'block-delete'])}}
                    Eliminar la clase? </br>
                    <button  class ="btn btn-danger" onClick="this.disabled=true; this.value='Eliminando…';this.form.submit();" >Eliminar</button>
                  {{Form::close()}}
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
    .datepicker {z-index: 1151 !important;}
    /*Date picker container*/ bs-datepicker-container { z-index: 3000; }
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
    $('#unique-tab .form-group').datepicker({
      format: 'dd/mm/yyyy',
      todayBtn: "linked",
      keyboardNavigation: false,
      forceParse: false,
      calendarWeeks: true,
      autoclose: true
    });

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
      slotDuration: '00:30:00',
      slotLabelFormat: 'h(:mm)a',
      hiddenDays: [0],
      eventColor: '#4c6c8b',
      eventClick: function(calEvent, jsEvent, view) {


        ids = Object.values(calEvent.plans_id);
        console.log(Object.values(calEvent.plans_id));
        console.log(calEvent.id);
        $('#plan-select-edit').multiSelect('deselect_all');
        $('#plan-select-edit').multiSelect('select',ids.map(String));
        //console.log( );
        update_url = $('#blockedit #block-update').attr('action');

        update_newurl = update_url.replace(/[0-9]/g, calEvent.id);
        console.log(update_newurl);
        $('#blockedit #block-update').attr('action',update_newurl);

        delete_url = $('#blockedit #block-delete').attr('action');
        delete_newurl = delete_url.replace(/[0-9]/g, calEvent.id);

        console.log(delete_newurl);
        $('#blockedit #block-delete').attr('action',delete_newurl);
        $('#blockedit').modal();
      },
      dayClick: function(date, jsEvent, view) {

        console.log('dayClick:'+date.format());
        $('#plan-select-add').multiSelect('deselect_all');
        $('#unique-tab input[name="date"]').val(date.format('D/M/Y'));
        $('#blockadd input[name="start"]').val(date.format('H:mm'));
        $('#blockadd input[name="end"]').val(date.add(1, 'hours').format('H:mm'));
        $('#daycheckbox input').prop('checked', false);
        $('#daycheckbox input[value="'+date.day()+'"]').prop('checked', true);
        $('#blockadd').modal();
        console.log('dow:'+date.day());
      },


    });
  });
  $('#recurrent').prop('checked', true);
  $('#unique-tab').hide();
  $('#recurrent').change(function()
   {
     if(this.checked == true)
     {
          console.log('recurrente');
          $('#recurrent-tab').show();
          $('#unique-tab').hide();
     }
   });
   $('#unique').change(function()
    {
      if(this.checked == true)
      {
           console.log('unico');
           $('#unique-tab').show();
           $('#recurrent-tab').hide();
      }
    });

  </script>


@endsection
