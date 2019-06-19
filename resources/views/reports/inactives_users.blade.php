@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'reports'])
@endsection

@section('content')

  <div class="row justify-content-center">
    <div class="col-10">
      <div class="ibox">
        <div class="ibox-head">
          <div class="ibox-title">
              <h3 class="font-strong"><i class="fa fa-user text-danger" aria-hidden="true"></i> Usuarios inactivos</h3>
          </div>
        </div>
        <div class="ibox-body pagos-body">
          <div class="table-responsive">
            <div class="flexbox mb-4">
            <div class="row">
              <div class="flexbox">
                <label class="mb-0 mr-2">Planes:</label>
                <div class="btn-group bootstrap-select show-tick form-control" style="width: 150px;">
                  <select class="selectpicker show-tick form-control select-over" id="plan-filter" title="Elegir plan" data-style="btn-solid" data-width="150px" tabindex="-98">
                    <option value="">Todos</option>
                    @foreach (App\Models\Plans\Plan::all() as $plan)
                      <option value="{{ $plan->plan }}">{{ $plan->plan }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="flexbox">
              <label class="mb-0 mr-2">&nbsp; Mostrar: </label>
              <div class="btn-group bootstrap-select show-tick form-control" style="width: 150px;">
                <select class="selectpicker show-tick form-control" id="length-filter" data-style="btn-solid" data-width="150px" tabindex="-98">
                  <option value="10">10 alumnos</option>
                  <option value="25">25 alumnos</option>
                  <option value="50">50 alumnos</option>
                  <option value="100">100 alumnos</option>
                </select>
              </div>
              <label class="mb-0 mr-2">&nbsp;</label>
            </div>

              <div class="flexbox">
                <label class="mb-0 mr-2"><h4 class="font-strong"><span class="text-primary" id="filtered">sin</span> registros <span id="filtered-from"></span></h4></label>
              </div>
            </div>

          <div class="input-group-icon input-group-icon-left mr-3">
            <span class="input-icon input-icon-right font-16"><i class="ti-search"></i></span>
            <input class="form-control form-control-rounded form-control-solid" id="key-search" type="text" placeholder="Buscar ...">
          </div>
        </div>

            <table id="inactives-table" class="table table-hover">
              <thead class="thead-default">
                <tr>
                  <th width="30%">Alumno</th>
                  <th width="30%">Plan</th>
                  <th width="20%">Fecha de término del plan</th>
                  <th width="20%">N° teléfono</th>
                  <th>date</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($inactive_users as $plan)
                <tr>
                   <td><a href="{{url('/users/'.$plan->user->id)}}">{{$plan->user->first_name}} {{$plan->user->last_name}}</a></td>
                   <td>{{$plan->plan->plan}}</td>
                   <td>{{ Carbon\Carbon::parse($plan->finish_date)->format('d-m-Y') }}</td>
                   <td>{{'+56 9 '.$plan->user->phone}}</td>
                   <td>{{ $plan->finish_date }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts') {{-- scripts para esta vista --}}
  <script src="{{ asset('js/datatables.min.js') }}"></script>
  <script>

    {{--  datatable --}}
    var table = $('#inactives-table').DataTable({
        "dom": '<"top">rt<"bottom"ilp><"clear">',
        "lengthChange": false,
        "columnDefs": [
          { "targets": [ 4 ], "visible": false },
          { "targets": [ 2 ], "orderData": [ 4 ] }
        ],
         "language": {
            "lengthMenu": "Mostrar _MENU_ elementos",
            "zeroRecords": "Sin resultados",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Sin resultados",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Filtrar:",
            "paginate": {
               "first":      "Primero",
               "last":       "último",
               "next":       "Siguiente",
               "previous":   "Anterior"
            },
         },
         "infoCallback": function( settings, start, end, max, total, pre ) {
          $('#filtered').html(total);
          $('#filtered-from').html('de ' + max);
        }
      } );

      $('#plan-filter').on('change', function() {
        table.column(1).search($(this).val()).draw();
      }); 
      $('#key-search').on('keyup', function() {
        table.search(this.value).draw();
      });

      $('#length-filter').on( 'change', function () {
          table.page.len( $(this).val() ).draw();
      } );
   </script> 
  {{--  End datatable --}}

@endsection