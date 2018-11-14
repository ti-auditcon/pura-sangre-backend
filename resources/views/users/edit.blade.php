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
            <label class="btn btn-info file-input mr-2">
              <span class="btn-icon"><i class="la la-upload"></i>Subir Imagen</span>
              <input style="display: none" name="image" type="file" accept="image/*" max-file-size=1234>
            </label>
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
          <input class="form-control " name="email" value="{{ $user->email }}" @if (!Auth::user()->hasRole(1)) readonly @endif required>
        </div>

				{{-- 	<div class="form-group @if($errors->has('contact_name')) has-warning  @endif">
	          <label class="col-form-label">Contacto de Emergencia</label>
	          <input class="form-control" name="contact_name" type="text" value="{{ old($user->emergency->contact_name) }}" required>
		      </div>

					<div class="form-group @if($errors->has('contact_phone')) has-warning  @endif">
	          <label class="col-form-label">Telefono de Contacto de Emergencia</label>
	          <input class="form-control" name="contact_phone" type="text" value="{{ old($user->emergency->contact_phone) }}" required>
		      </div> --}}

          {{-- COMPROBAR SI ES NECESARIO ESTE CAMPO --}}
         {{--  <div class="form-group  @if($errors->has('status_user_id')) has-warning  @endif">
            <label class="form-control-label">Estado del Usuario*</label>
            <select class="selectpicker form-control"  name="status_user_id" data-live-search="true" required>
             <option value="">Seleccionar estado...</option>
             @foreach (App\Models\Users\StatusUser::all() as $status_user)
             <option value="{{$status_user->id}}" @if($user->status_user_id == $status_user->id) selected @endif >{{$status_user->status_user}}</option>
             @endforeach
            </select>
          </div> --}}
          <button class="btn btn-primary" type="submit">Actualizar datos</button>
          <a class="btn btn-secondary" href="{{ route('users.show', $user->id) }}">Volver</a>
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
@endsection
