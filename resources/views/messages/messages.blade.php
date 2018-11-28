@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'messages'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox">
        <div class="ibox-head">
          <label class="checkbox checkbox-primary check-single pt-1">
            <input type="checkbox" data-select="all">
            <span class="input-span"></span>
          </label>
          <div class="ibox-title">Alumnos</div>
          <div class="input-group-icon input-group-icon-left">
            <span class="input-icon input-icon-right font-16"><i class="ti-search"></i></span>
            <input class="form-control form-control-rounded" id="key-search" type="text" placeholder="Buscar ...">
            </div>
        </div>
        <div class="ibox-body">
          <div class="" >
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
        </div>
    </div>
  </div>

  <div class="modal fade" id="user-assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Redactar correo</h5>
       {{--    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
        var val = [];
        $(':checkbox:checked').each(function(i){
            var form = document.getElementById('form-val');
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "users_id[]";
            input.value = $(this).val();
            form.appendChild(input);
        });
        console.log(val);
        $('form#form-val #users_id').val(val);
        $('#user-assign').modal('show');
      });
    });
  </script>>

@endsection
