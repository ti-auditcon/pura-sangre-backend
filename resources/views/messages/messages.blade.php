@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar', ['page'=>'messages'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox">
 {{--        <div class="ibox-head"> --}}
        <div class="ibox" id="mailbox-container">
          <div class="flexbox-b p-4">
            <h5 class="font-strong m-0 mr-3">Enviar Correo</h5>
            <span id="counter-selected" style="display: none;">Seleccionados
              <span class="font-strong text-danger ml-2" id="counter-count">15</span>
            </span>
          </div>
          <div class="flexbox px-4 py-3 bg-primary-50">
            <div class="flexbox-b">
              <label class="checkbox checkbox-primary check-single pt-1">
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
        {{--           <div id="inbox-actions">
                      <button class="btn btn-sm btn-transparent btn-primary btn-icon-only btn-circle disabled" data-action="mark_as_read" data-toggle="tooltip" data-original-title="Mark as read"><i class="la la-eye"></i></button>
                      <button class="btn btn-sm btn-transparent btn-pink btn-icon-only btn-circle disabled" data-action="mark_as_important" data-toggle="tooltip" data-original-title="Mark as important"><i class="la la-star-o"></i></button>
                      <button class="btn btn-sm btn-transparent btn-info btn-icon-only btn-circle disabled" data-action="reply" data-toggle="tooltip" data-original-title="Reply"><i class="la la-reply"></i></button>
                      <button class="btn btn-sm btn-transparent btn-danger btn-icon-only btn-circle disabled" data-action="delete" data-toggle="tooltip" data-original-title="Delete"><i class="la la-trash-o"></i></button>
                  </div> --}}
               {{--    <span class="counter-selected ml-1" hidden="">Seleccionados
                      <span class="font-strong text-warning counter-count">3</span>
                  </span> --}}
           
          <div class="input-group-icon input-group-icon-left">
            <span class="input-icon input-icon-right font-16"><i class="ti-search"></i></span>
            <input class="form-control form-control-rounded" id="key-search" type="text" placeholder="Search ...">
          </div>
          </div>
          <table class="table table-hover table-inbox" id="table-inbox">
            <thead class="rowlinkx">
              <tr>
                <th width="80%">Nombre</th>
                <th width="20%">Acciones</th>
              </tr>
            </thead>
            <tbody class="rowlinkx" data-link="row">
              @foreach (App\Models\Users\User::all()->take(15) as $user)
              <tr class="{{$user->status_user->status_user}}" data-id="1">
                <td class="check-cell rowlink-skip">
                  <label class="checkbox checkbox-primary check-single">
                    <input class="mail-check" type="checkbox">
                    <span class="input-span"></span>
                  </label>
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
            <button class="btn btn-sm btn-outline-secondary btn-rounded" id="save_value" name="save_value">Enviar Correo</button>
          </table>
          <ul class="pagination justify-content-end p-4">
            <li class="page-item">
              <a class="page-link page-link-solid" href="javascript:;" aria-label="First">
                <span aria-hidden="true"><i class="la la-angle-double-left"></i></span>
              </a>
            </li>
            <li class="page-item">
              <a class="page-link page-link-solid" href="javascript:;" aria-label="Previous">
                <span aria-hidden="true"><i class="la la-angle-left"></i></span>
              </a>
            </li>
            <li class="page-item active">
              <a class="page-link" href="javascript:;">1</a>
            </li>
            <li class="page-item">
              <a class="page-link" href="javascript:;">2</a>
            </li>
            <li class="page-item">
              <a class="page-link" href="javascript:;">3</a>
            </li>
            <li class="page-item">
              <a class="page-link" href="javascript:;">4</a>
            </li>
            <li class="page-item">
              <a class="page-link" href="javascript:;"><i class="la la-ellipsis-h"></i></a>
            </li>
            <li class="page-item">
              <a class="page-link page-link-solid" href="javascript:;" aria-label="Next"><i class="la la-angle-right"></i></a>
            </li>
            <li class="page-item">
              <a class="page-link page-link-solid" href="javascript:;" aria-label="Last"><i class="la la-angle-double-right"></i></a>
            </li>
          </ul>
      </div>


          {{-- <div class="flexbox-b">
          <label class="checkbox checkbox-primary check-single pt-1">
              <input type="checkbox" data-select="all">
              <span class="input-span"></span>
          </label>
          <div class="btn-group mr-2" style="margin-left: -4px;">
              <button class="btn btn-transparent btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-angle-down"></i></button>
              <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                  <li data-select="read">
                      <a class="dropdown-item">Select read</a>
                  </li>
                  <li data-select="unread">
                      <a class="dropdown-item">Select unread</a>
                  </li>
                  <li class="dropdown-divider"></li>
                  <li data-select="clear">
                      <a class="dropdown-item">Clear selection</a>
                  </li>
              </ul>
          </div>
          <div id="inbox-actions">
              <button class="btn btn-sm btn-transparent btn-primary btn-icon-only btn-circle disabled" data-action="mark_as_read" data-toggle="tooltip" data-original-title="Mark as read"><i class="la la-eye"></i></button>
              <button class="btn btn-sm btn-transparent btn-pink btn-icon-only btn-circle disabled" data-action="mark_as_important" data-toggle="tooltip" data-original-title="Mark as important"><i class="la la-star-o"></i></button>
              <button class="btn btn-sm btn-transparent btn-info btn-icon-only btn-circle disabled" data-action="reply" data-toggle="tooltip" data-original-title="Reply"><i class="la la-reply"></i></button>
              <button class="btn btn-sm btn-transparent btn-danger btn-icon-only btn-circle disabled" data-action="delete" data-toggle="tooltip" data-original-title="Delete"><i class="la la-trash-o"></i></button>
          </div>
          <span class="counter-selected ml-1" hidden="">Selected
              <span class="font-strong text-warning counter-count">3</span>
          </span>
      </div> --}}

         {{--  <label class="checkbox checkbox-primary check-single pt-1">
            <input type="checkbox" data-select="all">
            <span class="input-span"></span>
          </label> --}}
       {{--    <div class="ibox-title">Alumnos</div>
          <div class="input-group-icon input-group-icon-left">
            <span class="input-icon input-icon-right font-16"><i class="ti-search"></i></span>
            <input class="form-control form-control-rounded" id="key-search" type="text" placeholder="Buscar ...">
            </div>
        </div>
        <div class="ibox-body">
          <div class="">
            <ul class="media-list media-list-divider mr-2 scroller" data-height="580px">
              @foreach (App\Models\Users\User::all()->take(15) as $user)
              <li class="media align-items-center">
                <label class="checkbox checkbox-primary">
                  <input class="mail-check" name="users[]" value="{{$user->id}}" type="checkbox">
                  <span class="input-span"></span>
                </label>
                <a class="media-img" href="{{url('/users/'.$user->id)}}">
                  <img class="img-circle" src="{{url($user->avatar)}}" alt="image" width="54">
                </a>
                <div class="media-body d-flex align-items-center">
                  <div class="flex-1">
                    <div class="media-heading">{{$user->fist_name}} {{$user->last_name}}<span class="badge badge-{{$user->status_user->type}} badge-pill ml-2">{{$user->status_user->status_user}}</span></div>
                    <small class="text-muted">{{$user->email}} </small></div>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
          <button class="btn btn-sm btn-outline-secondary btn-rounded" id="save_value" name="save_value">Aplicar</button>
        </div> --}}

  {{--   </div> --}}
  </div>


