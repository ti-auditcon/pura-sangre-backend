@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="ibox ibox-fullheight">
        <div class="ibox-head">
          <div class="ibox-title">Planes</div>
          <div class="ibox-tools">
            <a class="btn btn-success btn-air text-white" href="{{ route('plans.create')}}">Crear Plan</a>
          </div>
        </div>
        <div class="ibox-body plans-body">
          <div class="table-responsive">
            <table id="students-table" class="table table-hover">
              <thead class="thead-default">
                <tr>
                  <th width="40%">Nombre</th>
                  <th width="20%">Período</th>
                  <th width="10%">N° Clases</th>
                  <th width="10%">Clases diarias</th>
                  <th width="20%">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($plans as $plan)
                <tr>
                  <td>
                    <span class="badge-success badge-point"></span>
                    <a class="media-img" href="{{url('/plans/'.$plan->id)}}">{{$plan->plan}}</a>
                  </td>
                  <td>{{ $plan->plan_period->period ?? "una semana"}}</td>
                  <td>{{ $plan->class_numbers }}</td>
                  <td>{{ $plan->daily_clases }}</td>
                  <td>
                    {{-- <button class="btn btn-info btn-icon-only btn-success"><a class="la la-eye" href="{{url('/plans/'.$plan->id)}}"></a></button> --}}
                    <a class="btn btn-info btn-icon-only btn-success" href="{{url('/plans/'.$plan->id)}}"><i class="la la-eye"></i> </a>
                    <a class="btn btn-info btn-icon-only btn-edit" href="{{route('plans.edit', $plan->id)}}"><i class="la la-pencil"></i></a>

                    {{-- <a href="" class="btn btn-info btn-icon-only btn-message"><i class="la la-envelope"></i></a --}}
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
					"info": "Mostrando pagina _PAGE_ de _PAGES_",
					"infoEmpty": "Sin resultados",
					"infoFiltered": "(filtered from _MAX_ total records)",
					"search": "Filtrar:"
				}
			});
		});

	</script>
	{{--  End datatable --}}
@endsection
