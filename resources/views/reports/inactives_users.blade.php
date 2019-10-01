@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar')
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-10">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">
                    <div class="row">
                        <h3 class="ml-4 afont-strong"><i class="fa fa-user text-danger" aria-hidden="true"></i> Usuarios inactivos</h3>
                        <a
                            class="ml-2 btn btn-info btn-labeled btn-labeled-left btn-icon"
                            style="display: inline-block;"
                            href="{{ route('inactive_users.export') }}"
                        >
                            <span class="btn-label">
                                <i class="la la-cloud-download"></i>
                            </span>
                            Exportar
                        </a>
                    </div>
                </div>
            </div>
            <div class="ibox-body pagos-body">
                
                <div class="table-responsive">
                
                    <div class="flexbox mb-4">
                
                        <div class="row">
                
                            <div class="flexbox">
                
                                <label class="mb-0 mr-2">Planes:</label>
                
                                <div class="btn-group bootstrap-select show-tick form-control" style="width: 150px;">
                
                                    <select
                                        class="selectpicker show-tick form-control select-over"
                                        id="plan-filter"
                                        title="Elegir plan"
                                        data-style="btn-solid"
                                        data-width="150px"
                                        tabindex="-98"
                                    >
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
                                <th width="25%">Alumno</th>
                                <th width="20%">N° teléfono</th>
                                <th width="25%">Plan</th>
                                <th width="20%">Fecha de término del plan</th>
                                <th width="10%">Clases restantes</th>
                                <th>DateRaw</th>
                            </tr>
                        </thead>
                        <tbody>
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
        "processing": true,
        "serverSide": false,
        "order": [[ 5, "desc" ]],
        "ajax": {
            "url": "<?= route('inactiveusers') ?>",
            "dataType": "json",
            "type": "POST",
            "data": {"_token": "<?= csrf_token() ?>"}
        },
        "columnDefs": [
          { "targets": [ 5 ], "visible": false },
          { "targets": [ 3 ], "orderData": [ 5 ] }
        ],
        "dom": '<"top">rt<"bottom"ilp><"clear">',
        "lengthChange": false,
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
        "columns":[
            { "data": "full_name" },
            { "data": "phone" },
            { "data": "plan" },
            { "data": "date" }, 
            { "data": "remaining_clases" },
            { "data": "date_raw" }
        ],
        "infoCallback": function( settings, start, end, max, total, pre ) {
            $('#filtered').html(total);
            $('#filtered-from').html('de ' + max);
        }
    });

    $('#plan-filter').on('change', function() {
        table.column(2).search($(this).val()).draw();
    }); 
    
    $('#key-search').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#length-filter').on( 'change', function () {
        table.page.len( $(this).val() ).draw();
    });
</script> 
  {{--  End datatable --}}

@endsection