@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar')
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-4">
        {{-- //////////////////////////////////////////////////////// --}}
        {{--                                                          --}}
        {{-- /////////////////     Clase details   ////////////////// --}}
        {{--                                                          --}}
        {{-- //////////////////////////////////////////////////////// --}}
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">
                    <h4>
                        Clase de {{ $clase->claseType->clase_type }}
                    </h4>
                </div>
            
                <div class="ibox-tools">
                    @if (Carbon\Carbon::parse($clase->date)->gte(today()) && in_array(1, $auth_roles))
                        {!! Form::open([
                            'route' => ['clases.destroy', $clase->id],
                            'method' => 'delete', 'class' => 'clase-delete'
                        ]) !!}
                        {!! Form::close() !!}
                        
                        <button
                            class="btn btn-danger sweet-clase-delete"
                            data-id="{{ $clase->id }}"
                            data-name="{{ Carbon\Carbon::parse($clase->date)->format('d-m-Y') }}">
                            <i></i>
                            Eliminar Clase
                        </button>
                    @endif
                </div>
            </div>
            <div class="ibox-body">
                <div class="clase">
                    <div class="card">
                        <div class="row mb-2">
                            
                            <div class="col-12 text-muted">Fecha:</div>
                            
                            <div class="col-12">{{ Carbon\Carbon::parse($clase->date)->format('d-m-Y') }}</div>
                        
                        </div>
                    
                        <div class="row mb-2">
                            <div class="col-12 text-muted">Horario:</div>
                            
                            <div class="col-12">
                                {{ Carbon\Carbon::parse($clase->start_at)->format('H:i') }} - {{ Carbon\Carbon::parse($clase->finish_at)->format('H:i') }}
                            </div>
                        
                        </div>

                        <div class="row">
                         
                            <div class="col-12 text-muted">Coach:</div>
                         
                            <div class="col-12">{{ $clase->block->user->first_name }} {{ $clase->block->user->last_name }}</div>
                      
                        </div>
                      
                        <br/>
                    </div>
                    <div class="clase-graphics pt-2">
                        <div class="canvas-item">
                            
                            <div
                                id="porcentaje"
                                class="easypie col"
                                data-percent="{{ ($reservation_count * 100) / $clase->quota }}"
                                data-bar-color="#5c6bc0"
                                data-size="70"
                                data-line-width="8"
                            >
                            </div>
                        
                        </div>
                        
                        <div class="data-item">
                            <div class="row m-0">
                                
                                <div class="col-12 p-0 m-0">
                                   
                                   <span class="easypie-data font-26 text-primary icon-people"><i class="ti-user"></i></span>
                                   
                                   <h3 id="total" class="font-strong text-primary">{{ $reservation_count }}</h3>

                                   <h3 class="font-strong text-primary">/{{ $clase->quota }}</h3>
                                
                                </div>
                                
                                <div class="col-12 p-0 m-0">
                                   
                                   <div class="text-muted">Cupos tomados</div>
                                
                                </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- //////////////////////////////////////////////////////// --}}
        {{--                                                          --}}
        {{-- /////////////////     Clase Stages    ////////////////// --}}
        {{--                                                          --}}
        {{-- //////////////////////////////////////////////////////// --}}
        <div class="ibox">
            <div class="ibox-head">

                <div class="ibox-title">Rutina de la Clase</div>
          
                <div class="ibox-tools">

                    <a class="ibox-collapse">

                        <i class="la la-angle-down" style="color: black;"></i>

                    </a>
                </div>

            </div>
            
            <div class="ibox-body">
                <div class="row">
                    @forelse($stages as $st)
                        <div class="col-12 col-md-4 col-xl-12 mb-4">
                            <h5 class="font-strong">{{ $st->stage_type->stage_type }}</h5>

                            <div class="py-2">
                                <textarea
                                    name="{{ $st->id }}"
                                    class="form-control form-control-solid p-4"
                                    rows="10"
                                    disabled
                                >
                                    {{ $st->description }}
                                </textarea>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 col-md-4 col-xl-12 mb-4">
                            Esta clase aún no tiene una rutina agregada
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- //////////////////////////////////////////////////////// --}}
    {{--                                                          --}}
    {{-- /////////////////    Students table   ////////////////// --}}
    {{--                                                          --}}
    {{-- //////////////////////////////////////////////////////// --}}
    <div class="col-12 col-xl-8">
        <div class="ibox">
            <div class="ibox-head">
            
                <div class="ibox-title">Alumnos de esta clase</div>
                
                <div class="ibox-tools">
                
                    @if (in_array(1, $auth_roles) || in_array(2, $auth_roles))

                        @if($clase->start_at <= now()->subMinute()->format('H:i:s') && $clase->date == toDay()->format('Y-m-d'))

                            <button id="button-modal" class="btn btn-warning btn-icon-only" data-toggle="modal" data-target="#confirm-assistance-modal"><i class="la la-check-square"></i></button>
                        @endif

                        @if (in_array(1, $auth_roles))

                            <button id="assign-button" class="btn btn-success" data-toggle="modal" data-target="#user-assign">
                                Agregar un Alumno
                            </button>

                        @endif

                    @else

                        {!! Form::open(['route' => ['reservation.store'], 'method' => 'post' ,'id' => 'user-join']) !!}
                            <input type="hidden" value="{{ Auth::user()->id }}" name="user_id">
                            
                            <input type="hidden" value="{{ $clase->id }}" name="clase_id">
                            
                            <button class="btn btn-success sweet-user-join" data-id="{{ $clase->id }}" data-name="{{ $clase->date }}">
                                <i></i>Reservar esta clase
                            </button>
                        {!! Form::close() !!}

                    @endif
                </div>
            </div>

            <div class="ibox-body pb-5">
                <div class="table-responsive">
                
                    <table id="students-table" class="table table-hover">
                  
                        <thead class="thead-default">
                            <tr>
                                <th width="80%">Nombre</th>
                                <th width="10%">Estado</th>
                                <th width="10%">Acciones</th>
                                <th width="10%">otro</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($reservations as $reservation)
                             <tr>
                                <td>
                                   <div class="img-avatar" style="background-image:  @if ($reservation->user->avatar) url('{{$reservation->user->avatar}}') @else url('{{ asset('/img/default_user.png') }}') @endif"></div>
                                   <span class="badge-{{$reservation->user->status_user->type}} badge-point"></span>
                                   <a @if (in_array(1, $auth_roles) || in_array(2, $auth_roles)) href="{{url('/users/'.$reservation->user->id)}}" @endif>
                                      {{$reservation->user->first_name}} {{$reservation->user->last_name}}
                                   </a>
                                </td>
                                <td>
                                   <span id="status-user-badge-{{ $reservation->id }}" class="badge badge-{{ $reservation->reservation_status->type }} badge-pill">{{ strtoupper($reservation->reservation_status->reservation_status) }}</span>
                                   <span id="status-user-badge-two-{{ $reservation->id }}" style="display: none" class="badge badge-success badge-pill">CONFIRMADA</span>
                                </td>

                             @if (in_array(1, $auth_roles) || in_array(2, $auth_roles))
                                <td>
                                   {!! Form::open(['action' => ['Clases\ReservationController@destroy', $reservation->id], 'method' => 'delete', 'id' => 'delete'.$reservation->user->id]) !!}

                                      <input type="hidden" value="1" name="by_god">
                                      <button class="btn btn-info btn-icon-only btn-danger sweet-user-delete" type="button" data-id="{{$reservation->user->id}}" data-name="{{$reservation->user->first_name}} {{$reservation->user->last_name}}"><i class="la la-trash"></i></button>
                                   {!! Form::close() !!}

                                   @if ($reservation->reservation_status_id === 1)
                                     {!! Form::open(['action' => ['Clases\ReservationController@confirm', $reservation->id], 'method' => 'POST', 'id' => 'update'.$reservation->user->id]) !!}
                                      <input type="hidden" value="1" name="by_god">
                                      <button class="btn btn-info btn-icon-only btn-success sweet-user-confirm" type="button" data-id="{{$reservation->user->id}}" data-name="{{$reservation->user->first_name}} {{$reservation->user->last_name}}"><i class="la la-check-circle"></i></button>
                                    {!! Form::close() !!}
                                   @endif
                                   
                                </td>
                             @elseif (in_array(3, $auth_roles) && Auth::id() == $reservation->user->id)
                                <td>
                                   {!! Form::open(['route' => ['reservation.destroy', $reservation->id], 'method' => 'delete', 'id'=> 'delete'.$reservation->user->id]) !!}
                                   <input type="hidden" value="1" name="by_god">
                                   <button class="btn btn-outline-info btn-sm btn-thick sweet-user-delete" type="button" data-id="{{$reservation->user->id}}" data-name="{{$reservation->user->first_name}} {{$reservation->user->last_name}}"><i class="la la-trash">Salir de Clase</i></button>
                                   {!! Form::close() !!}
                                </td>
                             @endif
                                <td>{{ $reservation->updated_at }}</td>
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
                <h5 class="modal-title" id="exampleModalLabel">Agregar Alumno</h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pl-4 pb-5">
                <table id="students-table-search" class="table table-hover">
                    <thead class="thead-default">
                        <tr>
                            <th width="90%">Alumnos</th>
                            <th width="10%">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outclase as $usuario)
                        <tr>
                            <td>
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
      var op = "";
      var sid = $(this).val();

      $.ajax({
         type:'get',
         url: '/asistencia-modal/'+id,
         success: function(data2){
            op+='<table class="table table-striped">';
            op+='<tr><th width="60%">Alumno</th><th width="25%">Estado de reserva</th><th width="15%">Asistencia</th></tr>';
            for(var i=0;i<data2.length;i++){
               op += '<tr>';
                 if (data2[i].estado_reserva == 'Consumida') {
                  var estado = 'checked';
                  var disabled = '';
               }else{
                  var estado = '';
                  var disabled = 'disabled';
               }
               op += '<td><div class="img-avatar" style="background-image: url(\''+data2[i].avatar+'\')"></div><span class="badge-'+data2[i].user_status+' badge-point"></span>'+data2[i].alumno+' '+ data2[i].birthdate +'</td>'+

                     '<td><span class="badge badge-'+data2[i].tipo+' badge-pill">'+data2[i].estado_reserva.toUpperCase()+'</td>'+
                     '<td><label class="ui-switch switch-icon switch-large"><input name="asistencia[]" '+estado+' type="checkbox"  class="checkboxBla"><span></span></label><input hidden class="user_id_class" type="text" name="user_id[]" '+disabled+' value="'+data2[i].user_id+'"></td></tr>';
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
      // var id = $(this).data('id');
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


     // CONFIRM A USUARIO DE LA CLASE
   $('.sweet-user-confirm').click(function(e){
      var id = $(this).data('id');
      var row = $(this).parents('tr');
      var form = $(this).parents('form');
      var url = form.attr('action');
     // alert(form.attr('action'));
      swal({
          title: "Desea confirmar a: "+$(this).data('name')+" en esta clase?",
          text: "",
          type: 'success',
          showCancelButton: true,
          confirmButtonClass: 'btn-success',
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Confirmar',
          closeOnConfirm: false,
      },function(){
        console.log(url);
         $.post(url, form.serialize(), function(result){
          console.log(result.reservation_id);
            form.hide();
            $('#status-user-badge-'+result.reservation_id).hide();
            $('#status-user-badge-two-'+result.reservation_id).show();
            swal.close();
            toastr.success('Confirmado correctamente');
         }).fail(function(){
            $('#alert').html('No se ha podido confirmar, por favor contactese con el encargado');
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
            "order": [[ 1, "asc" ], [3, 'asc']],
            "columnDefs": [
               {
                   "targets": [ 3 ],
                   "visible": false,
                   "searchable": false
               }
            ],
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
               "search": "Filtrar:",
               "paginate": {
                  "first": "Primero",
                  "last": "Último",
                  "next": "Siguiente",
                  "previous": "Anterior"
               },
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
               "search": "Buscar Alumno:",
               "paginate": {
                  "first": "Primero",
                  "last": "Último",
                  "next": "Siguiente",
                  "previous": "Anterior"
               },
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

      swal({
          title: "Seguro desea ELIMINAR la clase del: " + $(this).data('name') + "?",
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
