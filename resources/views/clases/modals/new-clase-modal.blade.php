{{-- <div class="modal fade" id="new-clase-modal" tabindex="-1" role="dialog" aria-hidden="true">
	
	<div class="modal-dialog modal-lg" role="document">
	
		<div class="modal-content">

			<div class="modal-header">
				
				<h5 class="modal-title">Nueva Clase de {{ Session::get('clases-type-name') }}</h5>
				
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				
					<span aria-hidden="true">&times;</span>
				
				</button>
			</div>
			
			<form action="/clases" method="POST">
				@csrf
				

			<div class="modal-body">
			
				<input
					type="text"
					class="form-control"
					value="{{ Session::get('clases-type-id') }}"
					name="clase_type_id"
					hidden
				/>

				<div class="row">

					<div class="input-group clockpicker col">

						<label class="col-sm-2 col-form-label mr-1 pl-0">Inicio:</label>

						<input
							id="start_at"
							type="text"
							class="form-control"
							value="{{ old('start_at', date('h:i')) }}"
							name="start_at"
						/>

						<span class="input-group-addon">

							<span class="la la-clock-o"></span>

						</span>

					</div>

					<div class="input-group clockpicker col">

						<label class="col-sm-2 col-form-label mr-1 pl-0">Término:</label>

						<input
							id="finish_at"
							type="text"
							class="form-control"
							value="{{ old('finish_at', date('h:i')) }}"
							name="finish_at"
							required
						/>

						<span class="input-group-addon"><span class="la la-clock-o"></span></span>

					</div>

				</div>

				<div class="form-group mt-4">

					<select multiple="multiple" id="plan-select-add" name="plans[]">

						@foreach (App\Models\Plans\Plan::all() as $plan)

							<option value="{{ $plan->id }}">

								{{ $plan->plan }} {{ $plan->plan_period->period ?? "no aplica" }}

							</option>

						@endforeach

					</select>

				</div>

				<div class="row">

					<div class="col">

						<label class="col-form-label">Profesor:</label>

						<select name="coach_id" class="form-control" required>

							<option value="">Elegir un Profesor</option>

							@foreach (App\Models\Users\Role::find(2)->users as $coach)

								<option value="{{ $coach->id }}">{{ $coach->first_name }} {{ $coach->last_name }}</option>

							@endforeach

						</select>

						<span class="input-group-addon"></span>

					</div>

					<div class="col">

						<label class="col-form-label">N° de Cupos</label>

						<input type="number" class="form-control" value="" name="quota" required>

					</div>

				</div>

				<div class="form-group mt-3">
               		
               		<label class="font-normal">Fecha de nacimiento</label>
                  
                  	<div class="input-group date datepicker-calendar-clase-create">

                  		<span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>

                  		<input
                  			class="form-control"
                  			name="date"
                  			value="{{ old('date', date('d-m-Y')) }}"
                  			type="text"
                  		/>
                  	
                  	</div>

                </div>

			</div>

			<div class="modal-footer">

				<button
					type="submit"
					class="btn btn-primary"
					onClick="this.disabled=true; this.value='Guardando…'; this.form.submit();"
				>
					Crear Clase
				</button>

				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

			</div>

			</form>

		</div>
	</div>
</div> --}}