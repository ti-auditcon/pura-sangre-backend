@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar', ['page' => 'payments' ])
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Pagos</div>

                <div class="tools">
                      <form action="{{ route('bills.export') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info btn-labeled btn-labeled-left btn-icon">
                            <span class="btn-label"><i class="la la-cloud-download"></i></span>
                            Exportar pagos
                        </button>
                    </form>
                </div>
            </div>
            <div class="ibox-body pagos-body">
                <div class="table-responsive">
                    <table id="payments-table" class="table table-hover">
                        <thead class="thead-default">
                            <tr>
                                <th>Fecha registro</th>
                                <th>Alumno</th>
                                <th>Correo</th>
                                <th>Plan</th>
                                <th>N° de Boleta</th>
                                <th>Tipo de Pago</th>
                                <th>Fecha Boleta</th>
                                <th>Fecha de Inicio</th>
                                <th>Fecha de Término</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
      "order": [[ 0, "desc" ]],
      "ajax": {
          "url": "payments/pagos",
          "dataType": "json",
          "type": "POST",
          "data": { "_token": "<?= csrf_token() ?>" }
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
      "columns": [
          { "data": "fecha_registro" },
          { "data": "alumno"},
          { "data": "email"},
          { "data": "plan"},
          { "data": "bill_id"},
          { "data": "payment_type"} , 
          { "data": "date" },
          { "data": "start_date" },
          { "data": "finish_date" },
          { "data": "amount" },
      ]
  });
</script> 
  {{--  End datatable --}}

@endsection
