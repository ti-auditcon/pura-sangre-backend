@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
<div class="row justify-content-center">
   <div class="col-4">
      <div class="ibox">
         <div class="ibox-head">
         <div class="ibox-title">Clase</div>
            <div class="ibox-tools">
            @if (Auth::user()->hasRole(1))
               @if (Carbon\Carbon::parse($clase->date)->gte(today()))
                  {!! Form::open(['route' => ['clases.destroy', $clase->id], 'method' => 'delete', 'class' => 'clase-delete']) !!}
                  {!! Form::close() !!}
                  <button class="btn btn-danger sweet-clase-delete" data-id="{{$clase->id}}" data-name="{{$clase->date}}"><i>
                  </i>Cerrar Clase</button>
               @endif
            @endif
            </div>
         </div>
         <div class="ibox-body">
            <div class="clase">
               <div class="card">
                  <div class="row mb-2">
                     <div class="col-12 text-muted">Fecha:</div>
                     <div class="col-12">{{Carbon\Carbon::parse($clase->date)->format('d-m-Y')}}</div>
                  </div>
                  <div class="row mb-2">
                     <div class="col-12 text-muted">Horario:</div>
                     <div class="col-12">{{Carbon\Carbon::parse($clase->block->start)->format('H:i')}} - {{Carbon\Carbon::parse($clase->block->end)->format('H:i')}}</div>
                  </div>
                  <div class="row">
                     <div class="col-12 text-muted">Coach:</div>
                     <div class="col-12">{{$clase->block->user->first_name}} {{$clase->block->user->last_name}}</div>
                  </div>
                  <br />
               </div>
               <div class="clase-graphics pt-2">
                  <div class="canvas-item">
                     <div id="porcentaje" class="easypie col" data-percent="{{$clase->reservations->count()*100/$clase->quota}}" data-bar-color="#5c6bc0" data-size="70" data-line-width="8">
                     </div>
                 {{--     <div>{{$clase->reservations->count()*100/$clase->quota}}</div> --}}
                  </div>
                  <div class="data-item">
                     <div class="row m-0">
                        <div class="col-12 p-0 m-0">
                           <span class="easypie-data font-26 text-primary icon-people"><i class="ti-user"></i></span>
                           <h3 id="total" class="font-strong text-primary">{{$clase->reservations->count()}}</h3><h3 class="font-strong text-primary">/{{$clase->quota}}</h3>
                        </div>
                        <div class="col-12 p-0 m-0">
                           <div class="text-muted">Cupos confirmados</div>
                        </div>
                     </div>
                  </div>
               </div>
         </div>
      </div>
      </div>
      <div class="ibox">
        <div class="ibox-head">
          <div class="ibox-title">Workout</div>
        </div>
        <div class="ibox-body">
          <div class="row">
            @foreach(App\Models\Wods\StageType::all() as $st)
              <div class="col-12 mb-4">
                {{-- <div class="ibox"> --}}
                  {{-- <div class="ibox-body"> --}}
                    <h5 class="font-strong">{{$st->stage_type}}</h5>
                    <div class="py-2">
                      <textarea name="{{$st->id}}" class="form-control form-control-solid p-4" rows="10" disabled>@if($clase->wod){{$clase->wod->stage($st->id)->description }} @else No hay {{$st->stage_type}} ingresado @endif</textarea>
                    </div>
                  {{-- </div> --}}
                {{-- </div> --}}
              </div>
            @endforeach
          </div>
        </div>
      </div>
  </div>
   <div class="col-8">
      <div class="ibox">
         <div class="ibox-head">
            <div class="ibox-title">Crossfiteros de esta clase</div>
            <div class="ibox-tools">
               @if (Auth::user()->hasRole(1) || Auth::user()->hasRole(2))
                  <button id="button-modal" class="btn btn-warning btn-icon-only" data-toggle="modal" data-target="#confirm-assistance-modal"><i class="la la-check-square"></i></button>
               @if (Auth::user()->hasRole(1))
                  <button id="assign-button" class="btn btn-success" data-toggle="modal" data-target="#user-assign">Agregar alumno a la clase</button>
               @endif
               @else
               {!! Form::open(['route' => ['reservation.store'], 'method' => 'post' ,'id' => 'user-join']) !!}
                  <input type="hidden" value="{{Auth::user()->id}}" name="user_id">
                  <input type="hidden" value="{{$clase->id}}" name="clase_id">
                  <button class="btn btn-success sweet-user-join" data-id="{{$clase->id}}" data-name="{{$clase->date}}">  <i></i>Reservar esta clase
                  </button>
               {!! Form::close() !!}
               @endif
            </div>
         </div>

         <div class="ibox-body pb-5">
            <div class="table-responsive">
               {{-- {!! Form::open(['route' => ['clase.confirm', $clase->id], 'method' => 'post', 'id'=>'confirm']) !!} --}}
               <table id="students-table" class="table table-hover">
                  <thead class="thead-default">
                     <tr>
                        <th width="80%">Alumno</th>
                        <th width="10%">Estado</th>
                        <th width="10%">Acciones</th>
                     </tr>
                  </thead>
                  <tbody>
                  @foreach ($clase->reservations as $reservation)
                     <tr>
                        <td>
                           {{-- <img class="img-circle" src="{{$reservation->user->avatar}}" alt="image" width="54"> --}}
                           {{-- <div class="img-avatar" style="background-image: url('{{$reservation->user->avatar}}');"></div> --}}

                           <div class="img-avatar" style="background-image:  @if ($reservation->user->avatar) url('{{$reservation->user->avatar}}') @else url('{{ asset('/img/default_user.png') }}') @endif"></div>
                           <span class="badge-{{$reservation->user->status_user->type}} badge-point"></span>
                           <a @if (Auth::user()->hasRole(1) || Auth::user()->hasRole(2)) href="{{url('/users/'.$reservation->user->id)}}" @endif>
                              {{$reservation->user->first_name}} {{$reservation->user->last_name}}
                           </a>
                        </td>
                        <td>
                           <span class="badge badge-{{$reservation->reservation_status->type}} badge-pill">{{strtoupper($reservation->reservation_status->reservation_status)}}</span>
                        </td>

                     @if (Auth::user()->hasRole(1) || Auth::user()->hasRole(2))
                        <td>
                           {{-- 'url' => 'foo/bar' --}}
                            {!! Form::open(['action' => ['Clases\ReservationController@destroy', $reservation->id], 'method' => 'delete', 'id' => 'delete'.$reservation->user->id]) !!}
                       {{--  {!! Form::open(['route' => ['reservation.destroy', $reservation->id], 'method' => 'delete', 'id'=>'delete'.$reservation->user->id]) !!} --}}
                              <input type="hidden" value="1" name="by_god">
                              <button class="btn btn-info btn-icon-only btn-danger sweet-user-delete" type="button" data-id="{{$reservation->user->id}}" data-name="{{$reservation->user->first_name}} {{$reservation->user->last_name}}"><i class="la la-trash"></i></button>
                           {!! Form::close() !!}
                        </td>
                     @elseif (Auth::user()->hasRole(3) && Auth::id() == $reservation->user->id)
                        <td>
                           {!! Form::open(['route' => ['reservation.destroy', $reservation->id], 'method' => 'delete', 'id'=> 'delete'.$reservation->user->id]) !!}
                           <input type="hidden" value="1" name="by_god">
                           <button class="btn btn-outline-info btn-sm btn-thick sweet-user-delete" type="button" data-id="{{$reservation->user->id}}" data-name="{{$reservation->user->first_name}} {{$reservation->user->last_name}}"><i class="la la-trash">Salir de Clase</i></button>
                           {!! Form::close() !!}
                        </td>
                     @endif
                     </tr>
                  @endforeach
                  </tbody>
               </table>
               {{-- {!! Form::close() !!} --}}
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
            <h5 class="modal-title" id="exampleModalLabel">Agregar Alumno</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body pr-0 pl-4 pb-5">
            <table id="students-table-search" class="table table-hover m-0 mr-0">
               <thead class="thead-default">
                  <tr>
                     <th width="80%">Alumnos</th>
                     <th width="20%">Accion</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($outclase as $usuario)
                  <tr>
                     <td>
                        {{-- <a class="media-img" href="javascript:;">
                           <img class="img-circle" src="{{$usuario->avatar}}" alt="image" width="54">
                        </a> --}}
                        {{-- <div class="img-avatar" style="background-image: url('{{$usuario->avatar}}');"></div> --}}
                        <div class="img-avatar" style="background-image:  @if ($usuario->avatar) url('{{$usuario->avatar}}') @else url('{{ asset('/img/default_user.png') }}') @endif"></div>
                        <span class="badge-{{$usuario->status_user->type}} badge-point"></span>
                        <a href="{{url('/users/'.$usuario->id)}}">{{$usuario->first_name}} {{$usuario->last_name}}</a>
                     </td>
                     <td>
                        {!! Form::open(['route' => ['reservation.store'], 'method' => 'post']) !!}
                        <input type="hidden" value="{{$usuario->id}}" name="user_id">
                        <input type="hidden" value="{{$clase->id}}" name="clase_id">
                        <input type="hidden" value="1" name="by_god">
                        <button type="button" class="btn btn-primary button-little" type="submit" onClick="this.form.submit();">Agregar</button>
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

