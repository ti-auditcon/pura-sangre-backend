@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar', ['page'=>'student'])
@endsection

@section('content')
{{-- <div class="row justify-content-center">
    <div class="col-6">
        <div class="ibox form-control-air">
            <div class="ibox-head">
                <div class="ibox-title">
                    Editar Plan {{ $plan_user->plan->plan }} a {{ $user->full_name }}
                </div>
                <div class="ibox-tools">
                    <span class="badge badge-{{ $plan_user->statusColor }} badge-pill">
                        {{ strtoupper($plan_user->status) }}
                    </span>
                </div>
            </div>

            <form action="{{ route('users.plans.update', [$user->id, $plan_user->id]) }}" method="POST">
                @method('PUT') @csrf
                <div class="ibox-body">
                    <div class="row">
                        <div class="col-sm-6 form-group mb-4">
                            <div class="form-group" id="start_date">
                                <label class="form-control-label">Fecha de inicio del plan</label>

                                <div class="input-group date">
                                    <span class="input-group-addon bg-white"><i class="la la-calendar"></i></span>

                                    <input class="form-control" name="start_date" type="text" value="{{ $plan_user->start_date->format('d-m-Y') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 form-group mb-2 is-custom">
                            <div class="form-group"  id="finish_date">
                                <label class="form-control-label">Fecha de término del plan</label>

                                <div class="input-group date">
                                    <span class="input-group-addon bg-white"><i class="la la-calendar"></i></span>

                                    <input class="form-control" name="finish_date" type="text" value="{{ $plan_user->finish_date->format('d-m-Y') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 form-group mb-2">
                            <div class="form-group is-not-custom">
                                <label class="form-control-label">Total**</label>

                                <div class="input-group-icon input-group-icon-left">
                                    <span class="input-icon input-icon-left"><i class="la la-dollar"></i></span>

                                    <input class="form-control" id="plan-amount" name="amount" value="@if ($plan_user->bill){{$plan_user->bill->amount}}@endif" type="text" placeholder="solo números" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-control-label">Clases totales</label>

                                <input class="form-control" name="counter" value="{{ $plan_user->counter }}" type="text"/>
                            </div>
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Observaciones</label>

                            <div class="input-group date ">
                                <textarea class="form-control " rows="5" name="observations" placeholder="Detalle...">
                                    {{ $plan_user->observations }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-6">
                            <button class="btn btn-primary btn-air mr-2" type="submit">
                                Actualizar Plan
                            </button>

                            <a class="btn btn-secondary" href="{{ route('users.show', ['user' => $user->id]) }}">
                                Perfirl de {{ $user->first_name }}
                            </a>
                        </div>
                        @if (in_array($plan_user->plan_status_id, $plan_status->reactivablePlans()))
                            <div class="col-6">
                                <label class="checkbox checkbox-success ml-3 float-right">
                                    <input type="checkbox" name="reactivate">

                                    <span class="input-span"></span>

                                    Activar plan nuevamente
                                </label>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="ibox-footer pt-3 pb-2 my-1 is-not-custom">
                    **Si el plan es de invitado, el total debe ser dejado en 0
                </div>
            </form>
    </div>
</div> --}}

<div class="row">
    {{----------------------- PLAN USER EDIT  --------------------- --}}
    <div class="col-sm-12 col-md-12 col-lg-6 ibox form-control-air">
        <div class="ibox-head">
            <div class="ibox-title">
                Editar Plan {{ $plan_user->plan->plan }} a {{ $user->full_name }}
            </div>
            <div class="ibox-tools">
                Estado del plan:
                <span class="badge badge-{{ $plan_user->statusColor }} badge-pill">
                    {{ strtoupper($plan_user->status) }}
                </span>
            </div>
        </div>
        <form action="{{ route('users.plans.update', [$user->id, $plan_user->id]) }}" method="POST">
            @method('PUT') @csrf
            <div class="ibox-body">
                <div class="row">
                    <div class="col-sm-4 form-group is-custom">
                        <div class="form-group">
                            <label class="form-control-label">Clases totales</label>

                            <input class="form-control" name="counter" value="{{ $plan_user->counter }}" type="number"/>
                        </div>
                    </div>

                    <div class="col-sm-4 form-group">
                        <div class="form-group" id="start_date">
                            <label class="form-control-label">Fecha de inicio del plan</label>

                            <div class="input-group date">
                                <span class="input-group-addon bg-white"><i class="la la-calendar"></i></span>

                                <input class="form-control"
                                name="start_date"
                                type="text"
                                value="{{ $plan_user->start_date->format('d-m-Y') }}"
                                required/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 form-group mb-2 is-custom">
                        <div class="form-group"  id="finish_date">
                            <label class="form-control-label">Fecha de término del plan</label>

                            <div class="input-group date">
                                <span class="input-group-addon bg-white"><i class="la la-calendar"></i></span>

                                <input class="form-control"
                                name="finish_date"
                                type="text"
                                value="{{ $plan_user->finish_date->format('d-m-Y') }}"
                                required/>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 form-group mb-4">
                        <label>Observaciones</label>

                        <div class="input-group date ">
                            <textarea class="form-control"
                            rows="5"
                            name="observations"
                            placeholder="Detalle...">{{ $plan_user->observations }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-between">
                    <div class="col-6">
                        <button class="btn btn-primary btn-air mr-2" type="submit">
                            Actualizar Plan
                        </button>

                        <a class="btn btn-info" href="{{ route('users.show', ['user' => $user->id]) }}">
                            Perfirl de {{ $user->first_name }}
                        </a>
                    </div>
                    @if (in_array($plan_user->plan_status_id, $plan_status->reactivablePlans()))
                    <div class="col-6">
                        <label class="checkbox checkbox-success ml-3 float-right">
                            <input type="checkbox" name="reactivate">

                            <span class="input-span"></span>

                            Activar plan nuevamente
                        </label>
                    </div>
                    @endif
                </div>
            </div>

            <div class="ibox-footer pt-3 pb-2 my-1 is-not-custom">
                **Si el plan es de invitado, el total debe ser dejado en 0
            </div>
        </form>
    </div>

    {{----------------------- PLAN USER BILL EDIT  --------------------- --}}
    <div class="col-sm-12 col-md-12 col-lg-6 ibox form-control-air">
        <div class="ibox-head">
            <div class="ibox-title">
                Boleta asociada al Plan
            </div>
            <div class="ibox-tools">
                tools
            </div>
        </div>
{{-- {{ dd($plan_user->bill) }} --}}
        <form action="{{ route('payments.update', ['payment' => $plan_user->bill]) }}" method="POST">
            @csrf @method('PUT')
            <div class="ibox-body">
                <div class="row">
                    <div class="col-sm-6 form-group form-group" id="start_date"">
                        <label class="form-control-label">Fecha de pago</label>

                        <div class="input-group date">
                            <span class="input-group-addon bg-white">
                                <i class="la la-calendar"></i>
                            </span>

                            <input class="form-control"
                                    name="date"
                                    type="text"
                                    value="{{ $plan_user->start_date->format('d-m-Y') }}"
                                    required/>
                        </div>
                    </div>

                    <div class="col-sm-6 form-group">
                        <label class="form-control-label">Monto</label>

                        <div class="input-group-icon input-group-icon-left">
                            <span class="input-icon input-icon-left"><i class="la la-dollar"></i></span>

                            <input class="form-control"
                                    id="plan-amount"
                                    name="amount"
                                    value="{{ optional($plan_user->bill)->amount }}"
                                    type="text"
                                    placeholder="0"
                                    required
                                    autocomplete="off"/>
                        </div>
                    </div>

                    <div class="col-sm-6 form-group">
                        <label class="form-control-label">Tipo de pago</label>

                        <select class="form-control" name="payment_type_id" required>
                            <option value="">Sin tipo de pago seleccionado</option>

                            @foreach ($payment_types as $payment)
                                <option value="{{ $payment->id }}"
                                        @if($payment->id === optional($plan_user->bill)->payment_type_id) selected @endif
                                >
                                    {{ $payment->payment_type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary btn-air mt-3"
                        type="submit"
                >
                    Actualizar Boleta
                </button>
            </div>
        </form>
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
         format: "dd-mm-yyyy",
         startDate: "01-01-1910",
         endDate: "01-01-2030",
         language: "es",
         orientation: "bottom auto",
         autoclose: true,
         maxViewMode: 3,
         todayHighlight: true
     });
   </script>

   <script defer>
      $('#finish_date .input-group.date').datepicker({
         todayBtn: "linked",
         keyboardNavigation: false,
         forceParse: false,
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
   </script>
@endsection
