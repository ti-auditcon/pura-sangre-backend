@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox form-control-air">
        <div class="ibox-head">
          <div class="ibox-title">EDITAR EJERCICIO: {{strtoupper($exercise->exercise)}}</div>
        </div>
        {!! Form::open(['route' => ['exercises.update', $exercise->id], 'method' => 'put']) !!}
        <div class="ibox-body">
          <div class="row">
            <div class="col-sm-6 form-group mb-4">
              <div class="form-group inline @if($errors->has('exercise')) has-warning  @endif">
                <label class="col-form-label">Nombre del Ejercicio*</label>
                <input class="form-control form-control-air" name="exercise" value="{{$exercise->exercise}}" placeholder="Ejemplo: Pistol squats asistidas" required>
              </div>
            </div>
          </div>
        <br>
        <div class="ibox-footer">
        <button class="btn btn-primary btn-air" type="submit">Actualizar Ejercicio</button>
        <a class="btn btn-secondary" href="{{ route('exercises.index') }}">Volver</a>
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


@endsection
