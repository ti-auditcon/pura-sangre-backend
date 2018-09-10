@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox form-control-air">
        <div class="ibox-head">
          <div class="ibox-title">Agregar plan a:  {{$user->first_name}} {{$user->last_name}}</div>
          <div class="ibox-tools">
            {{-- <a class="btn btn-success text-white" href="{{ route('users.create')}}">Nuevo alumno</a> --}}
          </div>
        </div>
        {!! Form::open(['route' => ['users.plans.store', $user->id]]) !!}
        <div class="ibox-body">
          <input class="form-control" name="user_id" type="hidden" value="{{ $user->id }}" hidden>
          <div class="row">
            <div class="col-sm-6 form-group mb-4">
            <div class="form-group">
              <label class="form-control-label">Planes*</label>
              <select class="selectpicker form-control form-control-air" name="plan_id" required>
               <option value="">Asignar plan...</option>
               @foreach (App\Models\Plans\Plan::all() as $plan)
               <option value="{{$plan->id}}" @if(old('plan_id')==$plan->id) selected @endif>
                 {{$plan->plan}} - {{$plan->plan_period->period}}
               </option>
               @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group" id="start_date">
              <label class="font-normal">Fecha inicio del plan</label>
              <div class="input-group date">
                <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                <input class="form-control form-control-air" name="fecha_inicio" type="text" value="{{ date('m/d/Y') }}">
              </div>
            </div>
          </div>
        </div>

        <div class="form-group mb-4">
          <label>Total</label>
          <div class="input-group-icon input-group-icon-left">
            <span class="input-icon input-icon-left"><i class="la la-dollar"></i></span>
            <input class="form-control form-control-air"
            name="amount" type="text" placeholder="solo números" required/>
          </div>
        </div>
        <br>
        <div class="ibox-footer">
        <button class="btn btn-primary btn-air" type="submit">ASIGNAR PLAN</button>
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

{{-- AGREGAR PUNTOS PARA MONTO DEL PLAN --}}
{{-- <script>
function oneDot(input) {
    var value = input.value,
        value = value.split('.').join('');
    if (value.length > 3) {
      value = value.substring(0, value.length - 3) + '.' + value.substring(value.length - 3, value.length);
    }
    input.value = value;
  }
</script> --}}

@endsection
