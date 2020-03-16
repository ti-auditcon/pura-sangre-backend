@extends('layouts.app')
@section('sidebar')
@include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6">
        <div class="ibox form-control-air">
            <div class="ibox-head">
                <div class="ibox-title">Editar Plan {{$plan_user->plan->plan}} a {{$user->full_name}}</div>
            </div>
            {!! Form::open(['route' => ['users.plans.update', $user->id, $plan_user->id], 'method' => 'put']) !!}
            {!! Form::open(['route' => ['users.update', $user->id], 'method' => 'put']) !!}
            <div class="ibox-body">
                <div class="row">
                    <div class="col-sm-6 form-group mb-4">
                        <div class="form-group">
                            <label class="col-form-label">Planes*</label>
                            <select class="selectpicker form-control form-control-air" name="plan_id" required>
                                <option value="">Asignar plan...</option>
                                @foreach (App\Models\Plans\Plan::all() as $plan)
                                <option value="{{$plan->id}}" @if(old('plan_id')==$plan->id) selected
                                    @elseif ($plan->id == $plan_user->plan_id) selected @endif>
                                    {{$plan->plan}} - {{$plan->plan_period->period}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group mb-4">
                        <div class="form-group" id="start_date">
                            <label class="col-form-label">Fecha inicio del plan</label>
                            <div class="input-group date">
                                <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                                <input class="form-control form-control-air" name="fecha_inicio" type="text"
                                    value="{{ date('m/d/Y') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                </div>


                <div class="row">
                    <div class="col-sm-6 form-group mb-4">
                        <label class="col-form-label">Valor del Plan</label>
                        <div class="input-group-icon input-group-icon-left">
                            <span class="input-icon input-icon-left"><i class="la la-dollar"></i></span>
                            <input class="form-control form-control-air" value="{{$plan_user->amount}}" name="amount"
                                type="text" placeholder="solo números" required />
                        </div>
                    </div>
                    <div class="col-sm-6 form-group mb-4">
                        <div class="form-group inline @if($errors->has('class_numbers')) has-warning  @endif">
                            <label class="col-form-label">Número de Clases</label>
                            <input class="form-control form-control-air" type="number"
                                value="{{$plan_user->class_numbers}}" name="class_numbers" placeholder="0" required>
                        </div>
                    </div>
                </div>

                <br>
                <div class="ibox-footer">
                    <button class="btn btn-primary btn-air" type="submit">Actualizar Plan</button>
                    {{-- <button class="" href="" type="btn btn-secondary"></button> --}}
                    <a class="btn btn-secondary"
                        href="{{ route('users.plans.show', ['user' => $user->id, 'plan' => $plan_user->id]) }}">Volver</a>
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

@endsection