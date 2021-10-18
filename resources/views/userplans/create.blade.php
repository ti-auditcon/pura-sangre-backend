@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-8">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Agregar plan a {{$user->full_name}}</div>
                
                <div class="ibox-tools">
                    <a href="/users/{{ $user->id }}" class="btn btn-info">
                        Perfil de {{ $user->first_name }}
                    </a>
                </div>
            </div>
            
            
            {{-- {!! Form::open(['route' => ['users.plans.store', $user->id]]) !!}
            <div class="ibox-body">
                <input class="form-control" name="user_id" type="hidden" value="{{ $user->id }}" hidden>

                <div class="row">
                    <div class="col-sm-6 form-group mb-2">
                        <div class="form-group">
                            <label class="form-control-label">Planes*</label>

                            <div class="input-group">
                                <select class="form-control" name="plan_id" required id="plan-select">
                                    <option value="">Elegir plan...</option>
                                    
                                    @foreach ($plans as $plan)
                                    <option
                                        value="{{ $plan->id }}"
                                        @if(old('plan_id') == $plan->id) selected @endif
                                        data-amount="{{ $plan->amount }}"
                                        data-custom="{{ $plan->custom }}"
                                    >
                                        {{ $plan->plan }}
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
                                    <input class="form-control"
                                           name="fecha_inicio"
                                           type="text"
                                           value="@isset ($user->last_plan) {{ $user->last_plan->finish_date->addDay()->format('d-m-Y') }} @else {{ date('d-m-Y') }} @endisset"
                                    />
                                </div>

                                <span class="bg-white">
                                    <i class="la la-calendar"></i>
                                    <i>Fecha término último plan: @isset ($user->last_plan) {{ $user->last_plan->finish_date->format('d-m-Y') }} @else Sin referencia @endisset</i>
                                </span>
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
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ibox-footer pt-3 pb-2 my-1"></div>

                    <div class="row">
                        <div class="col-sm-6 form-group mb-2 is-not-custom">
                            <label>Forma de pago</label>
                            
                            <select class="selectpicker form-control" name="payment_type_id">
                                @foreach (App\Models\Bills\PaymentType::all() as $pt)
                                    <option value="{{ $pt->id }}">
                                        {{ $pt->payment_type }}
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
                                        value="@isset ($user->last_plan) {{$user->last_plan->finish_date->addDay()->format('d-m-Y')}} @else {{date('d-m-Y')}} @endisset"
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

                        {{-- <div class="col-sm-6 form-group mb-2 is-not-custom">
                            <label>Forma de pago</label>
                            
                            <select class="selectpicker form-control" name="payment_type_id">
                                @foreach (App\Models\Bills\PaymentType::all() as $pt)
                                    <option value="{{ $pt->id }}">
                                        {{ $pt->payment_type }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                        {{-- <div class="col-sm-6 mb-2 form-group is-not-custom">
                            <label class="checkbox checkbox-success">
                                <input type="checkbox" name="to_sii" checked/>
                            
                                <span class="input-span"></span>
                            
                                Enviar boleta al SII
                            </label>
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
                    <button class="btn btn-primary btn-air mr-2" 
                            type="submit"
                            onclick="this.form.submit(); this.disabled=true; this.innerText='Asignando…';"
                    >Asignar Plan</button>
                
                    <a class="btn btn-secondary" href="{{ route('users.show', $user->id) }}">Volver</a>
                </div>
            </div>
            {!! Form::close() !!}  --}}

            <form method="POST" action="{{ route('users.plans.store', $user->id) }}">
                @csrf

                <div class="ibox-body">
                    <input class="form-control" 
                            name="user_id"
                            type="hidden"
                            value="{{ $user->id }}"
                            hidden/>

                    <div class="row">
                        <div class="col-sm-12 form-group mb-2">
                            <div class="form-group">
                                <label class="form-control-label">Planes*</label>

                                <div class="input-group">
                                    <select class="form-control"
                                            name="plan_id"
                                            required
                                            id="plan-select">
                                        <option value="" selected>Elegir plan...</option>

                                        @foreach ($plans as $plan)
                                            <option value="{{ $plan->id }}"
                                                    @if(old('plan_id') == $plan->id) selected @endif
                                                    data-amount="{{ $plan->amount }}"
                                                    data-custom="{{ $plan->custom }}"
                                                    data-period-id="{{ optional($plan->plan_period)->period_number }}"
                                                    data-class-numbers="{{ $plan->class_numbers }}"
                                                    data-daily-clases="{{ $plan->daily_clases }}"
                                            >
                                                {{ $plan->plan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="display:none;" id="payment">
                        <div class="ibox-footer pt-3 pb-2 my-1"></div>

                    <div class="row mb-3">
                        <div class="col-sm-3 form-group mb-2">
                            <label class="form-control-label">Nº Clases <span id="has-period">al mes</span></label>

                            <input id="class-numbers"
                                    class="form-control"
                                    name="class_numbers"
                                    type="number"
                                    autocomplete="off"/>
                        </div>

                        <div class="col-sm-3 form-group mb-2">
                            <label class="form-control-label">Clases por día</label>

                            <input id="daily-clases" class="form-control" name="clases_by_day" type="number" autocomplete="off">
                        </div>

                        <div id="div-period-numbers" class="col-sm-3 form-group mb-2">
                            <label class="form-control-label">Cantidad de meses</label>

                            <input disabled
                                    id="period-numbers"
                                    class="form-control"
                                    type="number"/>

                            <input hidden
                                    id="period-numbers-hidden"
                                    class="form-control"
                                    name="period_number"
                                    type="hidden"/>
                        </div>

                        <div class="col-sm-3 form-group mb-2">
                            <label class="form-control-label">Clases totales del plan</label>

                            <input disabled id="clases-totales" class="form-control" value="0"/>

                            <input name="counter" hidden id="clases-totales-hidden"/>
                        </div>
                    </div>
                    <div class="ibox-footer pt-3 pb-2 my-1"></div>
                        <div class="row">
                            <div class="col-sm-6 form-group mb-2">
                                <div class="form-group" id="start_date">
                                    <label class="form-control-label">Fecha de inicio del plan</label>

                                    <div class="input-group date">
                                        <span class="input-group-addon bg-white">
                                            <i class="la la-calendar"></i>
                                        </span>

                                        <input class="form-control"
                                            id="input-start-date"
                                            name="start_date"
                                            type="text"
                                            data-start-date="@isset ($user->last_plan) {{ $user->last_plan->finish_date->addDay() }}@else {{ today() }}@endisset"
                                            value="@isset ($user->last_plan) {{ $user->last_plan->finish_date->addDay()->format('d-m-Y') }}@else {{ date('d-m-Y') }}@endisset"
                                            autocomplete="off"
                                        />
                                    </div>

                                    <span class="bg-white">
                                        <i class="la la-calendar"></i>
                                        <i>Fecha término último plan: @isset ($user->last_plan) {{ $user->last_plan->finish_date->format('d-m-Y') }} @else Sin referencia @endisset</i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-sm-6 form-group mb-2">
                                <div class="form-group" id="finish_date">
                                    <label class="form-control-label">
                                        Fecha de término del plan
                                    </label>

                                    <div class="input-group date">
                                        <span class="input-group-addon bg-white">
                                            <i class="fa fa-calendar"></i>
                                        </span>

                                        <input id="input-finish_date"
                                                class="form-control"
                                                name="finish_date"
                                                type="text"
                                                autocomplete="off"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ibox-footer pt-3 pb-2 my-1"></div>

                        <div class="row">
                            <div class="col-sm-6 form-group mb-2 with-bill">
                                <label>Forma de pago</label>

                                <select class="selectpicker form-control " name="payment_type_id">
                                    @foreach (App\Models\Bills\PaymentType::humanList() as $key => $payment_type)
                                        <option value="{{ $key }}">
                                            {{ $payment_type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 form-group mb-2 with-bill">
                                <div class="form-group" id="date">
                                    <label class="font-normal">Fecha del pago</label>

                                    <div class="input-group date ">
                                        <span class="input-group-addon bg-white">
                                            <i class="la la-calendar"></i>
                                        </span>

                                        <input class="form-control"
                                                name="date"
                                                type="text"
                                                value="@isset ($user->last_plan) {{ $user->last_plan->finish_date->addDay()->format('d-m-Y')}} @else {{ date('d-m-Y') }} @endisset"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 form-group mb-2 with-bill">
                                <div class="form-group">
                                    <label class="form-control-label">Total</label>

                                    <div class="input-group-icon input-group-icon-left">
                                        <span class="input-icon input-icon-left"><i class="la la-dollar"></i></span>

                                        <input class="form-control"
                                            id="plan-amount"
                                            name="amount"
                                            type="number"
                                            placeholder="solo números"
                                            required
                                            value="{{ old('amount') }}"
                                        />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-6 form-group mb-2 with-bill">
                                <div class="form-group">
                                    
                                    <label class="checkbox checkbox-success">
                                        <input type="checkbox" name="is_issued_to_sii" checked/>

                                        <span class="input-span"></span>
                                        
                                        Emitir boleta al SII
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="ibox-footer pt-3 pb-2 my-1 is-not-custom"></div>

                        <div class="row">
                            <div class="col-sm-12 form-group mb-2">
                                <label>Observaciones (Opcional)</label>

                                <div class="input-group date ">
                                    <textarea class="form-control"
                                                rows="5"
                                                name="observations"
                                                placeholder="Detalle..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                    <div class="row col-sm-12 form-group mt-3">
                        <label class="checkbox checkbox-success">
                            <input id="bill-checkbox" type="checkbox" name="billed" checked/>

                            <span class="input-span"></span>
                                Se registra un pago
                        </label>
                    </div>

                    <div class="ibox-footer p-0 pt-3">
                        <button class="btn btn-primary btn-air mr-2" 
                            type="submit"
                            onclick="this.form.submit(); this.disabled=true; this.innerText='Asignando…';"
                        >Asignar Plan</button>


                        <a class="btn btn-secondary" href="{{ route('users.show', $user->id) }}">Volver</a>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@section('css') {{-- stylesheet para esta vista --}}

@endsection


@section('scripts') {{-- scripts para esta vista --}}

    <script src="{{ asset('js/moment.min.js') }}"></script>

<script defer>
// Bootstrap datepicker
    $('#start_date .input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: true,
        calendarWeeks: true,
        format: "dd-mm-yyyy",
        startDate: moment().subtract(6, 'year').format('dd mm YYYY'),
        endDate: moment().add(10, 'year').format('dd mm YYYY'),
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
        startDate: moment().subtract(6, 'year').format('dd mm YYYY'),
        endDate: moment().add(10, 'year').format('dd mm YYYY'),
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
        startDate: moment().subtract(6, 'year').format('dd mm YYYY'),
        endDate: moment().add(10, 'year').format('dd mm YYYY'),
        language: "es",
        orientation: "bottom auto",
        autoclose: true,
        maxViewMode: 3,
        todayHighlight: true
    });

    // show plan information on option selected
    $('#plan-select').change(() => {
        showPlanInformation();

        showHideBillInformation();
    });


    function showPlanInformation()
    {
        calculateAmountPlan();

        calculatePlanUserClases();

        $('#payment').show();
    }

    /**
     *  Calculate plan amount and put into the input field
     */
    function calculateAmountPlan()
    {
        var amount_selected_plan = $('#plan-select').find('option:selected').data('amount');

        if (amount_selected_plan) {
            $('#plan-amount').val(amount_selected_plan);
        }
    }

    function calculatePlanUserClases()
    {
        var period_id = $('#plan-select').find('option:selected').data('period-id');
        var class_numbers = $('#plan-select').find('option:selected').data('class-numbers');
        var daily_clases = $('#plan-select').find('option:selected').data('daily-clases');
        console.log(period_id, class_numbers, daily_clases);

        // Number of the period (semestral = 6), this starts in 1, in case of hasn't period
        var period_number = 1;
        let start_date = $('#input-start-date').data('start-date');

        if (period_id) {
            period_number = period_id;

            finish_date = moment(start_date).add(period_id, 'month').subtract(1, 'days').format('DD-MM-YYYY');

            $('#input-finish_date').val(finish_date);

            $('#period-numbers').val(period_number);
            $('#period-numbers-hidden').val(period_number);

            $('#div-period-numbers').attr('hidden', false);
            $('#has-period').attr('hidden', false);
        } else {
            $('#has-period').attr('hidden', true);
            $('#div-period-numbers').attr('hidden', true);
            $('#input-finish_date').val(moment(start_date).format('DD-MM-YYYY'));
        }

        $('#class-numbers').val(class_numbers);

        $('#daily-clases').val(daily_clases);

        var clases_totales = class_numbers * daily_clases * period_number;
        $('#clases-totales').val(clases_totales);
        $('#clases-totales-hidden').val(clases_totales);
    }


    function showHideBillInformation()
    {
        bill_checkbox_checked = $("#bill-checkbox").is(':checked');

        if (bill_checkbox_checked) {
            $('.with-bill').show();
            $('#plan-amount').attr('required', true);

            return;
        }

        $('#plan-amount').attr('required', false);
        $('.with-bill').hide();
    }

    $("#bill-checkbox").change(function() {
        showHideBillInformation();
        //     $('#select-period').attr('required', false);
    });

    // Update finish_date on start_date change
    $('#input-start-date').change(start_date => {
        var period_id = $('#plan-select').find('option:selected').data('period-id');

        start_date = moment(start_date.target.value, 'DD-MM-YYYY');

        if (period_id) {
            console.log('has period');
            finish_date = moment(start_date).add(period_id, 'month').subtract(1, 'days').format('DD-MM-YYYY');

            $('#input-finish_date').val(finish_date);

            return;
        }

        return $('#input-finish_date').val(moment(start_date).format('DD-MM-YYYY'));
    });

    $("#class-numbers").on("input", function() {
        recalculatePlanUserClases();
    });
    
    $("#daily-clases").on("input", function() {
        recalculatePlanUserClases();
    });

    function recalculatePlanUserClases()
    {
        var period_number = $('#period-numbers').val();

        if (! period_number) {
            period_number = 1;
        }

        var class_numbers = $('#class-numbers').val();

        var daily_clases = $('#daily-clases').val();

        var result = period_number * class_numbers * daily_clases;

        $('#clases-totales').val(result);
        $('#clases-totales-hidden').val(result);
    }
</script>

@endsection
