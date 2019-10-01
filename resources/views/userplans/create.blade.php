@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Agregar plan a: {{$user->first_name}} {{$user->last_name}}</div>
            </div>
            
            {!! Form::open(['route' => ['users.plans.store', $user->id]]) !!}
            <div class="ibox-body">
                <input class="form-control" name="user_id" type="hidden" value="{{ $user->id }}" hidden>

                <div class="row">
                    <div class="col-sm-6 form-group mb-2">
                        <div class="form-group">
                            <label class="form-control-label">Planes*</label>
                            
                            <div class="input-group">
                                <select class="selectpicker form-control " name="plan_id" required id="plan-select">
                                    <option value="">Elegir plan...</option>
                                    
                                    @foreach (App\Models\Plans\Plan::all() as $plan)
                                    <option
                                        value="{{$plan->id}}"
                                        @if(old('plan_id')==$plan->id) selected @endif
                                        data-amount="{{$plan->amount}}"
                                        data-custom="{{$plan->custom}}"
                                    >
                                        {{$plan->plan}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 form-group mb-2 is-custom" style="display: none;">
                        <label class="form-control-label">Número de Clases</label>
                        
                        <input class="form-control" name="counter" type="number">
                    </div>
                </div>
            
                <div style="display:none;" id="payment">
                    <div class="ibox-footer pt-3 pb-2 my-1"></div>

                    <div class="row">
                        <div class="col-sm-6 form-group mb-2">
                            <div class="form-group" id="start_date">
                                <label class="form-control-label">Fecha de inicio del plan</label>

                                <div class="input-group date">
                                    <span class="input-group-addon bg-white"><i class="la la-calendar"></i></span>
                                    <input class="form-control" name="fecha_inicio" type="text" value="@isset ($user->last_plan){{$user->last_plan->finish_date->addDay()->format('d-m-Y')}} @else {{date('d-m-Y')}} @endisset">
                                </div>
                                <span class="bg-white"><i class="la la-calendar"></i><i>Fecha término último plan: @isset ($user->last_plan) {{$user->last_plan->finish_date->format('d-m-Y')}} @else Sin referencia @endisset</i></span>
                            </div>
                        </div>

                        <div class="col-sm-6 form-group mb-2 is-custom">
                            <div class="form-group"  id="finish_date">
                                <label class="form-control-label">Fecha de término del plan</label>

                                <div class="input-group date">
                                    <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                                    
                                    <input 
                                        class="form-control" 
                                        name="fecha_termino" 
                                        type="text" 
                                        value="@isset ($user->last_plan) {{$user->last_plan->finish_date->addDay()->format('d-m-Y')}} @else {{date('d-m-Y')}} @endisset"
                                        >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ibox-footer pt-3 pb-2 my-1"></div>

                    <div class="row">
                        <div class="col-sm-6 form-group mb-2 is-not-custom">
                            <label>Forma de pago</label>
                            
                            <select class="selectpicker form-control " name="payment_type_id">
                                @foreach (App\Models\Bills\PaymentType::all() as $pt)
                                <option value="{{$pt->id}}">
                                    {{$pt->payment_type}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 form-group mb-2 is-not-custom">
                            <div class="form-group" id="date">
                                <label class="font-normal">Fecha del pago</label>
                                
                                <div class="input-group date ">
                                    <span class="input-group-addon bg-white"><i class="la la-calendar"></i></span>
                                
                                    <input 
                                        class="form-control" 
                                        name="date" 
                                        type="text" 
                                        value="@isset ($user->last_plan){{$user->last_plan->finish_date->addDay()->format('d-m-Y')}} @else {{date('d-m-Y')}} @endisset"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 form-group mb-2 is-not-custom">
                            <div class="form-group">
                                <label class="form-control-label">Total</label>
                                
                                <div class="input-group-icon input-group-icon-left">
                                    <span class="input-icon input-icon-left"><i class="la la-dollar"></i></span>
                                   
                                    <input class="form-control" id="plan-amount" name="amount" type="text" placeholder="solo números" required/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ibox-footer pt-3 pb-2 my-1 is-not-custom"></div>

                    <div class="row">
                        <div class="col-sm-12 form-group mb-2">
                            <label>Observaciones</label>
                            
                            <div class="input-group date ">
                                <textarea class="form-control " rows="5" name="observations" placeholder="Detalle..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ibox-footer p-0 pt-3">
                    <button class="btn btn-primary btn-air mr-2" type="submit">Asignar Plan</button>
                
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
    forceParse: true,
    calendarWeeks: true,
    format: "dd-mm-yyyy",
    startDate: "01-01-1910",
    endDate: "01-01-2030",
    language: "es",
    orientation: "bottom auto",
    autoclose: true,
    maxViewMode: 3,
    todayHighlight: true
});

$('#date .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: true,
    calendarWeeks: true,
    format: "dd-mm-yyyy",
    startDate: "01-01-1910",
    endDate: "01-01-2030",
    language: "es",
    orientation: "bottom auto",
    autoclose: true,
    maxViewMode: 3,
    todayHighlight: true
});

$('#finish_date .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: true,
    calendarWeeks: true,
    format: "dd-mm-yyyy",
    startDate: "01-01-1910",
    endDate: "01-01-2030",
    language: "es",
    orientation: "bottom auto",
    autoclose: true,
    maxViewMode: 3,
    todayHighlight: true
});

   $('#plan-select').change(function() {
     // console.log('estoy vivo!!!');
      var data = $('#plan-select').find('option:selected').data('amount');
      var custom = $('#plan-select').find('option:selected').data('custom');
      $('#plan-amount').val(data);
      $('#payment').show();

      if(custom != 0){
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

@endsection
