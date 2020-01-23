@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6">
        <div class="ibox form-control-air">
            <div class="ibox-head">
                <div class="ibox-title">
                    Editar Plan {{ $plan_user->plan->plan }} a {{ $user->full_name }}
                </div>
                <div class="ibox-tools">
                    <span class="badge badge-{{ $plan_user->plan_status->type }} badge-pill">
                        {{ strtoupper($plan_user->plan_status->plan_status) }}
                    </span>
                </div>
            </div>

            {!! Form::open(['route' => ['users.plans.update', $user->id, $plan_user->id], 'method' => 'put']) !!}
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
                <button class="btn btn-primary btn-air mr-2" type="submit">
                    Actualizar Plan
                </button>
                
                <a class="btn btn-secondary" href="{{ route('users.show', ['user' => $user->id]) }}">
                    Perfirl de {{ $user->first_name }}
                </a>
            </div>

            <div class="ibox-footer pt-3 pb-2 my-1 is-not-custom">
                **Si el plan es de invitado, el total debe ser dejado en 0
            </div>
        </div>
        {!! Form::close() !!}
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
