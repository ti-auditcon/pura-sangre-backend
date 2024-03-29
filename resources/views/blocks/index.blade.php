@extends('layouts.app')

@section('sidebar')

	@include('layouts.sidebar', ['page'=>'student'])

@endsection

@section('content')
	<div class="row justify-content-center">
		<div class="col-12">
			<div class="ibox">
				<div class="ibox-head">
					<div class="ibox-title">
						<h3 class="font-strong">
							<i class="fa fa-calendar" aria-hidden="true"></i>
								Horarios de {{ Session::get('clases-type-name') }}
            </h3>
					</div>
				</div>
				<div class="ibox-body">
					{{ Form::open(['route' => 'clases.type']) }}
					<div class="form-group mb-4 row">
						<label class="col-sm-1 col-form-label">Tipo de clase:</label>

						<div class="col-sm-4">
							<select class="form-control" id="calendar-type-clase-select" name="type">
							</select>
						</div>

						<div class="col-sm-1">
							<button class="btn btn-default">seleccionar</button>
						</div>
					</div>
					{{ Form::close() }}
					<div id="calendar"></div>
				</div>

			</div>
		</div>
	</div>

	{{-- ////// MODAL PARA AGREGAR HORARIO  //////// --}}
	<div class="modal fade" id="blockadd" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
		<div class="modal-dialog ">
			{{ Form::open(['route'=>'blocks.store']) }}

			<input type="text" hidden class="form-control" value="" name="date">

			<input type="text" hidden class="form-control" value="{{ Session::get('clases-type-id') }}" name="clase_type_id">

			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Nuevo horario de {{ Session::get('clases-type-name') }}</h5>

					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<div class="form-group">
						<div class="input-group clockpicker">
							<label class="col-sm-2 col-form-label mr-1 pl-0">Inicio:</label>

							<input type="text" class="form-control" value="" name="start">

							<span class="input-group-addon">
								<span class="la la-clock-o"></span>
							</span>
						</div>
					</div>

					<div class="form-group mb-4">
						<div class="input-group clockpicker">
							<label class="col-sm-2 col-form-label mr-1 pl-0">Termino:</label>

							<input type="text" class="form-control" value="" name="end">

							<span class="input-group-addon">
								<span class="la la-clock-o"></span>
							</span>
						</div>
					</div>

					<div class="form-group mb-4">
						<select multiple="multiple" id="plan-select-add" name="plans[]">
							@foreach ($plans as $plan)
								<option value="{{ $plan->id }}">
									{{ $plan->plan }} {{ $plan->plan_period->period ?? "no aplica" }}
								</option>
							@endforeach
						</select>
					</div>

					<div class="form-group mb-12">
						<label class="col-form-label">Profesor:</label>

						<select name="coach_id" class="form-control" required>
							<option value="">Elegir un Profesor</option>
							@foreach (App\Models\Users\Role::find(2)->users as $coach)
								<option value="{{$coach->id}}">{{$coach->first_name}} {{$coach->last_name}}</option>
							@endforeach
						</select>

						<span class="input-group-addon"></span>
					</div>

					<div class="form-group mb-4">
						<label class="col-form-label">N° de Cupos</label>

						<input type="number" class="form-control" value="" name="quota" required>
					</div>

					<div class="form-group mb-4">
						<label class="radio radio-grey radio-primary">
							<input id="recurrent" type="radio" name="repetition" value="multiple" checked>

							<span class="input-span"></span>

							Recurrente
						</label>

						<label class="radio radio-grey radio-primary">
							<input id="unique" type="radio" name="repetition" value="unique">

							<span class="input-span"></span>

							Unico
						</label>
					</div>

					<div class="tab-content">
						<div id="recurrent-tab" >
							<div class="form-group" id="daycheckbox">
								<div class="mb-2">
									<label class="checkbox checkbox-inline mb-2">
										<input type="checkbox" name="day[]" value="1">

										<span class="input-span"></span>Lunes
									</label>

									<label class="checkbox checkbox-inline mb-2">
										<input type="checkbox" name="day[]" value="2">

										<span class="input-span"></span>Martes
									</label>

									<label class="checkbox checkbox-inline mb-2">
										<input type="checkbox" name="day[]" value="3">

										<span class="input-span"></span>Miercoles
									</label>

									<label class="checkbox checkbox-inline mb-2">
										<input type="checkbox" name="day[]" value="4">

										<span class="input-span"></span>Jueves
									</label>

									<label class="checkbox checkbox-inline mb-2">
										<input type="checkbox" name="day[]" value="5">

										<input type="checkbox">

										<span class="input-span"></span>Viernes
									</label>

									<label class="checkbox checkbox-inline mb-2">
										<input type="checkbox" name="day[]" value="6">

										<input type="checkbox" >

										<span class="input-span"></span>Sabado
									</label>

									<label class="checkbox checkbox-inline mb-2">
										<input type="checkbox" name="day[]" value="0">

										<input type="checkbox" >

										<span class="input-span"></span>Domingo
									</label>
								</div>
							</div>
						</div>

						<div id="unique-tab">
							<div class="form-group mb-12">
								<div class="input-group date">
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
					<button
						type="submit"
						class="btn btn-primary"
						onClick="this.disabled=true; this.value='Guardando…';this.form.submit();"
					>
						Guardar horario
					</button>

					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

				</div>
			</div>
			{{ Form::close() }}
		</div>
	</div>

	{{--  ///////////////////////////////////////////////  --}}

	{{--          MODAL PARA EDITAR HORARIO                --}}

	{{--  ///////////////////////////////////////////////  --}}
	<div class="modal fade" id="blockedit" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
          <h5 class="modal-title" id="edit-block-title"></h5>

					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<div class="form-group">
					{{ Form::open(['route' => ['blocks.update', 1], 'method' => 'put', 'id' => 'block-update', 'class' => 'styles-select']) }}
						<div class="form-group">
							<div class="input-group clockpicker">
								<label class="col-sm-2 col-form-label mr-1 pl-0">Inicio:</label>

								<input id="block-edit-start" type="text" class="form-control" value="" name="start">

								<span class="input-group-addon">
									<span class="la la-clock-o"></span>
								</span>
							</div>
						</div>

						<div class="form-group">
							<div class="input-group clockpicker">
									<label class="col-sm-2 col-form-label mr-1 pl-0">Término:</label>

									<input id="block-edit-end" type="text" class="form-control" value="" name="end">

									<span class="input-group-addon"><span class="la la-clock-o"></span></span>
							</div>
						</div>

						<div class="form-group mt-2">
							<select multiple="multiple" id="plan-select-edit" name="plans[]">
								@foreach ($plans as $plan)
									<option value="{{ $plan->id }}">
										{{ $plan->plan }} {{ $plan->plan_period->period ?? "(sin período)" }}
									</option>
								@endforeach
							</select>

						</div>

						<div class="form-group">
							<label class="col-form-label">N° de Cupos</label>

							<input id="block-quota-input" type="number" class="form-control" name="quota" required/>
						</div>

						<div class="form-group mt-0">
							<label class="col-form-label">Profesor:</label>

							<select id="select-coach" name="coach_id" class="form-control" required>
								<option value="">Elegir un Profesor</option>

								@foreach (App\Models\Users\Role::find(2)->users as $coach)
									<option value="{{ $coach->id }}">{{ $coach->first_name }} {{ $coach->last_name }}</option>
								@endforeach
							</select>

							<span class="input-group-addon"></span>
						</div>

						<button
							type="submit"
							class="btn btn-primary mt-2"
							onClick="this.disabled=true; this.value='Editando…';this.form.submit();"
						>
							Editar planes
						</button>
						{{ Form::close() }}
					</div>
					<div class="form-group mt-2 mb-4">
						{{Form::open(['route' => ['blocks.destroy', 1],'method' => 'delete' , 'id' => 'block-delete'])}}
							Eliminar el bloque?
							<button  class ="btn btn-danger mt-2" onClick="this.disabled=true; this.value='Eliminando…';this.form.submit();">Eliminar</button>
						{{Form::close()}}
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

<!-- Modal de confirmacion de clase-->
@include('blocks.modals.clases-types')
<!-- END Modal de confirmacion de clase-->
@endsection


@section('css') {{-- stylesheet para esta vista --}}
	<link href="{{ asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet"/>

	<link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet"/>

	<link href="{{ asset('css/bootstrap-clockpicker.min.css') }}" rel="stylesheet"/>

	<link href="{{ asset('css/multi-select.css') }}" rel="stylesheet"/>

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
	{{--  Full calendar --}}

	<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>

	<script src="{{ asset('js/moment.min.js') }}"></script>

	{{-- <script src="{{asset('/js/fullcalendar/jquery-ui.min.js')}}"></script> --}}

	<script src="{{ asset('js/fullcalendar/fullcalendar.min.js') }}"></script>

	<script src="{{ asset('js/fullcalendar/lang/es.js') }}"></script>

	<script src="{{ asset('js/bootstrap-clockpicker.min.js') }}"></script>

	<script src="{{ asset('js/jquery.multi-select.js') }}"></script>

	{{-- PuraSangre customized javascripts --}}
	<script src="{{ asset('js/purasangre-js/clases-types.js') }}"></script>

	<script src="{{ asset('js/sweetalert2.8.js') }}"></script>

	<script defer>

	$(document).ready(function() {
		$('.clockpicker').clockpicker({ autoclose: true });

		$('#unique-tab .form-group').datepicker({
			todayBtn: "linked",
	      	keyboardNavigation: false,
	      	forceParse: false,
	      	calendarWeeks: true,
	      	format: "dd-mm-yyyy",
	      	startDate: "01-01-1910",
	      	endDate: "01-01-2030",
	      	language: "es",
	      	orientation: "bottom auto",
	      	autoclose: true,
	      	maxViewMode: 3,
	      	// daysOfWeekDisabled: "6",
	      	todayHighlight: true
		});

		$('#plan-select-add').multiSelect({
			selectableHeader: "<div style='color: #a32017;'>No pueden tomar este horario</div>",
      selectionHeader: "<div class='text-success'>Si pueden tomar este horario</div>",
		});

		$('#plan-select-edit').multiSelect({
			selectableHeader: "<div style='color: #a32017;'>No pueden tomar este horario</div>",
      selectionHeader: "<div class='text-success'>Si pueden tomar este horario</div>",
		});

		$('#calendar').fullCalendar({
			header: {
					right:  'agendaWeek',
			},
			minTime: "06:00:00",
			maxTime: "23:59:59",
			events: {!! $blocks !!},
			editable: false,
			defaultView: 'agendaWeek',
			// allDaySlot: false,
			slotDuration: '00:30:00',
			slotLabelFormat: 'h(:mm)a',
			eventColor: '#4c6c8b',
			eventClick: function(calEvent, jsEvent, view) {
				var ids = [];
				$.each(calEvent.plans, function(index, plan) {
					ids[index] = plan.id;
				});

				//traer todos los ids de los planes que pueden tomar clase de la hora que se seleccionó
				$('#block-quota-input').val(calEvent.quota);
				$('#select-coach').val(calEvent.coach_id);
				$('#edit-block-title').text('Editar bloque para ' + moment(calEvent.start).format('dddd') + ' de ' + moment(calEvent.start).format('LT') + ' a ' + moment(calEvent.end).format('LT'));
				// $('#edit-block-title').empty();
				// $('#edit-block-title').append(
				// 	`Editar bloque para ${moment(calEvent.start).format('dddd')} de ${moment(calEvent.start).format('LT')} a ${moment(calEvent.end).format('LT')}`
				// );

				// Set values to edit form
				$('#block-edit-start').val(calEvent.start.format('H:mm'));     // set value to start input
        $('#block-edit-end').val(calEvent.end.format('H:mm'));         // set value to end input	
				$('#plan-select-edit').multiSelect('deselect_all');            // deselect all options
				$('#plan-select-edit').multiSelect('select', ids.map(String)); // select options with ids

				update_url = $('#blockedit #block-update').attr('action');
				update_newurl = update_url.replace(/[0-9]+/g, calEvent.id);

				$('#blockedit #block-update').attr('action',update_newurl);

				delete_url = $('#blockedit #block-delete').attr('action');
				delete_newurl = delete_url.replace(/[0-9]+/g, calEvent.id);

				$('#blockedit #block-delete').attr('action', delete_newurl);
				$('#blockedit').modal();
			},
			dayClick: function(date, jsEvent, view) {
				$('#plan-select-add').multiSelect('deselect_all');

				$('#unique-tab input[name="date"]').val(date.format('D/M/Y'));

				$('#blockadd input[name="start"]').val(date.format('H:mm'));

				$('#blockadd input[name="end"]').val(date.add(1, 'hours').format('H:mm'));

				$('#daycheckbox input').prop('checked', false);

				$('#daycheckbox input[value="'+date.day()+'"]').prop('checked', true);

				$('#blockadd').modal();
			},
		});
	});
	$('#recurrent').prop('checked', true);
	$('#unique-tab').hide();
	$('#recurrent').change(function(){
		if(this.checked == true){
			// console.log('recurrente');
			$('#recurrent-tab').show();
			$('#unique-tab').hide();
		}
	});
	$('#unique').change(function(){
		if(this.checked == true){
			$('#unique-tab').show();
			$('#recurrent-tab').hide();
		}
	});
	</script>

<script>
	$(function () {
		$('#calendar-type-clase-select').find('option').remove();

		$('#calendar-type-clase-select').append($('<option>Eliga un tipo de clase...</option>').val(null));

		$.get("/clases-types-all").done( function (response) {
			response.data.forEach( function (el) {
				$('#type-clase-select').append(
			        $('<option></option>').val(el.id).html(el.clase_type)
			    );

			    $('#calendar-type-clase-select').append(
			        $('<option></option>').val(el.id).html(el.clase_type)
			    );
			});

		}).done( function () {

			var clase_type_session_id = {!! Session::get('clases-type-id') ?? 1 !!};

			$('#calendar-type-clase-select option[value="' + clase_type_session_id + '"]').attr("selected", true);

		});
	});
</script>
@endsection
