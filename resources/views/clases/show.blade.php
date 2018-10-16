@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-6">
    <div class="ibox">
      <div class="ibox-head">
        <div class="ibox-title">CLASE</div>
      </div>
      <div class="ibox-body">
        <div class="row mb-4">
          <div class="col-lg-6 col-md-6">
            <div class="card mb-4">
              <div class="card-body ">
                <div class="row mb-2">
                  <div class="col-12 text-muted">Fecha:</div>
                  <div class="col-12">{{Carbon\Carbon::parse($clase->date)->format('d-m-Y')}}</div>
                </div>
                <div class="row mb-2">
                  <div class="col-12 text-muted">Horario:</div>
                  <div class="col-12">{{$clase->block->start}} - {{$clase->block->end}}</div>
                </div>
                <div class="row mb-2">
                  <div class="col-12 text-muted">Coach:</div>
                  <div class="col-12">{{$clase->block->user->first_name}} {{$clase->block->user->last_name}}</div>
                </div>
                <br />
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-6">
            <div class="card mb-4">
              <div class="card-body flexbox-b">
                <div class="row ">
                  <div class="easypie mr-4" data-percent="{{$clase->reservations->count()*100/25}}" data-bar-color="#5c6bc0" data-size="80" data-line-width="8">
                    <span class="easypie-data font-26 text-primary"><i class="ti-user"></i></span>
                  </div>
                  <h3 class="font-strong text-primary">{{$clase->reservations->count()}}/{{$clase->block->block_type->max_quota}}</h3>
                  <div class="text-muted">Cupos confirmados</div>
                </div>
                @if (Auth::user()->hasRole(1))
                <div class="row">
                  {!! Form::open(['route' => ['clases.destroy', $clase->id], 'method' => 'delete', 'class' => 'clase-delete']) !!}
                  {!! Form::close() !!}
                  <button class="btn btn-danger sweet-clase-delete" data-id="{{$clase->id}}" data-name="{{$clase->date}}"><i>
                  </i>Deshabilitar clase</button>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="ibox">
      <div class="ibox-head">
        <div class="ibox-title">Workout</div>
        <div class="ibox-tools">
          <a href=""><i class="ti-pencil"></i></a>
        </div>
      </div>
      <div class="ibox-body">
        <div class="row">
          @foreach(App\Models\Wods\StageType::all() as $st)
            <div class="col-md-4">
              <div class="ibox shadow-wide">
                <div class="ibox-body text-center">
                  <h5 class="font-strong">{{$st->stage_type}}</h5>
                  <div class="py-5">
                    <textarea name="{{$st->id}}" class="form-control form-control-solid" rows="6" disabled>
                      @if($clase->wod)
                      {{$clase->wod->stage($st->id)->description }}
                      @else
                        - sin registro -
                      @endif
                    </textarea>

                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <div class="col-6">
    <div class="ibox">
        <div class="ibox-head">
          <div class="ibox-title">Alumnos</div>
          @if (Auth::user()->hasRole(1))
            <button class="btn btn-success" data-toggle="modal" data-target="#user-assign">Agregar alumno a la clase</button>
          @else
            {!! Form::open(['route' => ['clases.users.store', 'clase' => $clase->id], 'method' => 'post']) !!}
                <input type="hidden" value="{{Auth::user()->id}}" name="user_id">
                <button class="btn btn-success sweet-user-join" data-id="{{$clase->id}}" data-name="{{$clase->date}}">
                <i class=""></i>Reservar</button>
            {!! Form::close() !!}
          @endif
        </div>
        <div class="ibox-body">
          <div class="ibox-fullwidth-block">
            <table id="students-table" class="table table-hover">
              <thead class="thead-default thead-lg">
                <tr>
                  <th width="80%">Alumno</th>
                  @if (Auth::user()->hasRole(1) || Auth::user()->hasRole(2))
                  <th width="20%">Acciones</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @foreach ($clase->reservations as $reservation)
                <tr>
                  <td>
                    <a class="media-img" href="javascript:;">
                        <img class="img-circle" src="{{url('/img/users/u'.rand(1,11).'.jpg')}}" alt="image" width="54">
                    </a>
                    @if($reservation->user->status_user_id == 1 )
                      <span class="badge-success badge-point"></span>
                    @elseif($reservation->user->status_user_id == 2 )
                      <span class="badge-danger badge-point"></span>
                    @elseif($reservation->user->status_user_id == 3 )
                      <span class="badge-warning badge-point"></span>
                    @endif
                    <a @if (Auth::user()->hasRole(1) || Auth::user()->hasRole(2)) href="{{url('/users/'.$reservation->user->id)}}" @endif>
                      {{$reservation->user->first_name}} {{$reservation->user->last_name}}
                    </a>
                  </td>
                  @if (Auth::user()->hasRole(1))
                  <td>
            {!! Form::open(['route' => ['clases.users.destroy', 'clase' => $clase->id, 'user' => $reservation->user->id], 'method' => 'delete', 'id'=>'delete'.$reservation->user->id]) !!}
                    <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick sweet-user-delete" type="button"
            data-id="{{$reservation->user->id}}" data-name="{{$reservation->user->first_name}} {{$reservation->user->last_name}}"><i class="la la-trash"></i></button>
            {!! Form::close() !!}
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

  <!-- Modal -->
  <div class="modal fade" id="user-assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table id="students-table-search" class="table table-hover">
            <thead class="thead-default thead-lg">
              <tr>
                <th width="80%">Alumnos</th>
                <th width="20%">Accion</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($outclase as $usuario)
              <tr>
                <td>
                  <a class="media-img" href="javascript:;">
                      <img class="img-circle" src="{{url('/img/users/u'.rand(1,11).'.jpg')}}" alt="image" width="54">
                  </a>
                @if($usuario->status_user_id == 1 )
                  <span class="badge-success badge-point"></span>
                @elseif($usuario->status_user_id == 2 )
                  <span class="badge-danger badge-point"></span>
                @elseif($usuario->status_user_id == 3 )
                  <span class="badge-warning badge-point"></span>
                @endif

                  <a href="{{url('/users/'.$usuario->id)}}">
                    {{$usuario->first_name}} {{$usuario->last_name}}
                  </a>
                </td>
                <td>
                  {!! Form::open(['route' => ['clases.users.store', 'clase' => $clase->id], 'method' => 'post']) !!}
                  <input type="hidden" value="{{$usuario->id}}" name="user_id">
                  <button type="button" class="btn btn-primary" type="submit" onClick="this.form.submit();">Agregar</button>
                {!! Form::close() !!}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>


