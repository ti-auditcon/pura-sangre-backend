@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox">
        {!! Form::open(['route' => ['users.update', $user->id], 'method' => 'put']) !!}
        <div class="ibox-body">
          <div class="form-group inline @if($errors->has('first_name')) has-warning  @endif">
            <label class="col-form-label">Nombre</label>
            <input class="form-control " name="first_name" value="{{ $user->first_name }}" required>
          </div>

          <div class="form-group inline @if($errors->has('last_name')) has-warning  @endif">
            <label class="col-form-label">Apellido</label>
            <input class="form-control " name="last_name" value="{{ $user->last_name }}" required>
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
            <input class="form-control " name="email" value="{{ $user->email }}" required>
          </div>

					<div class="form-group @if($errors->has('contact_name')) has-warning  @endif">
	          <label class="col-form-label">Contacto de Emergencia</label>
	          <input class="form-control" name="contact_name" type="text" value="{{ old($user->emergency->contact_name) }}" required>
		      </div>

					<div class="form-group @if($errors->has('contact_phone')) has-warning  @endif">
	          <label class="col-form-label">Telefono de Contacto de Emergencia</label>
	          <input class="form-control" name="contact_phone" type="text" value="{{ old($user->emergency->contact_phone) }}" required>
		      </div>

          <div class="form-group  @if($errors->has('status_user_id')) has-warning  @endif">
            <label class="form-control-label">Estado del Usuario*</label>
            <select class="selectpicker form-control"  name="status_user_id" data-live-search="true" required>
             <option value="">Seleccionar estado...</option>
             @foreach (App\Models\Users\StatusUser::all() as $status_user)
             <option value="{{$status_user->id}}" @if($user->status_user_id == $status_user->id) selected @endif >{{$status_user->status_user}}</option>
             @endforeach
            </select>
          </div>
          <button class="btn btn-primary" type="submit">Actualizar datos</button>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>



@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection



@section('scripts') {{-- scripts para esta vista --}}


@endsection