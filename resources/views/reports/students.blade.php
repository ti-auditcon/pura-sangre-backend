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
            <h5 class="m-0">Análisis de alumnos</h5>
          </div>
        </div>

        <div class="ibox-body pt-3">
          <ul class="nav nav-pills nav-fill" id="reportTabs" role="tablist">
            <li class="nav-item border">
              <a class="nav-link active" id="students-tab" data-toggle="tab" href="#students" role="tab"
                aria-controls="students" aria-selected="true">Alumnos</a>
            </li>
            <li class="nav-item border ml-2">
              <a class="nav-link" id="trials-students-tab" data-toggle="tab" href="#trial" role="tab"
                aria-controls="trial" aria-selected="false">Alumnos de Prueba</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade show active" id="students" role="tabpanel" aria-labelledby="students-tab">
              <div class="d-flex justify-content-end mt-3">
                <select id="students-year-select" class="font-weight-bold form-control" style="max-width: 110px; border-radius: 4px;">
                  @for ($year = 2018; $year <= date('Y'); $year++)
                    @if ($year == date('Y'))
                      <option value="{{ $year }}" selected>{{ $year }}</option>
                    @else
                      <option value="{{ $year }}">{{ $year }}</option>
                    @endif
                  @endfor
                </select>
                <select id="students-month-select" class="font-weight-bold form-control ml-2" style="max-width: 180px; border-radius: 4px;">
                  <option value="">Todos los meses</option>
                  @for ($month = 1; $month <= 12; $month++)
                    <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                  @endfor
                </select>
              </div>

                  <div class="mt-3">
                      <strong>Tasa de Crecimiento Acumulada: </strong><span id="cumulative-growth-rate">0%</span>
                  </div>

              <div class="table-responsive mt-3">
                <table id="reports-table" class="table table-hover" style="width:100%">
                  <thead class="thead-default">
                    <tr>
                      <th width="5%">Año</th>
                      <th width="5%">Mes</th>
                      <th width="5%">Act inicio</th>
                      <th width="5%">Act término</th>
                      <th width="5%">Bajas</th>
                      <th width="5%">Nuevos</th>
                      <th width="5%">% Nuevos</th>
                      {{-- <th>Conversiones</th> --}}
                      <th width="5%">Dif.</th>
                      <th width="5%">Crecim.</th>
                      <th width="5%">Reten.</th>
                      <th width="5%">Rota.</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              {{-- explicative text --}}
              <div id="accordion">
                <div class="card mt-2">
                  <div id="headingOne">
                    <h5 class="btn font-16 font-bold mb-0 p-2 text-secondary" data-toggle="collapse"
                      data-target="#collapsableInfo" aria-expanded="false" aria-controls="collapsableInfo">
                      Diccionario de datos <i class="fa fa-angle-down" aria-hidden="true" click="turnDown(this)"></i>
                    </h5>
                  </div>
                  <div id="collapsableInfo" class="m-0 p-0 collapse" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body m-0 pl-2 py-0">
                      <div class="row">
                        <div class="col-12">
                          <p class="mt-1"><strong>Act inicio (Activos al inicio):</strong> Todos los alumnos que en el momento de iniciar el mes (xx-xx-xxx 00:00:00) tenían un plan activo.</p>
                          <p class=""><strong>Act térm. (Activos al término):</strong> Todos los alumnos que en el momento de terminar el mes (xx-xx-xxx 23:59:59) tenían un plan activo.</p>
                          <p class=""><strong>Bajas:</strong> La cantidad de alumnos que han dejado de ser activos durante el mes.</p>
                          <p class=""><strong>% Bajas:</strong> El porcentaje de alumnos que han dejado de ser activos con respecto al total de alumnos activos al inicio del mes.</p>
                          <p class=""><strong>Nuevos:</strong> La cantidad de nuevos alumnos del mes que han comprado su primer plan en el.</p>
                          <p class=""><strong>% Nuevos:</strong> El porcentaje de alumnos nuevos con respecto al total de alumnos activos al término del mes.</p>
                          <p class=""><strong>Dif.:</strong> La diferencia en la cantidad de alumnos que terminaron menos los que empezaron.</p>
                          <p class=""><strong>Crecimiento:</strong> El porcentaje de crecimiento en el número de alumnos activos durante el período.</p>
                          <p class=""><strong>Retención:</strong> El porcentaje de alumnos que permanecen activos al finalizar el período.</p>
                          <p class=""><strong>Rotación:</strong> El porcentaje de rotación de alumnos durante el período.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="trial" role="tabpanel" aria-labelledby="trials-students-tab">
              <div class="d-flex justify-content-end mt-3">
                <select id="trials-year-select" class="font-weight-bold form-control" style="max-width: 110px; border-radius: 4px;">
                  @for ($i = 2018; $i <= date('Y'); $i++)
                    @if ($i == date('Y'))
                      <option value="{{ $i }}" selected>{{ $i }}</option>
                    @else
                      <option value="{{ $i }}">{{ $i }}</option>
                    @endif
                  @endfor
                </select>
                <select id="trials-month-select" class="font-weight-bold form-control ml-2" style="max-width: 180px; border-radius: 4px;">
                  <option value="">Todos los meses</option>
                  @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                  @endfor
                </select>
              </div>
              <div class="table-responsive mt-3">
                <table id="trials-table" class="table table-hover" style="width:100%">
                  <thead class="thead-default">
                    <tr>
                      <th width="5%">Año</th>
                      <th width="5%">Mes</th>
                      <th width="5%">Planes vendidos</th>
                      <th width="5%">Usuarios de prueba</th>
                      <th width="5%">Clases consumidas</th>
                      <th width="5%">Clases tomadas</th>
                      <th width="5%">Conversión</th>
                      <th width="5%">Conversión</th>
                      <th width="5%">Retención</th>
                      <th width="5%">Usuarios inactivos</th>
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
@endsection

