
@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'wod-create'])
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-10">
    <div class="ibox form-control-air">
      <div class="ibox-head">
        <div class="ibox-title">Crear Workout {{Session::get('clase-type-id')}}</div>
      </div>
      {!! Form::open(['route' => 'wods.store']) !!}
      <div class="ibox-body">
        <div class="row">
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group" id="start_date">
              <label class="font-normal">Fecha Workout</label>
              <div class="input-group date">
                <span class="input-group-addon bg-white"><i class="la la-calendar"></i></span>
                <input class="form-control form-control-air" name="date" type="text" value="{{ date('d-m-Y') }}">
              </div>
            </div>
          </div>
        </div>

    <div class="contaner">
      <div class="row">
        @foreach(App\Models\Wods\StageType::all() as $st)
        <div class="col">
          <div class="form-group mb-4">
            <label>{{$st->stage_type}}</label>
            <textarea name="{{$st->id}}" class="form-control form-control-solid" rows="6"></textarea>
          </div>
        </div>
        @endforeach
      </div>
    </div>


      <br>
      {{-- <div class="ibox-footer"> --}}
      <button class="btn btn-primary btn-air mr-2" type="submit">Crear WOD</button>
      {{-- <button class="" href="" type="btn btn-secondary"></button> --}}
      <a class="btn btn-secondary" href="{{ route('clases.index') }}">Volver</a>
      {{-- </div> --}}
    </div>

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
   format: "dd-mm-yyyy",
   startDate: "01-01-2010",
   endDate: "01-01-2030",
   language: "es",
   autoclose: true,
   maxViewMode: 3,
   daysOfWeekDisabled: "6",
   todayHighlight: true
});
</script>


@endsection
