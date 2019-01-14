@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'payments'])
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
            <table id="payments-table" class="table table-hover">
              <thead class="thead-default">
                <tr>
                  <th width="20%">Alumno</th>
                  <th width="20%">Plan</th>
                  <th width="15%">Date</th>
                  <th width="15%">Start Date</th>
                  <th width="15%">Finish Date</th>
                  <th width="15%">Amount</th>
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
  {{--  datatable --}}
  <script src="{{ asset('js/datatables.min.js') }}"></script>
  <script>

      $('#payments-table').DataTable({
         "processing": true,
         "serverSide": true,
         "ajax": {
            "url": "<?= route('datapagos') ?>",
            "dataType": "json",
            "type": "POST",
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
            {"data": "date"},
            {"data": "start_date"},
            {"data": "finish_date"},
            {"data": "amount"},
         ]
    } );

   </script> 
  {{--  End datatable --}}

@endsection
