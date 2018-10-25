@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox">
        {!! Form::open(['route' => 'users.store']) !!}
        <div class="ibox-body">

          <div class="col-sm-6 form-group mb-4">
            <div class="form-group inline @if($errors->has('rut')) has-warning  @endif">
              <label class="col-form-label">Rut</label>
              <input class="form-control" id="rut-src" name="rut" type="text" value="{{ old('rut') }}" required>
            </div>
          </div>
          <div class="col-12 p-0 my-0 mt-3">
            <span class="col-form-label hidden">Por favor, ingrese un rut válido</span>
          </div>

        <div class="row">
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group inline @if($errors->has('first_name')) has-warning  @endif">
              <label class="col-form-label">Nombre</label>
              <input class="form-control " name="first_name" value="{{ old('first_name') }}" required>
            </div>
          </div>
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group inline @if($errors->has('last_name')) has-warning  @endif">
              <label class="col-form-label">Apellido</label>
              <input class="form-control " name="last_name" value="{{ old('last_name') }}" required>
            </div>
          </div>
        </div>

        <div class="col-sm-6 form-group mb-4">
          <div class="form-group" id="start_date">
            <label class="font-normal">Fecha de Nacimiento</label>
            <div class="input-group date">
              <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
              <input class="form-control form-control-air" name="birthdate" value="{{ old('birthdate') }}" type="text" value="{{ date('d/m/Y') }}">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group inline @if($errors->has('phone')) has-warning  @endif">
              <label class="col-form-label">Teléfono</label>
              <div class="input-group mb-3">
                <span class="input-group-addon">+56 9</span>
                <input class="form-control" name="phone" value="{{ old('phone') }}" type="tel" required>
              </div>
            </div>
          </div>
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group inline @if($errors->has('email')) has-warning  @endif">
              <label class="col-form-label">Email</label>
              <input class="form-control " name="email" value="{{ old('email') }}" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group inline @if($errors->has('contact_name')) has-warning  @endif">
              <label class="col-form-label">Contacto de Emergencia</label>
              <input class="form-control " name="contact_name" value="{{ old('contact_name') }}" required>
            </div>
          </div>
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group inline @if($errors->has('contact_phone')) has-warning  @endif">
              <label class="col-form-label">Teléfono de Contacto</label>
              <input class="form-control " name="contact_phone" value="{{ old('contact_phone')}}" required>
            </div>
          </div>
        </div>

          <div class="form-group  @if($errors->has('status_user_id')) has-warning  @endif">
            <label class="form-control-label">Estado del Usuario*</label>
            <select class="selectpicker form-control"  name="status_user_id" data-live-search="true" required>
             <option value="">Asignar estado...</option>
             @foreach (App\Models\Users\StatusUser::all() as $status_user)
             <option value="{{$status_user->id}}" @if(old('status_user_id')==$status_user->id) selected @endif >{{$status_user->status_user}}</option>
             @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Género</label>
            <div>
              <label class="radio radio-inline radio-info">
                <input type="radio" name="gender" required value="male">
                <span class="input-span"></span>Masculino</label>
              <label class="radio radio-inline radio-info">
                <input type="radio" name="gender" value="female">
                <span class="input-span"></span>Femenino</label>
            </div>
          </div>

          <button class="btn btn-primary" type="submit">Ingresar Alumno</button>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>



@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection



@section('scripts') {{-- scripts para esta vista --}}

  <script defer>
  // Bootstrap datepicker
  $('#start_date .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true
  });
  </script>

   {{-- RUT --}}
<script src="{{ asset('js/jquery.rut.min.js') }}"></script>
  <script>
    $(function()  {
      $("input#rut-src").rut({
          formatOn: 'keyup',
          minimumLength: 8, // validar largo mínimo; default: 2
          validateOn: 'keyup' // si no se quiere validar, pasar null
      });
      $("input#rut-src").rut().on('rutInvalido', function(e) {
          $('#rut-group').addClass('has-error');
          $('#rut-group span').show();
          $('#rut-submit').prop('disabled', true);
      });

      $("input#rut-src").rut().on('rutValido', function(e, rut, dv) {
          $('#rut-group').removeClass('has-error');
          $('#rut-group span').hide();
          $('#rut-submit').prop('disabled', false);
      });
    })
  </script>
  {{-- END RUT --}}

@endsection
