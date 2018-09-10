@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
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
                  <button class="btn btn-outline-success">
                    <span class="btn-icon">ACTIVOS</span>
                  </button>
                  <span class="btn-label-out btn-label-out-right btn-label-out-success pointing">340</span>
                </div>
              </span>
              <span class="flexbox mr-3" >
                <div class="btn-group">
                  <button class="btn btn-outline-danger">
                    <span class="btn-icon">INACTIVOS</span>
                  </button>
                  <span class="btn-label-out btn-label-out-right btn-label-out-danger pointing">120</span>
                </div>
              </span>
              <span class="flexbox mr-3">
                <div class="btn-group">
                  <button class="btn btn-outline-warning">
                    <span class="btn-icon">PRUEBA</span>
                  </button>
                  <span class="btn-label-out btn-label-out-right btn-label-out-warning pointing">13</span>
                </div>
              </span>
            </div>
          </div>
          <div class="ibox-fullwidth-block">
            <table id="students-table" class="table table-hover">
              <thead class="thead-default thead-lg">
                <tr>
                  <th width="30%">Alumno</th>
                  <th width="20%">Plan Activo</th>
                  {{-- <th width="10%">Dia de Pago</th> --}}
                  <th width="10%">Vencimiento</th>
                  <th width="20%">Período</th>
                  <th>acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($users as $user)
                <tr>
                  {{-- {{dd()}} --}}
                  @if ($user->plan_users->isNotEmpty())
                    <td>
                      <span class="badge-success badge-point"></span>
                      <a class="media-img " href="{{url('/users/'.$user->id)}}">
                        {{-- <img class="img-circle" src="" alt="image" width="54" style="padding-right:20px;"> --}}
                        {{$user->first_name}} {{$user->last_name}}
                      </a>
                    </td>
                    <td>{{$user->plan_users->first()->plan->plan}}</td>
                    <td>{{'Quedan '}}{{$user->plan_users->first()->finish_date->diffInDays(Carbon\Carbon::now())}}{{' días'}}</td>
                    <td>{{$user->plan_users->first()->start_date->format('d-m-Y')}} a {{$user->plan_users->first()->finish_date->format('d-m-Y')}}</td>
                    <td>
                      <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick"><i class="la la-credit-card"></i></button>
                      <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick"><i class="la la-send"></i></button>
                    </td>
                  @else
                    <td>
                      <span class="badge-success badge-point"></span>
                      <a class="media-img " href="{{url('/users/'.$user->id)}}">
                        {{$user->first_name}} {{$user->last_name}}
                      </a>
                    </td>
                    <td>{{'Sin plan'}}</td>
                    <td>{{'No aplica'}}</td>
                    <td>{{'No aplica'}}</td>
                    <td>
                      <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick"><i class="la la-credit-card"></i></button>
                      <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick"><i class="la la-send"></i></button>
                    </td>
                  @endif

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