{{--  ................ M O D A L ...................... --}}

  <div class="modal fade" id="user-assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Redactar correo</h5>
       {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button> --}}
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
  <script>
    $(document).ready(function() {
      $('#table-inbox').DataTable({
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

  <script src="{{ asset('js/summernote.min.js') }}"></script>
  <script>
    $(function() {
      $('#summernote').summernote({
       height: 350, 
      });
    });
  </script>

  <script>
    $(function(){
      $('#save_value').click(function(){
        console.log('bla');
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
        console.log(val);
        $('form#form-val #users_id').val(val);
        $('#user-assign').modal('show');
      });
    });
  </script>>

  <script>
    $(function() {
    //'use strict';
    var actions = $('#inbox-actions .btn'),
        rows = $('#table-inbox tr')

    $("input[data-select='all']").change(function(){
        $(this).prop('checked') 
            ? (rows.find('.mail-check').prop('checked',true), actions.removeClass('disabled'))
            : (rows.find('.mail-check').prop('checked',false), actions.addClass('disabled'))        
        checkSelectedCount();
    });
    $("li[data-select='activos']").click(function(){
        rows.find('.mail-check').prop('checked',false);
        var r = rows.filter('.Activo').find('.mail-check');
        r.prop('checked',true);
        if(r.length) actions.removeClass('disabled');
        checkSelectedCount();
    });
    $("li[data-select='inactivos']").click(function(){
        rows.find('.mail-check').prop('checked',false);
        var r = rows.filter('.Inactivo').find('.mail-check');
        r.prop('checked',true);
        if(r.length) actions.removeClass('disabled');
        checkSelectedCount();
    });
    $("li[data-select='pruebas']").click(function(){
        rows.find('.mail-check').prop('checked',false);
        var r = rows.filter('.Prueba').find('.mail-check');
        r.prop('checked',true);
        if(r.length) actions.removeClass('disabled');
        checkSelectedCount();
    });
    $("li[data-select='clear']").click(function(){
        rows.find('.mail-check').prop('checked',false);
        actions.addClass('disabled');
        checkSelectedCount();
    });
    $('.mail-check').change(function(){
        if($(this).prop('checked')) actions.removeClass('disabled');
        else if(!rows.find('.mail-check:checked').length) actions.addClass('disabled');
        checkSelectedCount();
    });

    // $("li[data-select='read']").click(function(){
    //     rows.find('.mail-check').prop('checked',false);
    //     var r = rows.not('.unread').find('.mail-check');
    //     r.prop('checked',true);
    //     if(r.length) actions.removeClass('disabled');
    //     checkSelectedCount();
    // });
    // $("li[data-select='unread']").click(function(){
    //     rows.find('.mail-check').prop('checked',false);
    //     var r = rows.filter('.unread').find('.mail-check');
    //     r.prop('checked',true);
    //     if(r.length) actions.removeClass('disabled');
    //     checkSelectedCount();
    // });
    
    // Actions
    // $(".btn[data-action='mark_as_read']").click(function(){
    //     var rows = $('.mail-check:checked').parents('tr.unread');
    //     rows.removeClass('unread');
    // });
    // $(".btn[data-action='delete']").click(function(){
    //     var rows = $('.mail-check:checked').parents('tr');
    //     rows.remove();
    //     checkSelectedCount();
    // });

    function checkSelectedCount() {
        var count = 0;
        $('#table-inbox tr').find('.mail-check').each(function(){
            if ($(this).is(":checked")) count++;
        });
        if(count) {
            $('#counter-selected').show().find('#counter-count').html(count);
        }
        else $('#counter-selected').hide();
    }

});

  </script>
@endsection
