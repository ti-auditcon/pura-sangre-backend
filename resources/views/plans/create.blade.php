@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox">
        <div class="ibox-head">
          <div class="ibox-title">Agregar plan a: {{$user->first_name}} {{$user->first_name}}</div>
          <div class="ibox-tools">
            {{-- <a class="btn btn-success text-white" href="{{ route('users.create')}}">Nuevo alumno</a> --}}
          </div>
        </div>
        {!! Form::open(['route' => ['users.plans.store', $user->id]]) !!}
        <div class="ibox-body">
          <div class="row">
            <div class="col-sm-6 form-group mb-4">
            <div class="form-group">
              <label class="form-control-label">Planes*</label>
              <select class="selectpicker form-control"  name="plan_id" required>
               <option value="">Asignar plan...</option>
               @foreach (App\Models\Plans\Plan::all() as $plan)
               <option value="{{$plan->id}}">{{$plan->plan}}</option>
               @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group" id="start_date">
              <label class="font-normal">Fecha inicio del plan</label>
              <div class="input-group date">
                <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                <input class="form-control" name="start_date" type="text" value="{{ date('m/d/Y') }}">
              </div>
            </div>
          </div>
        </div>
        <button class="btn btn-primary" type="submit">Asignar Plan</button>
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
@endsection
