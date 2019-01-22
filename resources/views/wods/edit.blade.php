
@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'wod-create'])
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-10">
    <div class="ibox form-control-air">
      <div class="ibox-head">
        <div class="ibox-title"> Workout {{Session::get('clase-type-id')}} día {{Carbon\Carbon::parse($wod->date)->format('d-m-Y')}}</div>
             {!! Form::open(['route' => ['wods.destroy', $wod->id], 'method' => 'delete', 'class' => 'wod-delete']) !!}
      {!! Form::close() !!}
          <button class="btn btn-danger sweet-wod-delete" data-id="{{$wod->id}}"><i></i>Eliminar WOD!</button>
      </div>
      <div class="ibox-body">
         {!! Form::open(['route' => ['wods.update', $wod->id],'method' => 'PUT']) !!}
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
            <a class="btn btn-secondary" href="{{ route('clases.index') }}">Volver</a>
            {!! Form::close() !!}
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

 <script>
  $('.sweet-wod-delete').click(function(e){
    var id = $(this).data('id');
      swal({
          title: "Confirma la eliminación de este WOD?",
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonClass: 'btn-danger',
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Borrar WOD',
          closeOnConfirm: false,
      },function(){
        //redirección para eliminar clase
         $('form.wod-delete').submit();
      });
  });
  </script>


@endsection
