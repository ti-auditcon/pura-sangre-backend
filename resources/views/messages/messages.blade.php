@extends('layouts.app')
@section('sidebar')
   @include('layouts.sidebar', ['page'=>'messages'])
@endsection

@section('content')
   <div class="row justify-content-center">
      <div class="col-8">
         <div class="ibox" id="mailbox-container">
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
               <div class="flexbox-b p-4">
                  <h5 class="font-strong m-0 mr-3">Enviar Correo</h5>
                  <span id="counter-selected" style="display: none;">Seleccionados
                     <span class="font-strong text-danger ml-2" id="counter-count">15</span>
                  </span>
               </div>
            <table class="table table-hover table-inbox" id="table-inbox">
               <thead class="rowlinkx">
                  <tr>
                     <th width="100%">Nombre</th>
                     <th width="100%">Estado</th>
                     <th width="100%">id</th>
                  </tr>
               </thead>
               <tbody class="rowlinkx" data-link="row">
                  @foreach (App\Models\Users\User::all() as $user)
                  <tr class="{{$user->status_user->status_user}}" data-id="{{$user->id}}">
                     <td class="check-cell rowlink-skip">
                        <a class="media-img" href="{{url('/users/'.$user->id)}}">
                           <img class="img-circle" src="{{url($user->avatar)}}" alt="image" width="54">
                        </a>
                        <span class="badge badge-{{$user->status_user->type}} badge-pill ml-2">{{$user->status_user->status_user}}
                        </span>
                        {{$user->first_name}} {{$user->last_name}}
                        <small class="text-muted">{{$user->email}}</small></div>
                     </td>
                     <td>{{$user->status_user_id}}</td>
                  
                     <td id="users_ids">{{$user->id}}</td>
                  </tr>
                  @endforeach
               </tbody>
               <button class="btn btn-sm btn-outline-secondary btn-rounded" id="save_value" name="save_value">Enviar Correo</button>
            </table>
         </div>
      </div>
   </div>


{{--  ................ M O D A L ...................... --}}

<div class="modal fade" id="user-assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Redactar correo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         {!! Form::open(['url' => ['messages/send'], 'method' => 'post', 'id' => 'form-val']) !!}
         <div class="modal-body">
            <tbody>
               <label class="col-form-label">Asunto</label>
               <input type="text" value="" name="subject" required>
               <label class="col-form-label">Contenido</label>
               <textarea name="text" required></textarea>
               <button type="button" class="btn btn-primary" type="submit" onClick="this.form.submit();">Enviar Correo</button>
            </tbody>
            <div id="form-input">
               
            </div>
         </div>
         {!! Form::close() !!}
      </div>
   </div>
</div>


@endsection


@section('css') {{-- stylesheet para esta vista --}}
<link href="{{asset('css/summernote.css')}}" rel="stylesheet" />
@endsection



@section('scripts') {{-- scripts para esta vista --}}

  <script src="{{ asset('js/datatables.min.js') }}"></script>
   <script >
   $(document).ready(function() {
      table = $('#table-inbox').DataTable({
         "paging": true,
         "ordering": true,
         "select": true,
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
               "targets": [ 1 ],
               "visible": false,
               "searchable": true
            }
         ],
      });
   });

   $('button.user-filter').on("click", function(){
      // var form = document.getElementById('form-val');
      // form.remove(input);
      table.columns( 1 ).search( $(this).data('status') ).draw();
      // console.log(input);
   });
   </script>

   <script>

   $(function(){
      table = $('#table-inbox').DataTable();
      var form = document.getElementById('form-input');
      
      // input.value = $thisids;
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
        // console.log($('#form-input input[name=users_id]').length);
      // form.appendChild(input);
         
      $('#user-assign').modal('show');

         // $(':').each(function(i){
         //    var form = document.getElementBy('form-val');
         //    var article = document.getElementById('electriccars');
         //    var input = document.createElement("input");
         //    input.type = "hidden";
         //    input.name = "users_id[]";
         //    input.value = $(this).val();
         //    form.appendChild(input);
         // });
         // $('form#form-val #users_id').val(val);
         // $('#user-assign').modal('show');
      });
   });
   </script>>

@endsection