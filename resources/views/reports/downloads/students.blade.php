@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="ibox rounded">
            <div class="ibox-head">
                <div class="ibox-title">
                    <h5 class="m-0">Descargas</h5>
                </div>
            </div>
            <div class="ibox-body">
                <table id="files-table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre del archivo</th>
                            <th>Tamaño (KB)</th>
                            <th>Fecha de creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
{{-- stylesheet para esta vista --}}
<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">

<style>
@keyframes pulseButton {
  0% { transform: scale(1); }
  50% { transform: scale(1.1); }
  100% { transform: scale(1); }
}

.pulse {
  animation: pulseButton 0.3s ease-in-out;
  animation-iteration-count: 2;
}
</style>

@endsection

@section('scripts')
{{-- scripts para esta vista --}}
<script src="{{ asset('js/datatables.min.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#files-table').DataTable({
        responsive: true,
        processing: false,
        serverSide: true,
        language: {
            processing: 'Cargando...',
            search: 'Buscar...',
            lengthMenu: 'Mostrar _MENU_ elementos',
            zeroRecords: 'Sin resultados',
            info: 'Mostrando página _PAGE_ de _PAGES_',
            infoEmpty: 'Sin resultados',
            infoFiltered: '(filtrado de _MAX_ registros totales)',
            paginate: {
                first: 'Primero',
                last: 'Último',
                next: 'Siguiente',
                previous: 'Anterior'
            },
        },
        ajax: {
            url: '{{ url('/reports/downloads/getFiles') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            dataSrc: 'data'
        },
        order: [[2, 'desc']], // Default sorting by 'created_at' column in descending order
        columns: [
            { data: 'name', name: 'name' },
            { 
                data: 'size', 
                name: 'size',
                render: function(data, type, row) {
                    return data !== 'N/A' 
                    ? Math.round(parseInt(data)) + ' KB'
                    : 'N/A';
                }
            },
            { 
                data: 'created_at', 
                name: 'created_at',
                render: function(data, type, row) {
                    return moment(data).format('DD-MM-YYYY [a las] HH:mm:ss');
                }
            },
            {
                data: 'url', 
                name: 'url',
                render: function(data, type, row) {
                    return row.status === 'completado' 
                      ? `<a href="${data}" class="btn btn-info download-btn" id="${row.id}" download>Descargar</a>`
                      : 'Procesando...';
                },
                orderable: false,
                searchable: false
            }
        ]
    });

    var pusher = new Pusher('fa1593e2790e5d18db8b', {
      cluster: 'sa1',
      forceTLS: true,
    });

var channel = pusher.subscribe('downloads');
    channel.bind('download.completed', function(data) {
      try {
        table.ajax.reload(function() {
          var row = $('#' + data.id);
          if (row.length) {
            row.addClass('pulse');
            setTimeout(function() {
              row.removeClass('pulse');
            }, 1000);
          } else {
            console.log('Row not found');
          }
        }, false);
      } catch (e) {
        console.error('Error parsing JSON:', e);
      }
    });
});


</script>

@endsection
