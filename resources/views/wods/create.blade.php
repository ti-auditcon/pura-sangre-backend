
@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-10">
    <div class="ibox form-control-air">
      <div class="ibox-head">
        <div class="ibox-title">Crear Workout {{Session::get('clase-type-id')}}</div>
      </div>
      {!! Form::open(['route' => 'wods.store']) !!}
      <div class="ibox-body">
        <div class="row">
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group" id="start_date">
              <label class="font-normal">Fecha WORKOUT</label>
              <div class="input-group date">
                <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                <input class="form-control form-control-air" name="date" type="text" value="{{ date('m/d/Y') }}">
              </div>
            </div>
          </div>
        </div>

    <div class="contaner">
      <div class="row">
        <div class="col">
          {{-- <div class="form-group mb-4">
            <label class="col-form-label">Nombre</label>
            <div class="input-group-icon input-group-icon-left">
              <input class="form-control form-control-air"
              name="name[]" type="text" placeholder="Ej: WarmUP" required/>
            </div>
          </div> --}}
          <div class="form-group mb-4">
            <label>Warm-Up</label>
            <textarea name="warm" class="form-control form-control-solid" rows="6"></textarea>
          </div>
        </div>
        <div class="col">
          {{-- <div class="form-group mb-4">
            <label class="col-form-label">Nombre</label>
            <div class="input-group-icon input-group-icon-left">
              <input class="form-control form-control-air"
              name="name[]" type="text" placeholder="Ej: Skill" required/>
            </div>
          </div> --}}
          <div class="form-group mb-4">
            <label>Skills</label>
            <textarea name="skill" class="form-control form-control-solid" rows="6"></textarea>
          </div>
        </div>
        <div class="col">
          {{-- <div class="form-group mb-4">
            <label class="col-form-label">Nombre</label>
            <div class="input-group-icon input-group-icon-left">
              <input class="form-control form-control-air"
              name="name[]" type="text" placeholder="Ej: OPEN WOD 14.4" required/>
            </div>
          </div> --}}
          <div class="form-group mb-4">
            <label>WOD</label>
            <textarea name="wod" class="form-control form-control-solid" rows="6"></textarea>
          </div>
        </div>
      </div>
    </div>


      <br>
      <div class="ibox-footer">
      <button class="btn btn-primary btn-air" type="submit">Crear WOD</button>
      {{-- <button class="" href="" type="btn btn-secondary"></button> --}}
      <a class="btn btn-secondary" href="{{ route('clases.index') }}">Volver</a>
      </div>
    </div>

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


@endsection
