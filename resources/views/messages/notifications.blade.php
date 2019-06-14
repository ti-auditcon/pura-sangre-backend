@extends('layouts.app')
@section('sidebar')
   @include('layouts.sidebar', ['page'=>'messages'])
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="ibox ibox-fullheight" id="mailbox-container">
            <div class="ibox-head">
                <div class="ibox-title">
                
                <h3 class="font-strong"><i class="fa fa-mobile" aria-hidden="true"></i> Notificaciones PUSH</h3></div>
                
                <div class="ibox-tools">
                    <button
                        class="btn btn-success text-white"
                        id="save_value"
                        name="save_value"
                    >
                        Redactar notificación
                    </button>
                </div>
            </div>

            <div class="ibox-body">
                <div class="ibox-body messages">
                    <div class="flexbox mb-4">
                        <div class="row">
                            <div class="flexbox">
                                
                                <label class="mb-0 mr-2">Estados:</label>
                                
                                <div class="btn-group bootstrap-select show-tick form-control" style="width: 150px;">
                                
                                    <select class="selectpicker show-tick form-control" id="type-filter" title="Elegir estado" data-style="btn-solid" data-width="150px" tabindex="-98">
                                        
                                        <option value="">Todos</option>
                                        
                                        <option value="1">Activo</option>
                                        
                                        <option value="2">Inactivo</option>
                                        
                                        <option value="3">Prueba</option>
                                    
                                    </select>

                                </div>
                            </div>
                            <div class="flexbox">
                                <label class="mb-0 mr-2">&nbsp; Mostrar: </label>
                                <div class="btn-group bootstrap-select show-tick form-control" style="width: 150px;">
                                    <select
                                        class="selectpicker show-tick form-control"
                                        id="length-filter"
                                        data-style="btn-solid"
                                        data-width="150px"
                                        tabindex="-98"
                                    >
                                        
                                        <option value="10">10 alumnos</option>
                                        
                                        <option value="25">25 alumnos</option>
                                        
                                        <option value="50">50 alumnos</option>
                                        
                                        <option value="100">100 alumnos</option>
                                    
                                    </select>
                                </div>
                                <label class="mb-0 mr-2">&nbsp;</label>
                            </div>
                            <div class="flexbox">
                                <label class="mb-0 mr-2">
                                    <h4 class="font-strong">
                                        <span class="text-primary" id="filtered">sin</span> registros <span id="filtered-from"></span>
                                    </h4>
                                </label>
                            </div>
                        </div>

                        <div class="input-group-icon input-group-icon-left mr-3">
                            <span class="input-icon input-icon-right font-16"><i class="ti-search"></i></span>
                            
                            <input class="form-control form-control-rounded form-control-solid" id="key-search" type="text" placeholder="Buscar ...">
                        </div>
                    </div>

                    <div class="table-responsive row">
                        <div id="datatable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                            <table
                                class="table table-bordered table-hover dataTable no-footer collapsed"
                                id="example"
                                role="grid"
                                aria-describedby="datatable_info"
                                style="width: 1592px;"
                            >
                                <thead class="thead-default thead-lg">
                                    <tr role="row">
                                        <th
                                            class="checkboxes-select-all sorting_disabled"
                                            tabindex="0"
                                            aria-controls="example"
                                            rowspan="1"
                                            colspan="1"
                                            aria-label=""
                                        >
                                            <input type="checkbox">
                                        </th>
                                        
                                        <th class="sorting_asc" width="10">ID</th>
                                        
                                        <th class="sorting">Nombre</th>
                                        
                                        <th class="sorting">Correo</th>
                                        
                                        <th class="sorting">status_user</th>
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
    </div>
</div>

{{--  - - - - - - - - - - - -  MODAL SEND PUSH NOTIFICATIONS  - - - - - - - - - - --}}
    <div class="modal fade bd-example-modal-xl"
         id="user-assign"
         tabindex="-1"
         role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">

        <div class="modal-dialog-email modal-dialog modal-xl " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Eviar mensaje PUSH</h5>
                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {!! Form::open(['url' => ['/notifications'], 'method' => 'post']) !!}
                <div class="modal-body messages-modal-body">
                    <tbody>
                        <div class="ibox-body">
                            <label class="col-form-label">Para</label>
                            
                            <input
                                type="text"
                                value=""
                                name="to[]"
                                data-role="tagsinput"
                                id="tags"
                                class="tass form-control"
                            />
                        </div>
                        
                        <label class="col-form-label">Título</label>
                        
                        <input class="form-control" name="title" type="text" required />
                        
                        <label class="col-form-label">Mensaje</label>
                        
                        <textarea class="form-control" name="body" required></textarea>
                        
                        <button
                            type="button"
                            class="btn btn-primary"
                            type="submit"
                            onClick="this.form.submit();"
                        >
                            Enviar Push
                        </button>
                    </tbody>
                    <div id="form-input"></div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
{{--  - - - - - - - - - - - - END MODAL SEND PUSH NOTIFICATIONS  - - - - - - - - --}}

