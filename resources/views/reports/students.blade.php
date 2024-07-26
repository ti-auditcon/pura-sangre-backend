@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-12">
    <div class="ibox">
      <div class="ibox-head">
        <div class="ibox-title">
          <h5 class="m-0">
            Análisis de alumnos
          </h5>
        </div>
      </div>

      <div class="ibox-body pt-3">
        <ul class="nav nav-pills nav-fill" id="reportTabs" role="tablist">
          <li class="nav-item border">
            <a class="nav-link active" id="students-tab" data-toggle="tab" href="#students" role="tab" aria-controls="students" aria-selected="true">Alumnos</a>
          </li>
          <li class="nav-item border ml-2">
            <a class="nav-link" id="trials-students-tab" data-toggle="tab" href="#other" role="tab" aria-controls="other" aria-selected="false">Alumnos de Prueba</a>
          </li>
        </ul>
        <div class="tab-content" id="reportTabsContent">
          <div class="tab-pane fade show active" id="students" role="tabpanel" aria-labelledby="students-tab">
            <div class="d-flex justify-content-end mt-3">
              <select id="year-select" class="font-weight-bold form-control" style="max-width: 110px; border-radius: 4px;">
                @for ($i = 2020; $i <= date('Y'); $i++)
                  <option value="{{ $i }}">{{ $i }}</option>
                @endfor
              </select>
              <select id="month-select" class="font-weight-bold form-control ml-2" style="max-width: 180px; border-radius: 4px;">
                <option value="">Todos los meses</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
              </select>
            </div>
            <div id="report-content" class="mt-3">
              <div class="table-responsive">
                <table id="reports-table" class="table table-hover display nowrap" style="width:100%">
                  <thead class="thead-default">
                    <tr>
                      <th>Año</th>
                      <th>Mes</th>
                      <th>Act inicio</th>
                      <th>Act término</th>
                      <th>Bajas</th>
                      <th>% Bajas</th>
                      <th>Nuevos</th>
                      <th>% Nuevos</th>
                      {{-- <th>Conversiones</th> --}}
                      <th>Dif mes ant.</th>
                      <th>Crecim.</th>
                      <th>Reten.</th>
                      <th>Rota.</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            {{-- explicative text --}}
{{-- explicative text --}}
<div id="accordion">
  <div class="card mt-2">
    <div id="headingOne">
      <h5 class="btn font-16 font-bold mb-0 p-2 text-secondary" data-toggle="collapse" data-target="#collapsableInfo" aria-expanded="false" aria-controls="collapsableInfo">
        Diccionario de datos <i class="fa fa-angle-down" aria-hidden="true" click="turnDown(this)"></i>
      </h5>
    </div>
    <div id="collapsableInfo" class="m-0 p-0 collapse" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body m-0 pl-2 py-0">
        <div class="row">
          <div class="col-12">
            <p class="mt-1">
                <strong>Activos inicio:</strong> Los datos de retención y rotación se calculan a partir de los datos de alumnos activos.
            </p>

            <p class="">
                <strong>Activos término:</strong> La cantidad de alumnos que han dejado de ser activos durante el período.
            </p>

            <p class="">
                <strong>Bajas:</strong> La cantidad de alumnos que han dejado de ser activos durante el período.
            </p>

            <p class="">
                <strong>% Bajas:</strong> El porcentaje de alumnos que han dejado de ser activos con respecto al total de alumnos activos.
            </p>

            <p class="">
                <strong>Nuevos:</strong> La cantidad de nuevos alumnos que se han inscrito durante el período.
            </p>

            <p class="">
                <strong>% Nuevos:</strong> El porcentaje de nuevos alumnos con respecto al total de alumnos activos.
            </p>

            <p class="">
                <strong>Dif mes ant.:</strong> La diferencia en el número de bajas en comparación con el mes anterior.
            </p>

            <p class="">
                <strong>Crecimiento:</strong> El porcentaje de crecimiento en el número de alumnos activos durante el período.
            </p>

            <p class="">
                <strong>Retención:</strong> El porcentaje de alumnos que permanecen activos al finalizar el período.
            </p>

            <p class="">
                <strong>Rotación:</strong> El porcentaje de rotación de alumnos durante el período.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

          </div>
          <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="trials-students-tab">
            <!-- Aquí puedes agregar la segunda tabla o contenido que desees mostrar -->
            <div class="mt-3">
              <div class="table-responsive">
                <table id="other-table" class="table table-hover display nowrap" style="width:100%">
                  <thead class="thead-default">
                    <tr>
                      <!-- Encabezados de la otra tabla -->
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Datos de la otra tabla -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection

@section('css')
<!-- Add your custom CSS here -->
<style>
.table-responsive {
  overflow-x: auto;
}

.dataTables_scrollHead {
  overflow: hidden !important;
}

.dataTables_scrollBody {
  overflow: auto !important;
  height: 400px; /* Adjust the height as needed */
}

.dataTables_empty {
  text-align: start !important;
}

.nav-item {
  flex: 1 1 auto;
  text-align: center;
  border: 1px solid #dee2e6;
  border-radius: .25rem;
}
</style>
@endsection

@section('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
<script>
const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

$(document).ready(function() {
  const reportsTable = $('#reports-table').DataTable({
    "language": {
      "zeroRecords": "Sin resultados",
      "infoEmpty": "Sin resultados",
      "infoFiltered": "(filtered from _MAX_ total records)",
    },
    "processing": true,
    "serverSide": true,
    "sort": false,
    "ajax": {
      "url": "{{ route('reports.students.filter') }}",
      "dataType": "json",
      "type": "POST",
      "data": function(d) {
        d._token = "{{ csrf_token() }}";
        d.year = $('#year-select').val();
        d.month = $('#month-select').val();
      },
    },
    dom: 'rt',
    "columns": [
      { "data": "year" },
      { "data": "month",
        "render": function(data, type, row) {
          return monthNames[data - 1];
        }
      },
      { "data": "active_students_start" },
      { "data": "active_students_end" },
      { "data": "dropouts" },
      { 
        "data": "dropout_percentage",
        "render": function(data, type, row) {
          return data + '%';
        }
      },
      { "data": "new_students" },
      { 
        "data": "new_students_percentage",
        "render": function(data, type, row) {
          return data + '%';
        }
      },
      // { "data": "turnaround"},
      { "data": "previous_month_difference"},
      { "data": "growth_rate",
        "render": function(data, type, row) {
          return data + '%';
        }
      },
      { "data": "retention_rate",
        "render": function(data, type, row) {
          return data + '%';
        }
      },
      { "data": "rotation",
        "render": function(data, type, row) {
          return data + '%';
        }
      },
    ],
  });

  $('#year-select, #month-select').change(function() {
    reportsTable.ajax.reload();
  });
});

function turnDown(el) {
  if (el.getAttribute('aria-expanded') === 'true') {
    el.setAttribute('aria-expanded', 'false');
  } else {
    el.setAttribute('aria-expanded', 'true');
  }
}
</script>
@endsection
