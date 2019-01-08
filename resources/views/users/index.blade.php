@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'users'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="ibox ibox-fullheight">
        <div class="ibox-head">
          <div class="ibox-title">Alumnos</div>
          <div class="ibox-tools">
            <a class="btn btn-success text-white" href="{{ route('users.create')}}">Nuevo alumno</a>
          </div>
        </div>
        <div class="ibox-body">
          <div class="flexbox mb-4">
            <div class="flexbox">
              <span class="flexbox mr-3">
                <div class="btn-group">
                  <button class="btn btn-outline-success user-filter" data-status="1">
                    <span class="btn-icon">ACTIVOS</span>
                  </button>
                  <span class="btn-label-out btn-label-out-right btn-label-out-success pointing">{{$users->where('status_user_id', 1)->count()}}</span>
                </div>
              </span>
              <span class="flexbox mr-3" >
                <div class="btn-group">
                  <button class="btn btn-outline-danger user-filter" data-status="2">
                    <span class="btn-icon">INACTIVOS</span>
                  </button>
                  <span class="btn-label-out btn-label-out-right btn-label-out-danger pointing">{{$users->where('status_user_id', 2)->count()}}</span>
                </div>
              </span>
              <span class="flexbox mr-3">
                <div class="btn-group">
                  <button class="btn btn-outline-warning user-filter" data-status="3">
                    <span class="btn-icon">PRUEBA</span>
                  </button>
                  <span class="btn-label-out btn-label-out-right btn-label-out-warning pointing">{{$users->where('status_user_id', 3)->count()}}</span>
                </div>
              </span>
              <span class="flexbox mr-3">
                <div class="btn-group">
                  <button class="btn btn-outline-primary user-filter" data-status="">
                    <span class="btn-icon">TODOS</span>
                  </button>
                  <span class="btn-label-out btn-label-out-right btn-label-out-primary pointing">{{$users->count()}}</span>
                </div>
              </span>
            </div>
          </div>
          <div class="table-responsive">
            <table id="students-table" class="table table-hover">
              <thead class="thead-default thead-lg">
                <tr>
                  <th width="30%">Alumno</th>
                  <th width="10%">RUN</th>
                  <th width="10%">Plan Activo</th>
                  <th width="15%">Vencimiento</th>
                  <th width="20%">Período</th>
                  <th width="10%">Acciones</th>
                  <th width="10%">status</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($users as $user)
                <tr>
                  <td style="vertical-align: middle;">
                    {{-- <img class="img-circle" src="{{$user->avatar}}" alt="image" width="54"> --}}
                    <div class="img-avatar" style="background-image: url('{{$user->avatar}}');"></div>
                    <span class="badge-{{$user->status_user->type}} badge-point"></span>
                    <a href="{{url('/users/'.$user->id)}}">
                      {{$user->first_name}} {{$user->last_name}}
                    </a>
                  </td>
                  <td>{{Rut::set($user->rut)->fix()->format()}}</td>
                  @if ($user->actual_plan)
                    <td>{{$user->actual_plan->plan->plan ?? 'No aplica'}}</td>
                    @if ($user->actual_plan->finish_date >= (Carbon\Carbon::today()))
                      <td>{{'Quedan '}}{{$user->plan_users->first()->finish_date->diffInDays(Carbon\Carbon::now())}}{{' días'}}</td>
                    @else
                      <td>{{'--'}}</td>
                    @endif
                    <td>{{$user->actual_plan->start_date->format('d-m-Y')}} a {{$user->actual_plan->finish_date->format('d-m-Y')}}</td>
                  @else
                    <td>{{'Sin plan'}}</td>
                    <td>{{'No aplica'}}</td>
                    <td>{{'No aplica'}}</td>
                  @endif
                  <td>
                    <a href="{{url('/users/'.$user->id)}}" class="btn btn-info btn-icon-only btn-success"><i class="la la-eye"></i></a>
                    <a href="" class="btn btn-info btn-icon-only btn-message"><i class="la la-envelope"></i></a>
                    <a href="" class="btn btn-info btn-icon-only btn-pay"><i class="la la-usd"></i></a>
                  </td>
                  <td>{{$user->status_user_id}}</td>
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
	<script >
		$(document).ready(function() {
			table = $('#students-table').DataTable({
				"paging": true,
				"ordering": true,
            "order": [[ 3, "asc" ]],
				"language": {
					"lengthMenu": "Mostrar _MENU_ elementos",
					"zeroRecords": "Sin resultados",
					"info": "Mostrando página _PAGE_ de _PAGES_",
					"infoEmpty": "Sin resultados",
					"infoFiltered": "(filtrado de _MAX_ registros totales)",
					"search": "Filtrar:"

				},
        "columnDefs": [
          {
              "targets": [ 6 ],
              "visible": false,
              "searchable": true
          }
        ],
			});
		});


  $('button.user-filter').on("click", function(){
      table.columns( 6 ).search( $(this).data('status') ).draw();
    });

	</script>
	{{--  End datatable --}}

@endsection
