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
          <a class="media-img" href="javascript:;">
            <img class="img-circle" src="{{url('/storage/users/'.$user->avatar.'.jpg')}}" alt="image" width="72">
          </a>
          <div>

            <h4>{{$user->first_name}} {{$user->last_name}}</h4>
            <span class="mr-3">{{$user->actual_plan->plan->plan ?? "sin plan actualmente" }}</span>

            <div class="text-muted font-13 mb-3">
              <span class="mr-3"><i class="mr-2"></i></span>
            </div>
          </div>
        </div>
        <div class="d-inline-flex">
          <div class="px-4 text-center">
            @if ($user->plan_users->where('plan_status_id', 1)->first() != null)
              <span class="badge badge-success badge-pills">ACTIVO</span>
            @else
              <span class="badge badge-danger badge-pills">INACTIVO</span>
            @endif
          </div>
          {{-- {{dd($user->attendedClases)}} --}}
          <div class="px-4 text-center">
            <div class="text-muted font-13">Clases Disponibles</div>
            <div class="h2 mt-2">
              {{$user->actual_plan->counter ?? "0"}}
            </div>
          </div>
          @if (in_array($user->actual_plan->plan_id, [7,8,9,10]))
            <div class="px-4 text-center">
              <div class="text-muted font-13">Clases disponibles</div>
              <div class="h2 mt-2 text-warning">
                {{12-$user->actual_plan->counter}}
              </div>
            </div>
          @endif
          
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-4">
      <div class="ibox">
        <div class="ibox-head d-flex">
          <div class="ibox-title">MIS DATOS</div>
          <div class="row">
          <div class="col-sm-6 form-group mb-4">
            <a class="btn btn-success text-white" style="display: inline-block;" href="{{ route('users.edit', $user->id) }}">Editar</a>
          </div>
            {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete', 'class' => 'user-delete']) !!}
            {!! Form::close() !!}
            @if (Auth::user()->hasRole(1))
            <div class="col-sm-6 form-group mb-4">
               <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick sweet-user-delete" style="display: inline-block;" data-id="{{$user->id}}" data-name="{{$user->first_name}} {{$user->last_name}}"><i class="la la-trash"></i></button>
            </div>
            @endif
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
                <div class="col-12">{{'+56 9 '.$user->phone ?? ''}}</div>
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

    </div>
    <div class="col-8">
      <div class="ibox ibox-fullheight">
        <div class="ibox-head">
          <div class="ibox-title">MIS PLANES</div>
          @if (Auth::user()->hasRole(1))
            <div class="ibox-tools">
            <a class="btn btn-success text-white"
            href="{{ route('users.plans.create', $user->id) }}">Asignar Plan</a>
          </div>
          @endif
        </div>
        <div class="ibox-body">
          <div class="ibox-fullwidth-block">
            <table id="students-table" class="table table-hover">
              <thead class="thead-default thead-lg">
                <tr>
                  <th width="15%">Plan</th>
                  <th width="15%">Fecha Pago</th>
                  <th width="20%">Periodo</th>
                  <th width="10%">Clases</th>
                  <th width="15%">Medio de pago</th>
                  <th width="15%">Monto</th>
                  <th width="10%">Acciones</th>
                </tr>
              </thead>
              <tbody>

                @foreach($user->plan_users()->orderBy('created_at','desc')->orderBy('plan_status_id', 'ASC')->get() as $up)
                  <tr>
                    <td><a href="{{url('/users/'.$user->id.'/plans/'.$up->id)}}">{{$up->plan->plan}}</a></td>
                    <td>{{$up->bill->date ?? "no aplica"}}</td>
                    <td>{{$up->start_date->format('d-m-Y')}} al {{$up->finish_date->format('d-m-Y')}}</td>
                    <td>{{$up->counter}}</td>
                    <td>{{$up->bill->payment_type->payment_type ?? "no aplica"}}</td>
                    <td>{{$up->bill->amount ?? "no aplica" }}</td>
                    <td>
                      {!! Form::open(['route' => ['users.plans.destroy', 'user' => $user->id, 'plan' => $up->id], 'method' => 'delete', 'class' => 'user-plan-delete']) !!}
                      {!! Form::close() !!}
                      <button class="btn btn-info btn-icon-only btn-circle btn-sm btn-air sweet-user-plan-delete" data-id="{{$up->id}}" data-name="{{$up->plan->plan}}"><i class="la la-trash"></i></button>
                    </td>
                  </tr>

                @endforeach
                {{-- @foreach ($user->installments as $fee)

                    <tr>
                      <td>{{$fee->plan_user->plan->plan}}</td>
                      <td>{{Carbon\Carbon::parse($fee->commitment_date)->format('d-m-Y')}}</td>
                      <td>{{Carbon\Carbon::parse($fee->payment_date)->format('d-m-Y')}}</td>
                      <td>{{Carbon\Carbon::parse($fee->expiration_date)->format('d-m-Y')}}</td>
                      <td>{{$fee->payment_status->payment_status}}</td>
                      <td>{{$fee->amount}}</td>
                      <td></td>
                    </tr>
                @endforeach --}}
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

  {{-- ELIMINAR UN PLAN A UN USUARIO --}}
  <script>
  $('.sweet-user-plan-delete').click(function(e){
    var id = $(this).data('id');
    //alert(id);
      swal({
          title: "Desea eliminar el plan: "+$(this).data('name')+"?",
          text: "(Se borrarán todas las cuotas futuras de este plan, manteniendo los ya consumidos)",
          type: 'warning',
          showCancelButton: true,
          confirmButtonClass: 'btn-danger',
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Eliminar',
          closeOnConfirm: false,
      },function(){
        //redirección para eliminar usuario
         $('form.user-plan-delete').submit();
      });
  });
  </script>




@endsection
