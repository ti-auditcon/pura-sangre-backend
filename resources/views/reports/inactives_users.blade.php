@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'reports'])
@endsection

@section('content')

  <div class="row justify-content-center">
    <div class="col-12">
      <div class="ibox">
        <div class="ibox-head">
          <div class="ibox-title">
            Pagos
          </div>
        </div>
        <div class="ibox-body pagos-body">
          <div class="table-responsive">
            <table id="inactives-table" class="table table-hover">
              <thead class="thead-default">
                <tr>
            <th width="30%">Alumno</th>
            <th width="30%">Plan</th>
            <th width="20%">Fecha plan</th>
            <th width="20%">N° teléfono</th>
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

@section('scripts') {{-- scripts para esta vista --}}
  {{--  datatable --}}
  <script src="{{ asset('js/datatables.min.js') }}"></script>
  <script>

      $('#inactives-table').DataTable({
         "processing": true,
         "serverSide": true,
         "ajax": {
            "url": "<?= route('inactive_users') ?>",
            "dataType": "json",
            "type": "post",
            "data": {"_token": "<?= csrf_token() ?>"}
         },
         "language": {
               "loadingRecords": "Cargando datos...",
               "processing": "Cargando datos...",
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
            {"data": "alumno"},
            {"data": "plan"}, 
            {"data": "fecha_termino"},
            {"data": "telefono"},
         ]
    } );

   </script> 
  {{--  End datatable --}}

@endsection
