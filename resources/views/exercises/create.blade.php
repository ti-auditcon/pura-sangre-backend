
@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-6">
    <div class="ibox form-control-air">
      <div class="ibox-head">
        <div class="ibox-title">Crear un nuevo ejercicio</div>
      </div>
      {!! Form::open(['route' => 'exercises.store']) !!}
      <div class="ibox-body">
        <div class="row">
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group inline @if($errors->has('exercise')) has-warning  @endif">
              <label class="col-form-label">Nombre del Ejercicio*</label>
              <input class="form-control form-control-air" name="exercise" placeholder="Ejemplo: Dominada en pronación" required>
            </div>
          </div>
        </div>
      <br>
      <div class="ibox-footer">
      <button class="btn btn-primary btn-air" type="submit">Crear Ejercicio</button>
      <a class="btn btn-secondary" href="{{ route('exercises.index') }}">Volver</a>
      </div>
    </div>

    </div>
  </div>
</div>

@endsection

@section('css') {{-- stylesheet para esta vista --}}

@endsection


@section('scripts') {{-- scripts para esta vista --}}

@endsection