@section('css')
  {{-- <link href="{{asset('css/datatables.min.css')}}" rel="stylesheet" /> --}}
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
      height: 400px;
      /* Adjust the height as needed */
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
    const token = '{{ csrf_token() }}';
  </script>

  <script src="{{ asset('/js/purasangre/reports/students.js') }}"></script>

  <script>
    $(document).ready(function() {

      // Initialize the second table when the tab is shown
      $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var target = $(e.target).attr("href"); // activated tab

        if (target === '#trial' && !$.fn.DataTable.isDataTable('#trials-table')) {
          $('#trials-table').DataTable({
            language: {
              zeroRecords: 'Sin resultados',
              infoEmpty: 'Sin resultados',
              infoFiltered: '(filtered from _MAX_ total records)',
            },
            processing: true,
            serverSide: true,
            sort: false,
            ajax: {
              url: '/reports/trials-filter',
              dataType: 'json',
              type: 'POST',
              data: function(d) {
                d._token = token;
                d.year = $('#trials-year-select').val();
                d.month = $('#trials-month-select').val();
              },
            },
            dom: 'rt',
            columns: [
              {
                data: 'year'
              },
              {
                data: 'month',
                render: function(data, type, row) {
                  return monthNames[data - 1];
                }
              },
              {
                data: 'plans_sold'
              },
              {
                data: 'trial_users'
              },
              {
                data: 'trial_classes_consumed'
              },
              {
                data: 'trial_classes_taken_percentage',
                render: function(data, type, row) {
                  return data + '%';
                }
              },
              {
                data: 'trial_conversion',
                render: function(data, type, row) {
                  return data + '%';
                }
              },
              {
                data: 'trial_conversion_percentage',
                render: function(data, type, row) {
                  return data + '%';
                }
              },
              {
                data: 'trial_retention_percentage',
                render: function(data, type, row) {
                  return data + '%';
                } 
              },
              { 
                data: 'inactive_users'
              },
            ]
          });
        }
      });

      // Reload trials table data when year or month is changed
      $('#trials-year-select, #trials-month-select').change(function() {
        if ($.fn.DataTable.isDataTable('#trials-table')) {
          $('#trials-table').DataTable().ajax.reload();
        }
      });
    });
  </script> 



@endsection
