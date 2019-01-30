@extends('layouts.app')
@section('sidebar')
   @include('layouts.sidebar', ['page'=>'messages'])
@endsection

@section('content')
<div class="row justify-content-center">
   <div class="col-md-12">
      <div class="ibox ibox-fullheight" id="mailbox-container">
         <div class="ibox-head">
            <div class="ibox-title">Enviar Correo</div>
            <div class="ibox-tools">
               <button class="btn btn-success text-white" id="save_value" name="save_value">Redactar notificación</button>
            </div>
         </div>

         <div class="ibox-body messages">
            {{-- Inicio Botones selectores --}}
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
             {{-- Fin Botones Selectores --}}

             <table class="table table-hover table-inbox" id="table-inbox">

                <thead class="rowlinkx">
                   <tr>
                      <th>Nombre</th>
                      <th>Estado</th>
                      <th>id</th>
                   </tr>
                </thead>

                <tbody class="rowlinkx messages-body" data-link="row">
                   @foreach (App\Models\Users\User::all() as $user)
                   <tr class="{{$user->status_user->status_user}}" data-id="{{$user->id}}">
                      <td class="check-cell rowlink-skip row py-4">
                        <div class="pr-3 pl-1">
                          <a class="media-img" href="{{url('/users/'.$user->id)}}">
                             <div class="img-avatar img-avatar-messages align-self-start" style="background-image: @if ($user->avatar) url('{{$user->avatar}}') @else url('{{ asset('/img/default_user.png') }}') @endif"></div>
                          </a>
                        </div>
                        <div>
                          <a class="media-img" href="{{url('/users/'.$user->id)}}">
                            <h5>{{$user->first_name}} {{$user->last_name}}</h5>
                          </a>
                          <small class="text-muted">{{$user->email}}</small>
                          {{-- <br> --}}
                          <span class="badge badge-{{$user->status_user->type}} badge-pill">
                            {{$user->status_user->status_user}}
                          </span>
                        </div>

                      </td>
                      <td>{{$user->status_user_id}}</td>
                      <td id="users_ids">{{$user->id}}</td>
                   </tr>
                   @endforeach
                </tbody>

                {{-- <button class="btn btn-sm btn-outline-secondary btn-rounded" id="save_value" name="save_value">Enviar Correo</button> --}}

             </table>

          </div>
         </div>
      </div>


{{--  ................ M O D A L ...................... --}}

<div class="modal fade" id="user-assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content col-10">
         <div class="modal-header">
            <h5 class="modal-title"> Notificaciones Push</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         {!! Form::open(['url' => ['/notifications'], 'method' => 'post']) !!}
         <div class="modal-body messages-modal-body">
            <tbody>
               <label class="col-form-label">Asunto</label>
               <input type="text" value="" name="title" required>
               <label class="col-form-label">Contenido</label>
               <textarea name="message" required></textarea>
               <button type="button" class="btn btn-primary" type="submit" onClick="this.form.submit();">Enviar notificación</button>
            </tbody>
            <div id="form-input">

            </div>
         </div>
         {!! Form::close() !!}
      </div>
   </div>
</div>

{{--       <div class="col-6">
         <div class="ibox">
           <div class="ibox-head">
             <div class="ibox-title">
               Notificaciones a la App
             </div>
           </div>
           {!!Form::open(['url' => ['/notifications'], 'method' => 'post'])!!}
           	<div class="ibox-body">
               <div class="row">
                  <div class="col-sm-12">
                     <label class="font-normal">Asunto</label>
                     <input type="text" class="form-control" name="title">
                  </div>
               </div>
               <br>
               <div class="row">
                  <div class="col-sm-12">
          			   <label class="font-normal">Contenido</label>
                     <textarea rows="8" class="form-control" name="message"></textarea>
                 	</div>
              	</div>
     	         <br>
               <button class="btn btn-primary" type="submit">Enviar notificación</button>
       		</div>
        		{!! Form::close() !!}
      	</div>
      </div> --}}
   </div>

@endsection


@section('css') {{-- stylesheet para esta vista --}}
	<link href="{{asset('css/summernote.css')}}" rel="stylesheet" />
@endsection



@section('scripts') {{-- scripts para esta vista --}}

	<script src="{{asset('/js/summernote.min.js')}}"></script>

  <script src="{{ asset('js/datatables.min.js') }}"></script>
   <script >
   $(document).ready(function() {
      table = $('#table-inbox').DataTable({
         "paging": true,
         "ordering": true,
         "select": true,
         "language": {
            "lengthMenu": "<p>Mostrar</p> _MENU_ <p>elementos</p>",
            "zeroRecords": "Sin resultados",
            "info": " ",
            "infoEmpty": "Sin resultados",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Filtrar:  "
         },
         "columnDefs": [
            {
               "targets": [ 1,2 ],
               "visible": false,
               "searchable": true
            }
         ],
      });
   });

   $('button.user-filter').on("click", function(){
      table.columns( 1 ).search( $(this).data('status') ).draw();
   });
   </script>

   <script>
   $(function(){
      table = $('#table-inbox').DataTable();
      var form = document.getElementById('form-input');

      $('#save_value').click(function(){
         $('.form-input-user').remove();
         table.rows( {search:'applied'} ).data().each(function(value, index){
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "users_id[]";
            input.value = value[2];
            input.className = "form-input-user";
            form.appendChild(input);
            console.log(input);
         });
      $('#user-assign').modal('show');
      });
   });
   </script>>

	<script>
	// Bootstrap datepicker
	$('#start_date .input-group.date').datepicker({
  		todayBtn: "linked",
  		keyboardNavigation: false,
  		forceParse: false,
  		calendarWeeks: true,
  		autoclose: true
	});
	</script>

	<script>
	// Bootstrap datepicker
	$('#finish_date .input-group.date').datepicker({
  		todayBtn: "linked",
  		keyboardNavigation: false,
  		forceParse: false,
  		calendarWeeks: true,
  		autoclose: true
	});
	</script>

@endsection

{{--  if($('input[type=checkbox]').prop('checked');){
      console.log('si esta checkeado');
   } --}}
