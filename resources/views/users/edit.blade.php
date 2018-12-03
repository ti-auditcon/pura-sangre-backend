@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
<div class="row justify-content-center">
   <div class="col-6">
      <div class="ibox">
      {!! Form::open(['route' => ['users.update', $user->id], 'method' => 'put', 'files' => true]) !!}
         <div class="ibox-body">
            <div class="form-group inline @if($errors->has('first_name')) has-warning  @endif">
               <label class="col-form-label">Nombre</label>
               <input class="form-control " name="first_name" value="{{ $user->first_name }}" required>
            </div>

            <div class="form-group inline @if($errors->has('last_name')) has-warning  @endif">
               <label class="col-form-label">Apellido</label>
               <input class="form-control " name="last_name" value="{{ $user->last_name }}" required>
            </div>

            <div class="row">
               <div class="form-group col-md-4">
                  {{Session::get('error')}}
                  <label class="btn btn-info btn-edit file-input mr-2">
                     <span class="btn-icon"><i class="la la-upload"></i>Subir o cambiar Imagen</span>
                     <input style="display: none" name="image" id="photoinput" type="file" accept="image/*" max-file-size=1234>
                  </label>
                 <a class="media-img align-self-start">
                   @if ($user->avatar)
                     <img class="img-circle mr-3" src="{{$user->avatar}}" id="imgback" alt="image" width="72">
                   @endif
                 </a>
               <span class="help-block"></span>
             </div>
             <div id="container-logo" class="pull-right" style="display: none">
               <img class="img-responsive" width="200" id="logo-img" src="#" />
             </div>
           </div>

        <div class="form-group inline @if($errors->has('phone')) has-warning  @endif">
          <label class="col-form-label">Numero de Celular</label>
          <div class="input-group mb-3">
            <span class="input-group-addon">+56 9</span>
            <input class="form-control " name="phone" value="{{ $user->phone }}" type="tel">
          </div>
        </div>

        <div class="form-group inline @if($errors->has('email')) has-warning  @endif">
          <label class="col-form-label">email</label>
          <input class="form-control" hidden="false" name="email" value="{{ $user->email }}" @if (!Auth::user()->hasRole(1)) readonly @endif required>
        </div>
          <br>
          <div>
            <button class="btn btn-primary mr-2" type="submit">Actualizar datos</button>
            <a class="btn btn-secondary" href="{{ route('users.show', $user->id) }}">Volver</a>
          </div>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>



@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection



@section('scripts') {{-- scripts para esta vista --}}

<script>
jQuery(function ()
{
    jQuery("input[type=file]").change(function() {
        readURL(this);
    });

    const readURL = (input) => {

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = (e) => {
                jQuery('#logo-img').attr('src', e.target.result)
                jQuery('#container-logo').css('display', 'block');
            }
            reader.readAsDataURL(input.files[0]);
        }
    };
})
</script>

<script>
   $(function() {
      $("input:file").change(function (){
         $("#imgback").prop('hidden', true);
         // console.log("si");
         // var fileName = $(this).val();
         // $(".filename").html(fileName);
     });
  });
</script>
@endsection
