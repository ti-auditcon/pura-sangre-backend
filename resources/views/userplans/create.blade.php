@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox form-control-air">
        <div class="ibox-head">
          <div class="ibox-title">Agregar plan a: {{$user->first_name}} {{$user->last_name}}</div>
        </div>
        {!! Form::open(['route' => ['users.plans.store', $user->id]]) !!}
        <div class="ibox-body">
          <input class="form-control" name="user_id" type="hidden" value="{{ $user->id }}" hidden>
          <div class="row">
            <div class="col-sm-6 form-group mb-4">
              <div class="form-group">
                <label class="form-control-label">Planes*</label>
                <select class="selectpicker form-control form-control-air" name="plan_id" required id="plan-select">
                  <option value=""> Elegir plan..</option>
                   @foreach (App\Models\Plans\Plan::all() as $plan)
                   <option value="{{$plan->id}}" @if(old('plan_id')==$plan->id) selected @endif data-amount="{{$plan->amount}}" data-custom="{{$plan->custom}}">
                     {{$plan->plan}}</option>
                   @endforeach
                </select>
              </div>
            </div>
          </div>

        <div style="display:none;" id="payment">
          <div class="col-sm-6 form-group mb-4 is-custom">
            <div class="form-group inline form-control-air">
              <label class="col-form-label">Número de Clases</label>
              <input class="form-control" name="counter" type="numeric">
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6 form-group mb-4">
              <div class="form-group" id="start_date">
                <label class="font-normal">Fecha de inicio del plan</label>
                <div class="input-group date form-control-air">
                  <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                  <input class="form-control" name="fecha_inicio" type="text" value="{{ date('m/d/Y') }}">
                </div>
              </div>
            </div>

            <div class="col-sm-6 form-group mb-4">
              <div class="form-group" id="date">
                <label class="font-normal">Fecha del pago del plan</label>
                <div class="input-group date form-control-air">
                  <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                  <input class="form-control" name="date" type="text" value="{{ date('m/d/Y') }}">
                </div>
              </div>
            </div>

            <div class="col-sm-6 form-group mb-4 is-custom">
              <div class="form-group"  id="finish_date">
                <label class="font-normal">Fecha de término del plan</label>
                <div class="input-group date form-control-air">
                  <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                  <input class="form-control " name="fecha_termino" type="text" value="{{ date('m/d/Y') }}">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 form-group mb-4 is-not-custom">
              <label>Forma de pago</label>
              <select class="selectpicker form-control form-control-air" name="payment_type_id">
                @foreach (App\Models\Bills\PaymentType::all() as $pt)
                 <option value="{{$pt->id}}">
                   {{$pt->payment_type}}
                 </option>
                @endforeach
              </select>
            </div>
            <div class="col-sm-6 form-group mb-4 is-not-custom">
              <label>Total</label>
              <div class="input-group-icon input-group-icon-left">
                <span class="input-icon input-icon-left"><i class="la la-dollar"></i></span>
                <input class="form-control form-control-air" id="plan-amount" name="amount" type="text" placeholder="solo números" required/>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 form-group mb-4">
              <label>Observaciones</label>
              <textarea class="form-control form-control-air" name="detalle" placeholder="Detalle..."></textarea>
            </div>
          </div>
        </div>
        <br>
        <div class="ibox-footer">
          <button class="btn btn-primary btn-air" type="submit">ASIGNAR PLAN</button>
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

<script defer>
// Bootstrap datepicker
$('#start_date .input-group.date').datepicker({
  todayBtn: "linked",
  keyboardNavigation: false,
  forceParse: false,
  calendarWeeks: true,
  autoclose: true
});

$('#date .input-group.date').datepicker({
  todayBtn: "linked",
  keyboardNavigation: false,
  forceParse: false,
  calendarWeeks: true,
  autoclose: true
});

$('#finish_date .input-group.date').datepicker({
  todayBtn: "linked",
  keyboardNavigation: false,
  forceParse: false,
  calendarWeeks: true,
  autoclose: true
});

$('#plan-select').change(function() {

var data = $('#plan-select').find('option:selected').data('amount');
var custom = $('#plan-select').find('option:selected').data('custom');
$('#plan-amount').val(data);
$('#payment').show();

if(custom != 0)
{
  console.log('es custom');
  $('.is-custom').show();
  $('.is-not-custom').hide();
  
}
else {
    console.log('no es custom');
  $('.is-custom').hide();
  $('.is-not-custom').show();
}

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
