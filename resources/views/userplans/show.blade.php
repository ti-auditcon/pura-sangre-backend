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
          <div class="ibox-title"><h3 class="m-0">Plan {{ucwords($plan_user->plan->plan)}} 
          @if (Auth::user()->hasRole(1))
             de {{$user->first_name}} {{$user->last_name}} @endif </h3></div>
          <div class="ibox-tools">
            <a class="btn btn-success text-white" href="{{ route('users.plans.edit', ['user' => $user->id, 'plan' => $plan_user->id]) }}">Editar</a>
          {{--   {!! Form::open(['route' => ['users.plans.destroy', 'user' => $user->id, 'plan' => $plan_user->id], 'method' => 'delete', 'class' => 'plan_user-delete']) !!}
            {!! Form::close() !!}
            <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick sweet-plan_user-delete"
            data-id="{{$plan_user->id}}" data-name="{{$plan_user->plan->plan}}"><i class="la la-trash"></i></button> --}}

          </div>
        </div>



        <div class="ibox-body">
          <div class="card mb-6">
            <div class="card-body ">

              <div class="row">
                <div class="col-4">
                  <div class="col-12 font-bold"><h5 class="m-0">Comineza el</h5></div>
                  <div class="col-12"><h5 class="m-0 font-light">{{$plan_user->start_date->format('d-m-Y')}}</h5></div>
                </div>
                <div class="col-4">
                  <div class="col-12 font-bold"><h5 class="m-0">Termina el</h5></div>
                  <div class="col-12"><h5 class="m-0 font-light">{{$plan_user->finish_date->format('d-m-Y')}}</h5></div>
                </div>
              </div>
              <br>
                <div class="row">
                <div class="col-4">
                  <div class="col-12 font-bold"><h5 class="m-0">Valor del Plan</h5></div>
                  <div class="col-12"><h5 class="m-0 font-light">{{'$'.$plan_user->bill->amount}}</h5></div>
                </div>
                <div class="col-4">
                  <div class="col-12 font-bold"><h5 class="m-0">Estado del Plan</h5></div>
                  <div class="col-12"><h5 class="m-0 font-light">{{$plan_user->plan_status->plan_status}}</h5></div>
                </div>
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