@endsection


@section('css') {{-- stylesheet para esta vista --}}
    <link href="{{asset('/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
	
    <link href="{{asset('css/summernote.css')}}" rel="stylesheet" />
@endsection



@section('scripts') {{-- scripts para esta vista --}}
    
    <script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
    
    <script src="{{ asset('/js/datatables.min.js') }}"></script>
    
    <script src="{{ asset('/js/dataTables.checkboxes.min.js') }}"></script>
    
    <script src="{{ asset('/js/bootstrap-tagsinput.min.js') }}"></script>
	
    <script src="{{asset('/js/summernote.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "ajax": {
                    "url": '{{ url("/messages/users_Json") }}',
                    "dataType": "json",
                    "type": "GET",
                    "data": {"_token": "<?= csrf_token() ?>"},
                },
                "language": {
                    "lengthMenu": "<p>Mostrar</p> _MENU_ <p>elementos</p>",
                    "zeroRecords": "Sin resultados",
                    "info": " ",
                    "infoEmpty": "Sin resultados",
                    "infoFiltered": "(_TOTAL_ filtrados de _MAX_ registros totales)",
                    "search": "Filtrar:  ",
                    "paginate": {
                        "next":     "Siguiente",
                        "previous": "Anterior"
                    },
                    "select": {
                        "rows": {
                            _: " %d alumnos seleccionados",
                            0: "",
                            1: " 1 alumno seleccionado"
                        }
                    }
                },  
                "dom": '<"top">rt<"bottom"ilp><"clear">',
                "lengthChange": false,
                'columnDefs': [
                    {'targets': 0, 'checkboxes': {'selectRow': true}},
                    { "targets": [ 4 ], "visible": false, "searchable": true},
                ],
                "columns": [
                    {"data": "id"},
                    {"data": "id"},
                    {"data": "full_name"},
                    {"data": "email"}, 
                    {"data": "status_user_id"},
                ],
                'select': {'style': 'multi'},
                'order': [[1, 'asc']],
                "infoCallback": function( settings, start, end, max, total, pre ) {
                    $('#filtered').html(total);
                    $('#filtered-from').html('de ' + max);
                }
            });

            var form = document.getElementById('form-input');

            $('#save_value').click(function(e) {
                // var form = this;

                var rows_selected = table.rows('.selected').data();

                console.log(form);
            
                $.each(rows_selected, function(index, rowId) {
                    if ($('#' + rowId.id).length === 0) {
                        $('<input>').attr({
                            type: 'hidden',
                            id: rowId.id,
                            name: 'users_id['+ index +']'
                        }).val(rowId.id).appendTo(form);

                        $('.tass').tagsinput('add', {
                            "id"  : rowId.id,
                            "text": rowId.email
                        });
                    }
                });

                e.preventDefault();
                
                $('#user-assign').modal('show');
            });

            $('#user-assign').on('hidden.bs.modal', function (e) {
                var rows_selected = table.rows().data();
            
                $.each(rows_selected, function(index, rowId) {
                    $("input[id="+ rowId.id +"]").remove();
                    
                    $('.tass').tagsinput('remove', { "id": rowId.id, "text": rowId.email });
                });
            });

            var table = $('#example').DataTable();
            
            $('#key-search').on('keyup', function() {
            
                table.search(this.value).draw();
            
            });
            
            $('#type-filter').on('change', function() {
            
                table.column(4).search($(this).val()).draw();
            
            }); 
            
            $('#length-filter').on('change', function () {
            
                table.page.len( $(this).val() ).draw();
            
            });
        });

        $('.tass').tagsinput({
            itemValue: 'id',
            
            itemText: 'text',
        });
    </script>


@endsection