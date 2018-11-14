@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-4">
      <div class="ibox form-control-air">
        <div class="ibox-head">
          <div class="ibox-title"><h4 class="m-0">Editar Plan {{$plan_user->plan->plan}}
              a {{$user->first_name}} {{$user->last_name}}</h4></div>
        </div>
        {!! Form::open(['route' => ['users.plans.update', $user->id, $plan_user->id], 'method' => 'put']) !!}
        <div class="ibox-body">
          <div class="row">
            <div class="col-sm-12 form-group mb-4">
              <div class="form-group">
                <label class="col-form-label">Estado del Plan</label>
                <select class="selectpicker form-control form-control-air" name="plan_status_id" required>
                 <option value="">Elegir estado...</option>
                 @foreach (App\Models\Plans\PlanStatus::all() as $ps)
                 <option value="{{$ps->id}}" @if($plan_user->plan_status_id == $ps->id) selected @endif>
                    {{$ps->plan_status}}
                  </option>
                 @endforeach
                </select>
              </div>
            </div>
          </div>
        <div class="ibox-footer">
        <button class="btn btn-primary btn-air" type="submit">Actualizar Plan</button>
        {{-- <button class="" href="" type="btn btn-secondary"></button> --}}
        <a class="btn btn-secondary" href="{{ route('users.plans.show', ['user' => $user->id, 'plan' => $plan_user->id]) }}">Volver</a>
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


  {{-- // BOOTSTRAP DATEPICKER // --}}
  <script defer>
  $('#start_date .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true
  });

  </script>

{{--   <script>
 $.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '< Ant',
 nextText: 'Sig >',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);
$(function () {
$("#fecha").datepicker();
});
</script> --}}

@endsection