@endsection

@section('css') {{-- stylesheet para esta vista --}}
	<link href="{{asset('css/datatables.min.css')}}" rel="stylesheet" />
@endsection



@section('scripts') {{-- scripts para esta vista --}}

<script>
  // ELIMINAR A USUARIO DE LA CLASE
  $('.sweet-user-delete').click(function(e){
    var id = $(this).data('id');
    //alert(id);
      swal({
          title: "Desea sacar a: "+$(this).data('name')+" de esta clase?",
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonClass: 'btn-danger',
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Eliminar',
          closeOnConfirm: false,
      },function(){
        //redirección para sacar al usuario
         $('form#delete'+id).submit();
      });
  });
  </script>

{{-- RESERVAR CUPO A LA CLASE (VISTA DEL USUARIO) --}}
<script>
 $('.sweet-user-join').click(function(e){
    var id = $(this).data('id');
    //alert(id);
      swal({
          title: "Confirma la reserva a la clase: "+$(this).data('name')+"?",
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonClass: 'btn-danger',
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Eliminar',
          closeOnConfirm: false,
      },function(){
        //redirección para sacar al usuario
         $('form.user-delete').submit();
      });
  });
  </script>

	{{--  datatable --}}
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script >
		$(document).ready(function() {
			table = $('#students-table').DataTable({
				"paging": true,
				"ordering": true,
        "pageLength": 8,
        "bLengthChange" : false, //thought this line could hide the LengthMenu
        "bpageLength": false,
        "bPaginate": false,
				"language": {
					"lengthMenu": "Mostrar _MENU_ elementos",
					"zeroRecords": "Sin resultados",
					"info": "Mostrando página _PAGE_ de _PAGES_",
					"infoEmpty": "Sin resultados",
					"infoFiltered": "(filtrado de _MAX_ registros totales)",
					"search": "Filtrar:"

				},
			});
      table_search = $('#students-table-search').DataTable({
          "paging": true,
          "ordering": true,
          "pageLength": 3,
          "bLengthChange" : false, //thought this line could hide the LengthMenu
          "bpageLength": false,
          "bPaginate": false,
          "language": {
            "lengthMenu": "Mostrar _MENU_ elementos",
            "zeroRecords": "Sin resultados",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Sin resultados",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Buscar Alumno:"

          },
        });
		});
  //
  //
  // $('button.user-filter').on("click", function(){
  //     table.columns( 6 ).search( $(this).data('status') ).draw();
  //   });

	</script>
	{{--  End datatable --}}

  <script>
	$('.sweet-clase-delete').click(function(e){
	  var id = $(this).data('id');
		//alert(id);
			swal({
					title: "Desea eliminar la clase: "+$(this).data('name')+"?",
					text: "(Se sacarán a todos los usuarios ya inscritos a esta clase)",
					type: 'warning',
					showCancelButton: true,
					confirmButtonClass: 'btn-danger',
					cancelButtonText: 'Cancelar',
					confirmButtonText: 'Eliminar',
					closeOnConfirm: false,
			},function(){
				//redirección para eliminar clase
         $('form.clase-delete').submit();
			});
	});
	</script>

@endsection