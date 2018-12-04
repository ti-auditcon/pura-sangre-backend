@extends('layouts.app')
@section('sidebar')
   @include('layouts.sidebar', ['page'=>'messages'])
@endsection

@section('content')
   <div class="row justify-content-center">
      <div class="col-8">
            <div class="ibox" id="mailbox-container">
               <div class="flexbox-b p-4">
                  <h5 class="font-strong m-0 mr-3">Enviar Correo</h5>
                  <span id="counter-selected" style="display: none;">Seleccionados
                     <span class="font-strong text-danger ml-2" id="counter-count">15</span>
                  </span>
               </div>
            {{--    <div class="flexbox px-4 py-3 bg-primary-50">
                  <div class="flexbox-b">
                     <label id="all_id" class="checkbox checkbox-primary check-single pt-1">
                        <input type="checkbox" data-select="all">
                        <span class="input-span"></span>
                     </label>
                     <div class="btn-group mr-2" style="margin-left: -4px;">
                        <button class="btn btn-transparent btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-angle-down"></i></button>
                        <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                           <li data-select="activos"><a class="dropdown-item">Activos</a></li>
                           <li data-select="inactivos"><a class="dropdown-item">Inactivos</a></li>
                           <li data-select="pruebas"><a class="dropdown-item">Prueba</a></li>
                           <li class="dropdown-divider"></li>
                           <li data-select="clear"><a class="dropdown-item">Limpiar Seleccion</a></li>
                        </ul>
                    </div>
                  </div>
               </div> --}}
            <table class="table table-hover table-inbox" id="table-inbox">
               <thead class="rowlinkx">
                  <tr>
                     <th width="100%">Nombre</th>
                  </tr>
               </thead>
               <tbody class="rowlinkx" data-link="row">
                  @foreach (App\Models\Users\User::all() as $user)
                  <tr class="{{$user->status_user->status_user}}" data-id="1">
                     <td class="check-cell rowlink-skip">
                   {{--      <label class="checkbox checkbox-primary checkbox-select check-single">
                           <input class="mail-check" type="checkbox">
                           <span class="input-span"></span>
                        </label> --}}
                        <a class="media-img" href="{{url('/users/'.$user->id)}}">
                           <img class="img-circle" src="{{url($user->avatar)}}" alt="image" width="54">
                        </a>
                        <span class="badge badge-{{$user->status_user->type}} badge-pill ml-2">{{$user->status_user->status_user}}</span>
                        {{$user->first_name}} {{$user->last_name}}
                        <small class="text-muted">{{$user->email}} </small></div>
                     </td>
                  </tr>
                  @endforeach
               </tbody>
               <button class="btn btn-sm btn-outline-secondary btn-rounded" disabled id="save_value" name="save_value">Enviar Correo</button>
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
      });

      table.on('search.dt', function() {
         //number of filtered rows
         console.log(table.rows( { filter : 'applied'} ).nodes().length);
         //filtered rows data as arrays
         console.log(table.rows( { filter : 'applied'} ).data('#users_id'));                                  
      }) 
   });
   </script>
   <script>
   $(function(){
      $('#save_value').click(function(){
         // console.log('bla');
         var val = [];
         $(':checkbox:checked').each(function(i){
            var form = document.getElementById('form-val');
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "users_id[]";
            input.value = $(this).val();
            // val.push('user_id' => $(this).val());
            form.appendChild(input);
         });
         // console.log(val);
         $('form#form-val #users_id').val(val);
         $('#user-assign').modal('show');
      });
   });
   </script>>

{{--   <script src="{{ asset('js/summernote.min.js') }}"></script>
   <script>
      $(function() {
         $('#summernote').summernote({
            height: 350, 
         });
      });
   </script> --}}


 {{--   <script>
   $(function() {
      var actions = $('#inbox-actions .btn');
      var count = 0;
     

      $("input[data-select='all']").change(function(){
          rows = $('#table-inbox tr');
         $(this).prop('checked')
            ? (rows.find('.mail-check').prop('checked',true), actions.removeClass('disabled'), $("#save_value").prop('disabled', false))
            : (rows.find('.mail-check').prop('checked',false), actions.addClass('disabled'))
         checkSelectedCount();
      });
      $("li[data-select='activos']").click(function(){
         

         rows.find('.mail-check').prop('checked',false);
         var r = rows.filter('.Activo').find('.mail-check');
         r.prop('checked',true);
         $("#save_value").prop('disabled', false);
         if(r.length) actions.removeClass('disabled');
         checkSelectedCount();

         // count = $("#table-inbox tr.sendeable").length;
         console.log(count);
      });
      $("li[data-select='inactivos']").click(function(){
         rows.find('.mail-check').prop('checked',false);
         var r = rows.filter('.Inactivo').find('.mail-check');
         r.prop('checked',true);
         $("#save_value").prop('disabled', false);
         if(r.length) actions.removeClass('disabled');
         checkSelectedCount();
      });
      $("li[data-select='pruebas']").click(function(){
         rows.find('.mail-check').prop('checked',false);
         var r = rows.filter('.Prueba').find('.mail-check');
         r.prop('checked',true);
         $("#save_value").prop('disabled', false);
         if(r.length) actions.removeClass('disabled');
         checkSelectedCount();
      });
      $("li[data-select='clear']").click(function(){
         rows.find('.mail-check').prop('checked',false);
         $("#save_value").prop('disabled', true);
         // id="all_id" 
         actions.addClass('disabled');
         checkSelectedCount();
      });
      $('.mail-check').change(function(){
         if($(this).prop('checked')) actions.removeClass('disabled');
         else if(!rows.find('.mail-check:checked').length) actions.addClass('disabled');
         checkSelectedCount();
      });

      //check
$('.checkbox input').prop('checked',false);
   $("#save_value").prop('disabled', true);

   $("#table-inbox tr .checkbox-select").change(function() {
      $(this).parent().parent().toggleClass("sendeable");
      
      checkSelectedCount();
      console.log(count);
      // if(count == 0) {
      //    $("#save_value").prop('disabled', true);
      // }
      if(count > 0) {
         $("#save_value").prop('disabled', false);
      }
      else {
         $("#save_value").prop('disabled', true);
      }
   });
   //count
      function checkSelectedCount() {
         count = 0;
         $('#table-inbox tr').find('.mail-check').each(function(){
            if ($(this).is(":checked")){
               count++;
               $(this).parent().parent().parent().toggleClass("sendeable");
            }
             
         });
         if(count) {

            $('#counter-selected').show().find('#counter-count').html(count);
         }
         else {
            $('#counter-selected').hide();
         }
      }
   });
   </script> --}}

@endsection

{{--  if($('input[type=checkbox]').prop('checked');){
      console.log('si esta checkeado');
   } --}}