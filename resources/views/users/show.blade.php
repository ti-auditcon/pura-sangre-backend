@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="ibox flex-1">
    <div class="ibox-body">
      <div class="flexbox">
        <div class="flexbox-b">
          <div class="ml-5 mr-5">
            {{-- <img class="img-circle" src="{{url('/img/users/'.$student->avatar)}}" alt="image" width="110"> --}}
          </div>
          <div>
            <h4>{{$user->first_name}} {{$user->last_name}}</h4>

            <div class="text-muted font-13 mb-3">
              <span class="mr-3"><i class="mr-2"></i>{{$user->active_plan()->plan ?? 'sin plan'}}</span>
            </div>
          </div>
        </div>
        <div class="d-inline-flex">

            <div class="px-4 text-center">
                <span class="badge badge-success badge-pills">ACTIVO</span>
            </div>
          <div class="px-4 text-center">
            <div class="text-muted font-13">Clases asistidas</div>
            <div class="h2 mt-2">134</div>
          </div>
          <div class="px-4 text-center">
            <div class="text-muted font-13">Clases disponibles</div>
            <div class="h2 mt-2 text-warning">7</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-4">
      <div class="ibox">
        <div class="ibox-head">
          <div class="ibox-title">DETALLES</div>
          <div class="ibox-tools">
            <a class="btn btn-success text-white" href="{{ route('users.edit', $user->id) }}">Editar</a>
            {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete', 'class' => 'user-delete']) !!}
            {!! Form::close() !!}
            <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick sweet-user-delete"
            data-id="{{$user->id}}" data-name="{{$user->first_name}} {{$user->last_name}}"><i class="la la-trash"></i></button>

          </div>
        </div>
        <div class="ibox-body">
          <div class="card mb-4">
            <div class="card-body">
              <div class="row mb-2">
                <div class="col-12 text-muted">RUT:</div>
                <div class="col-12">{{Rut::set($user->rut)->fix()->format()}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">EMAIL:</div>
                <div class="col-12">{{$user->email}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Fecha de nacimiento:</div>
                <div class="col-12">22-07-1985</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Teléfono</div>
                <div class="col-12">{{$user->phone}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Direccción:</div>
                <div class="col-12">{{$user->address}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Contacto de emergencia</div>
                <div class="col-12">{{$user->emergency->contact_name}}, {{$user->emergency->contact_phone}}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="ibox">
        <div class="ibox-head">
          <div class="ibox-title">Planes</div>
          <div class="ibox-tools">
            <a class="btn btn-success text-white"
            href="{{ route('users.plans.create', $user->id) }}">Asignar Plan</a>
          </div>
        </div>
        <div class="ibox-body">
          <table id="plans-table" class="table table-hover">
            <thead class="thead-default thead-lg">
              <tr>
                <th width="30%">Plan</th>
                <th width="30%">Período</th>
                <th width="20%">Desde</th>
                <th width="20%">Hasta</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($user->plan_users as $plan_user)
              <tr>
                <td>
                  {{-- {{dd($user->id, $plan_user->id)}} --}}
                <a class="media-img" href="{{route('users.plans.show', ['user' => $user->id, 'plan' => $plan_user->id])}}">
                  {{$plan_user->plan->plan}}</a>
                </td>
                <td>{{$plan_user->plan->plan_period->period}}</td>
                <td>{{Carbon\Carbon::parse($plan_user->start_date)->format('d-m-Y')}}</td>
                <td>{{Carbon\Carbon::parse($plan_user->finish_date)->format('d-m-Y')}}</td>
                {{-- <td><span class="badge badge-success">{{$plan_user->plan_state}}</span></td> --}}
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-8">
      <div class="ibox ibox-fullheight">
        <div class="ibox-head">
          <div class="ibox-title">Pagos</div>
          <div class="ibox-tools">
            <button class="btn btn-success">Realizar pago</button>
          </div>
        </div>
        <div class="ibox-body">
          <div class="flexbox mb-4">
            <div class="flexbox">
              <span class="flexbox mr-3">
                <span class="mr-2 text-muted">Dia de pago</span>
                <span class="h3 mb-0 text-primary font-strong">08</span>
              </span>
              <span class="flexbox mr-3">
                <span class="mr-2 text-muted">Dias disponibles</span>
                <span class="h3 mb-0 text-primary font-strong">9</span>
              </span>
            </div>
          </div>
          <div class="ibox-fullwidth-block">
            <table id="students-table" class="table table-hover">
              <thead class="thead-default thead-lg">
                <tr>
                  <th width="20%">Plan</th>
                  <th width="30%">Periodo</th>
                  <th width="15%">total</th>
                  <th width="20%">Medio de pago</th>
                  <th width="15%">Día de pago</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($user->installments as $fee)
                  {{-- {{dd($fee)}} --}}
                    <tr>
                      <td>{{Carbon\Carbon::parse($fee->commitment_date)->format('d-m-Y')}}</td>
                      <td>{{Carbon\Carbon::parse($fee->payment_date)->format('d-m-Y')}}</td>
                      <td>{{Carbon\Carbon::parse($fee->expiration_date)->format('d-m-Y')}}</td>
                      <td>{{$fee->amount}}</td>
                      <td></td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>

@endsection


@section('css') {{-- stylesheet para esta vista --}}
@endsection



@section('scripts') {{-- scripts para esta vista --}}

  <script>
	$('.sweet-user-delete').click(function(e){
	  var id = $(this).data('id');
		//alert(id);
			swal({
					title: "Desea eliminar al usuario: "+$(this).data('name')+"?",
					text: "(Se borrarán todas las cuotas o planes futuros, manteniendo los ya consumidos)",
					type: 'warning',
					showCancelButton: true,
					confirmButtonClass: 'btn-danger',
					cancelButtonText: 'Cancelar',
					confirmButtonText: 'Eliminar',
					closeOnConfirm: false,
			},function(){
				//redirección para eliminar usuario
         $('form.user-delete').submit();
			});
	});
	</script>




@endsection
