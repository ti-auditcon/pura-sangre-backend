@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox">
        {!! Form::open(['route' => 'users.store']) !!}
        <div class="ibox-body">
          <div class="row">
            <div class="col-sm-6 form-group mb-4">
            <div class="form-group">
              <label class="form-control-label">Estado del Usuario*</label>
              <select class="selectpicker form-control"  name="plan_id" required>
               <option value="">Asignar estado...</option>
               @foreach (App\Models\Plans\Plan::all() as $plan)
               <option value="{{$plan->id}}">{{$plan->plan}}</option>
               @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group" id="date_1">
              <label class="font-normal">Fecha Inicio</label>
              <div class="input-group date">
                <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                <input class="form-control" type="text" value="{{ date('m/d/Y')}}">
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
$('#date_1 .input-group.date').datepicker({
  todayBtn: "linked",
  keyboardNavigation: false,
  forceParse: false,
  calendarWeeks: true,
  autoclose: true
});
</script>
@endsection
