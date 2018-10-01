@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="ibox ibox-fullheight">
        <div class="ibox-head">
          <div class="ibox-title">Ejercicios</div>
          <div class="ibox-tools">
            <a class="btn btn-success btn-air text-white" href="{{ route('exercises.create')}}">CREAR EJERCICIO</a>
          </div>
        </div>
        <div class="ibox-body">
          <div class="ibox-fullwidth-block">
            <table id="students-table" class="table table-hover">
              <thead class="thead-default thead-lg">
                <tr>
                  <th width="80%">Nombre</th>
                  <th width="20%">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($exercises as $exercise)
                <tr>
                  <td>
                    <span class="badge-success badge-point"></span>
                    <a class="media-img" href="{{url('/exercises/'.$exercise->id)}}">{{$exercise->exercise}}</a>
                  </td>
                  <td>
                    <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick"><a class="la la-eye" href="{{url('/exercises/'.$exercise->id)}}"></a></button>
                    <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick"><a class="la la-pencil" href="{{route('exercises.edit', $exercise->id)}}"></a></button>
                  </td>
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
	<link href="{{asset('css/datatables.min.css')}}" rel="stylesheet" />
@endsection



@section('scripts') {{-- scripts para esta vista --}}
	{{--  datatable --}}
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script>
		$(document).ready(function() {
			$('#students-table').DataTable({
				"paging": true,
				"ordering": true,
				"language": {
					"lengthMenu": "Mostrar _MENU_ elementos",
					"zeroRecords": "Sin resultados",
					"info": "Mostrando página _PAGE_ de _PAGES_",
					"infoEmpty": "Sin resultados",
					"infoFiltered": "(filtered from _MAX_ total records)",
					"search": "Filtrar:"
				}
			});
		});

	</script>
	{{--  End datatable --}}

@endsection