<!-- Modal de confirmacion de clase-->
@include('clases.modals.asistencia', ['clase' => $clase])
<!-- END Modal de confirmacion de clase-->


@endsection

@section('css') {{-- stylesheet para esta vista --}}
  <link href="{{asset('css/datatables.min.css')}}" rel="stylesheet" />
@endsection



@section('scripts') {{-- scripts para esta vista --}}

<script>

$(document).ready(function(){
   $('#button-modal').on("click",function(){
      var id = {!!$clase->id!!};
      var op ="";
      var sid = $(this).val();

      $.ajax({
         type:'get',
         url: '/asistencia-modal/'+id,
         success: function(data2){
            op+='<table class="table table-striped">';
            op+='<tr><th width="60%">Alumno</th><th width="25%">Estado de reserva</th><th width="15%">Asistencia</th></tr>';
            for(var i=0;i<data2.length;i++){
               op += '<tr>';
               op += '<td><div class="img-avatar" style="background-image: url(\''+data2[i].avatar+'\')"></div><span class="badge-'+data2[i].user_status+' badge-point"></span>'+data2[i].alumno+'</td>'+
               // op += '<td><a class="media-img" href="javascripteeee:;"><img class="img-circle" src="'+data2[i].avatar+'" alt="image" width="54"></a><span class="badge-'+data2[i].user_status+' badge-point"></span>'+data2[i].alumno+'</td>'+
                     '<td><span class="badge badge-'+data2[i].tipo+' badge-pill">'+data2[i].estado_reserva.toUpperCase()+'</td>'+
                     '<td><label class="ui-switch switch-icon switch-large"><input name="asistencia[]" type="checkbox"  class="checkboxBla"><span></span></label><input hidden class="user_id_class" type="text" name="user_id[]" disabled value="'+data2[i].user_id+'"></td></tr>';
            }
            op+='</table>';
            $('#confirm-table').html(op);
            $('.checkboxBla').change(function(){
                  // alert('hola');
                   if(this.checked) {
                     // console.log('si');
                     $(this).parent().parent().find('[name="user_id[]"]').prop('disabled',false);

                   } else {
                     // console.log('no');
                     $(this).parent().parent().find('[name="user_id[]"]').prop('disabled',true);
                   }
            });
         },
         error: function(){
            console.log("Error Occurred");
         }
      });
   });
});







