@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="ibox">
      <div class="ibox-head">
        <div class="ibox-title">
          <h4>Comparación de Ingresos</h4>
        </div>

        <div class="iboox-tools d-flex align-items-center justify-content-between">
          <input autocomplete="off" id="date-input" type="text" class="form-control mr-2 flex-grow-1">
          <input autocomplete="off" id="date-input-two" type="text" class="form-control mr-2 flex-grow-1">
          <button id="compare-button" class="btn btn-success" style="min-width: 100px;">Buscar</button>
        </div>
      </div>

      <div class="ibox-body">
        <div class="table-responsive">
          <table id="plans-summary-table" class="table table-hover">
            <thead class="thead-default">
              <tr>
                <th>Día</th>
                <th>Fecha</th>
                <th>Usuarios Activos del día</th>
                <th>Cantidad de Reservas del día</th>
                <th>Reservaciones acumuladas a la Fecha</th>
                <th>Ingresos del Día</th>
                <th>Ingresos Acumulados a la Fecha</th>
                <th>Cantidad Planes vendidos en el Día</th>
                <th>Acumulado de Planes vendidos</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>


  <div class="ibox">
    <div class="ibox-head">
      <div class="ibox-title">
        <h4>Registro de fechas pasadas</h4>
      </div>
    </div>
    <div class="ibox-body">
      <form id="dateForm">
        <div class="form-group">
          <label for="pastDate">Fecha:</label>
          <input type="text" class="form-control" id="pastDate" required>
        </div>
        <button type="submit" class="btn btn-primary" id="submitButton">Registrar</button>
        <div id="loadingMessage" class="alert alert-info d-none" role="alert">Procesando...</div>
      </form>
    </div>
  </div>
</div>
@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection



@section('scripts') {{-- scripts para esta vista --}}

<script src="{{ asset('js/moment.min.js') }}"></script>

<script src="{{ asset('js/datatables.min.js') }}"></script>

<script>
var today = moment().format('DD-MM-YYYY');

$('#date-input').datepicker({
    format: "dd-mm-yyyy",
    weekStart: 1,
    startDate: "03-03-2008",
    endDate: today,
    maxViewMode: 3,
    todayBtn: "linked",
    language: "es",
    autoclose: true,
    todayHighlight: true
});

$('#date-input-two').datepicker({
    format: "dd-mm-yyyy",
    weekStart: 1,
    startDate: "03-03-2008",
    endDate: today,
    maxViewMode: 3,
    todayBtn: "linked",
    language: "es",
    autoclose: true,
    todayHighlight: true
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

const dataPlansCompareUrl = @json(route('data-plans-compare'));
$(document).ready(function () {
    $('#compare-button').click(function () {
        table.ajax.reload();
    });


    var table = $('#plans-summary-table').DataTable({
        "processing": true,
        // "serverSide": true,
        "ajax": {
            "url": dataPlansCompareUrl,
            "dataType": "json",
            "type": "POST",
            // Data to send
            "data" : function( data ) {
                data.first_date = $('#date-input').val() ? $('#date-input').val() : moment().format('DD-MM-YYYY');
                data.second_date = $('#date-input-two').val() ? $('#date-input-two').val() : moment().format('DD-MM-YYYY');
            },
        },
        "dom": '<"top">rt<"bottom"><"clear">',
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
            { "data": "day" },
            { "data": "date" },
            { "data": "active_users_day" },
            { "data": "reservations_day" }, 
            { "data": "cumulative_reservations" },
            { "data": "day_incomes" },
            { "data": "cumulative_incomes" },
            { "data": "day_plans_sold" },
            { "data": "cumulative_plans_sold" }
        ],
    });
});

</script>

<script>
    $('#pastDate').datepicker({
      format: "dd-mm-yyyy",
      weekStart: 1,
      startDate: "03-03-2008",
      endDate: today,
      maxViewMode: 3,
      todayBtn: "linked",
      language: "es",
      autoclose: true,
      todayHighlight: true
  });

  document.getElementById('dateForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var selectedDate = document.getElementById('pastDate').value;

    var submitButton = document.getElementById('submitButton');
    submitButton.disabled = true;
    submitButton.textContent = 'Registrando...';

    fetch('/reports/data-plans/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Add Laravel's CSRF token
        },
        body: JSON.stringify({ date: selectedDate }),
    })
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        alert(data.error);
      }
      else {
        alert('Fecha registrada correctamente.');
        document.getElementById('pastDate').value = '';
      }
    })
    .catch((error) => {
        console.error('Error:', error);
    })
    .finally(() => {
      submitButton.disabled = false;
      submitButton.textContent = 'Registrar';
    });
});
</script>

@endsection