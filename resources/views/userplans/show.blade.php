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
          <div class="ibox-title">Plan {{ucwords($plan_user->plan->plan)}}
          @if (Auth::user()->hasRole(1))
             de {{$user->first_name}} {{$user->last_name}} @endif </div>
          @if (Auth::user()->hasRole(1))
            <div class="ibox-tools">
              <a class="btn btn-success text-white" href="{{ route('users.plans.edit', ['user' => $user->id, 'plan' => $plan_user->id]) }}">Editar</a>
            </div>
          @endif

          {{--   {!! Form::open(['route' => ['users.plans.destroy', 'user' => $user->id, 'plan' => $plan_user->id], 'method' => 'delete', 'class' => 'plan_user-delete']) !!}
            {!! Form::close() !!}
            <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick sweet-plan_user-delete"
            data-id="{{$plan_user->id}}" data-name="{{$plan_user->plan->plan}}"><i class="la la-trash"></i></button> --}}

        </div>

        <div class="ibox-body">

              <div class="row mb-4">
                <div class="col-4">
                  <div class="col-12 text-muted">Comineza el</div>
                  <div class="col-12">{{$plan_user->start_date->format('d-m-Y')}}</div>
                </div>
                <div class="col-4">
                  <div class="col-12 text-muted">Termina el</div>
                  <div class="col-12">{{$plan_user->finish_date->format('d-m-Y')}}</div>
                </div>
              </div>
              {{-- <br> --}}
              <div class="row mb-3">
                <div class="col-4">
                  <div class="col-12 text-muted">Valor del Plan</div>
                  <div class="col-12">{{$plan_user->bill->amount ?? 'No aplica'}}</div>
                </div>
                <div class="col-4">
                  <div class="col-12 text-muted">Estado del Plan</div>
                  <div class="col-12">{{$plan_user->plan_status->plan_status}}</div>
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
