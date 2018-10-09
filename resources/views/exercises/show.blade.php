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
          <div class="ibox-title">DETALLES DEL EJERCICIO: {{strtoupper($exercise->exercise)}}</div>
          <div class="ibox-tools">
            <a class="btn btn-success text-white" href="{{ route('exercises.edit', $exercise->id) }}">Editar</a>
            {!! Form::open(['route' => ['exercises.destroy', $exercise->id], 'method' => 'delete', 'class' => 'exercise-delete']) !!}
            {!! Form::close() !!}
            <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick sweet-exercise-delete"
            data-id="{{$exercise->id}}" data-name="{{$exercise->exercise}}"><i class="la la-trash"></i></button>
          </div>
        </div>
        <div class="ibox-body">
          <div class="card mb-4">
            <div class="card-body ">
              <div class="row mb-2">
                <div class="col-12 text-muted">Nombre</div>
                <div class="col-12">{{$exercise->exercise}}</div>
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
	$('.sweet-exercise-delete').click(function(e){
	  var id = $(this).data('id');
		//alert(id);
			swal({
					title: "Desea eliminar el ejercicio: "+$(this).data('name')+"?",
					text: "",
					type: 'warning',
					showCancelButton: true,
					confirmButtonClass: 'btn-danger',
					cancelButtonText: 'Cancelar',
					confirmButtonText: 'Eliminar',
					closeOnConfirm: false,
			},function(){
				//redirección para eliminar usuario
         $('form.exercise-delete').submit();
			});
	});
	</script>

@endsection