</script>

<script>



  // ELIMINAR A USUARIO DE LA CLASE
   $('.sweet-user-delete').click(function(e){
      var id = $(this).data('id');
      var row = $(this).parents('tr');
      var form = $(this).parents('form');
      var url = form.attr('action');
     // alert(form.attr('action'));
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
         $.post(url, form.serialize(), function(result){
            row.fadeOut();
            swal.close();
            $('#total').html(result.reserv_numbers);
            $('.easypie').data('easyPieChart').update(result.reserv_numbers*100/result.quota);
         }).fail(function(){
            $('#alert').html('no funcionó');
         });
      });
   });
  </script>

{{-- RESERVAR CUPO A LA CLASE (VISTA DEL USUARIO) --}}
<script>
 $('.sweet-user-join').click(function(e){
    var id = $(this).data('id');
    //alert(id);
      swal({
          title: "Confirma la reserva a la clase del "+$(this).data('name')+"?",
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonClass: 'btn-success',
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Confirmar',
          closeOnConfirm: false,
      },function(){
         $('form#user-join').submit();
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
            "pageLength": 12,
            "bLengthChange" : false, //thought this line could hide the LengthMenu
            "bpageLength": false,
            "bPaginate": false,
            "language": {
               "lengthMenu": "Mostrar _MENU_ elementos",
               "zeroRecords": "Sin Alumnos/as",
               "info": "Mostrando página _PAGE_ de _PAGES_",
               "infoEmpty": "Sin Alumnos",
               "infoFiltered": "(filtrado de _MAX_ registros totales)",
               "search": "Filtrar:"
            },
         });

         table_search = $('#students-table-search').DataTable({
            "paging": true,
            "ordering": true,
            "pageLength": 5,
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
         //foco al input al abrir el modal
         $('#user-assign').on('shown.bs.modal', function () {
             $('#students-table-search_filter input').focus();
         })
      });

  </script>
  {{--  End datatable --}}

  <script>
  $('.sweet-clase-delete').click(function(e){
    var id = $(this).data('id');
    //alert(id);
      swal({
          title: "Seguro desea cerrar la clase del: "+$(this).data('name')+"?",
          text: "(Se sacarán a todos los usuarios ya inscritos a esta clase)",
          type: 'warning',
          showCancelButton: true,
          confirmButtonClass: 'btn-danger',
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Cerrar Clase',
          closeOnConfirm: false,
      },function(){
        //redirección para eliminar clase
         $('form.clase-delete').submit();
      });
  });
  </script>

@endsection
