@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="ibox flex-1">
    </div>
  <div class="row justify-content-center">
    <div class="col-7">
      <div class="ibox">
        <div class="ibox-head">
          <div class="ibox-title">Detalles del plan {{strtoupper($plan_user->plan->plan)}} de {{$user->first_name}} {{$user->last_name}}</div>
          {{-- {{dd($user)}} --}}
          <div class="ibox-tools">
            <a class="btn btn-success text-white" href="{{ route('users.plans.edit', ['user' => $user->id, 'plan' => $plan_user->id]) }}">Editar</a>
            {!! Form::open(['route' => ['users.plans.destroy', 'user' => $user->id, 'plan' => $plan_user->id], 'method' => 'delete', 'class' => 'plan_user-delete']) !!}
            {!! Form::close() !!}
            <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick sweet-plan_user-delete"
            data-id="{{$plan_user->id}}" data-name="{{$plan_user->plan->plan}}"><i class="la la-trash"></i></button>

          </div>
        </div>
        <div class="ibox-body">
          <div class="card mb-4">
            <div class="card-body ">

              <div class="row mb-2">
                <div class="col-12 text-muted">Comineza el</div>
                <div class="col-12">{{$plan_user->start_date}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Termina el</div>
                <div class="col-12">{{$plan_user->finish_date}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Valor del Plan</div>
                <div class="col-12">{{$plan_user->amount}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Estado del Plan</div>
                <div class="col-12">{{$plan_user->plan_state}}</div>
              </div>
            </div>
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
	$('.sweet-plan_user-delete').click(function(e){
	  var id = $(this).data('id');
		//alert(id);
			swal({
					title: "Desea eliminar el plan: "+$(this).data('name')+"?",
					text: "",
					type: 'warning',
					showCancelButton: true,
					confirmButtonClass: 'btn-danger',
					cancelButtonText: 'Cancelar',
					confirmButtonText: 'Eliminar',
					closeOnConfirm: false,
			},function(){
				//redirección para eliminar usuario
         $('form.plan_user-delete').submit();
			});
	});
	</script>

@endsection
