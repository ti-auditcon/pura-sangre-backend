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
          <div class="ibox-title">DETALLES DEL PLAN: {{strtoupper($plan->plan)}}</div>
          <div class="ibox-tools">
            <a class="btn btn-success text-white" href="{{ route('plans.edit', $plan->id) }}">Editar</a>
            {!! Form::open(['route' => ['plans.destroy', $plan->id], 'method' => 'delete', 'class' => 'plan-delete']) !!}
            {!! Form::close() !!}
            <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick sweet-plan-delete"
            data-id="{{$plan->id}}" data-name="{{$plan->plan}}"><i class="la la-trash"></i></button>

          </div>
        </div>
        <div class="ibox-body">
          <div class="card mb-4">
            <div class="card-body ">

              <div class="row mb-2">
                <div class="col-12 text-muted">Nombre</div>
                <div class="col-12">{{$plan->plan}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Periodo</div>
                <div class="col-12">{{$plan->period}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Número de clases</div>
                <div class="col-12">{{$plan->class_numbers}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Valor del plan</div>
                <div class="col-12">{{$plan->amount}}</div>
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
	$('.sweet-plan-delete').click(function(e){
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
         $('form.plan-delete').submit();
			});
	});
	</script>

@endsection
