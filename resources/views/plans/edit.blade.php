@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox form-control-air">
        <div class="ibox-head">
          <div class="ibox-title">Editar Plan: {{strtoupper($plan->plan)}}</div>
        </div>
        {!! Form::open(['route' => ['plans.update', $plan->id], 'method' => 'put']) !!}
        <div class="ibox-body">
          <div class="row">
            <div class="col-sm-6 form-group mb-4">
              <div class="form-group inline @if($errors->has('plan')) has-warning  @endif">
                <label class="col-form-label">Nombre del Plan*</label>
                <input class="form-control form-control-air" name="plan" value="{{$plan->plan}}" placeholder="Ejemplo: 12 sesiones" required>
              </div>
            </div>
            <div class="col-sm-6 form-group mb-4 @if($errors->has('plan_period_id')) has-warning  @endif">
              <label class="col-form-label">Período*</label>
              <select class="selectpicker form-control form-control-air"  name="plan_period_id" required>
               <option value="">Elegir período...</option>
               @foreach (App\Models\Plans\PlanPeriod::all() as $plan_period)
               <option value="{{$plan_period->id}}" @if(old('plan_period_id')!=null) @if(old('plan_period_id')==$plan_period->id) selected @endif @else @if($plan_period->id==$plan->plan_period_id) selected @endif @endif >{{$plan_period->period}}</option>
               @endforeach
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 form-group mb-4">
                <label class="col-form-label">Valor del Plan</label>
              <div class="input-group-icon input-group-icon-left">
                <span class="input-icon input-icon-left"><i class="la la-dollar"></i></span>
                <input class="form-control form-control-air"value="{{$plan->amount}}"
                name="amount" type="text" placeholder="solo números" required/>
              </div>
            </div>
            <div class="col-sm-6 form-group mb-4">
              <div class="form-group inline @if($errors->has('class_numbers')) has-warning  @endif">
                <label class="col-form-label">Número de Clases</label>
                <input class="form-control form-control-air" type="number" value="{{$plan->class_numbers}}" name="class_numbers" placeholder="0" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 form-group mb-4">
              <label class="col-form-label">Numero de clases unicas diarias</label>
              <div class="input-group-icon input-group-icon-left">
                <span class="input-icon input-icon-left"><i class="la la-dollar"></i></span>
                <input class="form-control form-control-air"
                name="daily_clases" type="number" value="{{$plan->daily_clases}}" required/>
              </div>
            </div>
          </div>
        <br>
        {{-- <div class="ibox-footer"> --}}
        <button class="btn btn-primary btn-air mr-2" type="submit">Actualizar Plan</button>
        {{-- <button class="" href="" type="btn btn-secondary"></button> --}}
        <a class="btn btn-secondary" href="{{ route('plans.index') }}">Volver</a>
        {{-- </div> --}}
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
