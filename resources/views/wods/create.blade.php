@extends('layouts.app')

@section('sidebar')

@include('layouts.sidebar',['page'=>'wod-create'])

@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-12">
    <div class="ibox">
      <div class="ibox-head">
        <div class="ibox-title">Crear Rutina {{ Session::get('clase-type-id') }}</div>
      </div>

      <form action="{{ route('wods.store') }}" method="POST">
        @csrf
        <div class="ibox-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <div class="form-group" id="start_date">
                <label class="font-normal">Elegir Fecha</label>

                <div class="input-group date">
                  <span class="input-group-addon bg-white"><i class="la la-calendar"></i></span>

                  <input class="form-control" name="date" type="text" value="{{ date('d-m-Y') }}">
                </div>
              </div>
            </div>

            <div class="col-md-6 form-group">
              <div class="form-group">
                <label class="font-normal">Elegir Tipo de Clase</label>

                <div class="input-group date">
                  <select class="form-control" id="type-clase-select" name="type"></select>
                </div>
              </div>
            </div>
          </div>
            <div class="row" id="div-fields"></div>

          <button class="btn btn-primary btn-air mr-2" type="submit">Guardar</button>

          <a class="btn btn-secondary" href="{{ route('clases.index') }}">Volver</a>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('css') {{-- stylesheet para esta vista --}}

@endsection


@section('scripts') {{-- scripts para esta vista --}}

<script defer>
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
    todayHighlight: true
  });
</script>

<script>
  $(function () {
    $('#type-clase-select').find('option').remove();

    $('#type-clase-select').append($('<option>Eliga un tipo de clase...</option>').val(null));

    $.get("/clases-types-all").done(function (response) {
      response.data.forEach(function (el) {
        $('#type-clase-select').append(
          $('<option></option>').val(el.id).html(el.clase_type)
        );
        $('#calendar-type-clase-select').append(
          $('<option></option>').val(el.id).html(el.clase_type)
        );
      });
    });
  });

  $(function () {
    $('#type-clase-select').change(function () {
      let clases_type_id = this.value;

      new manageFields(clases_type_id);
    });
  });

  function manageFields(clase_type_id) {
    $('#div-fields').empty();

    $.get(`/stage-types/${clase_type_id}`)
      .done(function (response) {
        new drawFields(response, clase_type_id);
      });
  }

  function drawFields(response, clase_type_id) {
    $("#div-fields").append(
      '<input type="hidden" value="' + clase_type_id + '" name="clase_type_id" hidden/>'
    );

    if (response.length == 0) {
      $("#div-fields").append(
        `<div class="col">
          <div class="form-group mb-1 text-center">
            <label>No hay etapas para este tipo de clase</label>
          </div>
        </div>`
      );
    } else {
      $.each(response, function (index, stage) {
        $("#div-fields").append(
          `<div class="col">
            <div class="form-group">
              <label>${stage.stage_type}</label><textarea name="${stage.id}" class="form-control form-control-solid" rows="12"></textarea></div>
          </div>`
        );
      });
    }
  }
</script>
@endsection