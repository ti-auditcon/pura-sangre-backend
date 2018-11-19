
@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'wod-create'])
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-10">
    <div class="ibox form-control-air">
      <div class="ibox-head">
        <div class="ibox-title"> Workout {{Session::get('clase-type-id')}} día {{$wod->date}}</div>
      </div>
      {!! Form::open(['route' => ['wods.update',$wod->id],'method' => 'PUT']) !!}
      <div class="ibox-body">

        <div class="contaner">
          <div class="row">
            @foreach(App\Models\Wods\StageType::all() as $st)
            <div class="col">
              <div class="form-group mb-4">
                <label>{{$st->stage_type}}</label>
                <textarea name="{{$st->id}}" class="form-control form-control-solid" rows="12">{{$wod->stage($st->id)->description ?? 'sin registro'}}</textarea>
              </div>
            </div>
            @endforeach

          </div>
    </div>


      <br>
      <div class="ibox-footer">
      <button class="btn btn-primary btn-air" type="submit">Editar Workout</button>
      {{-- <button class="" href="" type="btn btn-secondary"></button> --}}
      <a class="btn btn-secondary" href="{{ route('clases.index') }}">Volver</a>
      <a class="btn btn-secondary btn-danger" href="">Eliminar</a>
      </div>
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
  autoclose: true
});
</script>


@endsection
