@extends('layouts.app')

@section('sidebar')

    @include('layouts.sidebar', ['page' => 'home'])

@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-5">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">
                    <h5>Clases de Hoy</h5>
                </div>

                <div class="ibox-tools">
                    {{ Form::open(['route' => 'clases.type']) }}
                        <div class="row mr-2">
                            <div class="col-10">
                                <select class="form-control" name="type">
                                    @foreach(App\Models\Clases\ClaseType::all() as $type)
                                        <option value="{{ $type->id }}"
                                                @if($type->id == Session::get('clases-type-id')) selected @endif
                                        >
                                            {{ $type->clase_type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-2 pl-0">
                                <button class="btn btn-default">Ir</button>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
            <div class="ibox-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    @if (auth()->user()->hasRole(1))
        <div class="col-12 col-md-7">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="ibox">
                        <div class="ibox-body">
                            <div>
                                <h5>
                                    Alumnos en
                                    <span style="text-transform: capitalize;">
                                        {{ today()->formatLocalized('%B') }}
                                    </span>
                                </h5>

                                <div class="row mt-3">
                                    <div class="col-4 text-center" style="border-right: 1px solid rgba(0,0,0,.1);">
                                        <div class="text-muted">ACTIVOS</div>

                                        <h2 class="text-success mt-1" id="actives">-</h2>
                                    </div>

                                    <div class="col-4 text-center" style="border-right: 1px solid rgba(0,0,0,.1);">
                                        <div class="text-muted">PRUEBA</div>

                                        <h2 class="text-warning mt-1" id="tests">-</h2>
                                    </div>

                                    <div class="col-4 text-center">
                                        <div class="text-muted">INACTIVOS</div>
                                        
                                        <h2 class="text-danger mt-1" id="inactives">-</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h5>Crossfiteros activos del box</h5>

                                <div class="row mt-4">
                                    <div class="col-6 text-center">
                                        <span class="h2"><i class="fa fa-male text-primary h1 mb-0 ml-2 mr-2"></i>
                                            <span id="hombres">-</span>
                                        </span>
                                    </div>
                                    <div class="col-6 text-center">
                                        <span class="h2"><i class="fa fa-female text-pink h1 mb-0 ml-2 mr-2"></i>
                                            <span id="mujeres">-</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="ibox">
                        <div class="ibox-body">
                            <h5>
                                Planes vendidos hoy vs. mes <span style="text-transform: capitalize;">{{today()->formatLocalized('%B')}}</span>
                            </h5>
                                <div class="row mt-5">
                                    <div class="col-6 text-center" style="border-right: 1px solid rgba(0,0,0,.1);">
                                        <div class="text-muted">HOY</div>

                                        <h2 id="hoy_total">-</h2>

                                        <div class="text-muted mt-4">PLANES</div>

                                        <h5 class="text-secondary mb-3" id="hoy_cantidad">-</h5>
                                    </div>

                                    <div class="col-6 text-center">
                                        <div class="text-muted"> {{ strtoupper(today()->formatLocalized('%B')) }}</div>

                                        <h2 id="mes_total">-</h2>

                                        <div class="text-muted mt-4">PLANES</div>

                                        <h5 class="text-secondary mb-3" id="mes_cantidad">-</h5>
                                    </div>
                                </div>
                            {{-- <div class="ibox-body" id="incomes-summary"></div> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="ibox">
                        <div class="ibox-head">
                            <div class="ibox-title">Alumnos con planes próximos a vencer</div>
                        </div>

                        <div class="ibox-body">
                            <div class="table-responsive">
                                <table id="students-table" class="table table-hover">
                                    <thead class="thead-default thead-lg">
                                        <tr>
                                            <th>Alumno</th>
                                            <th>Plan</th>
                                            <th>Vencimiento</th>
                                            <th>Teléfono</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($plan_users->take(5) as $pu)
                                        <tr>
                                            <td><a href="{{ url('/users/'.$pu['user_id']) }}">{{ $pu['alumno'] }}</a></td>
                                            <td>{{ $pu['plan'] }}</td>
                                            <td>{{ $pu['fecha_termino'] }}</td>
                                            <td>{{ $pu['telefono'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="ibox">
                        <div class="ibox-head">
                            <div class="ibox-title">Alumnos recientemente inactivos</div>
                        </div>
                        <div class="ibox-body">
                            <table id="inactive-users-table" class="table table-hover">
                                <thead class="thead-default">
                                    <tr>
                                       <th>Alumno</th>
                                       <th>Plan</th>
                                       <th>Fecha</th>
                                       <th>N° Teléfono</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                           </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection

@section('css') {{-- stylesheet para esta vista --}}
    <link href="{{asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet" />
    
    <link href="{{asset('css/fullcalendar.min.css')}}" rel="stylesheet" />
    
    <style>
        .fc-axis.fc-widget-header{width:59px !important;}
        .fc-axis.fc-widget-content{width:51px !important;}
        .fc-scroller.fc-time-grid-container{height:100% !important;}
        .fc-time-grid.fc-event-container {left:10px}
        .closeon { top: 0; right: 8px; bottom: 0; position: absolute; }
    </style>
@endsection



@section('scripts') {{-- scripts para esta vista --}}
	{{--  datatable --}}
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('js/moment.min.js') }}"></script>

    <script src="{{ asset('js/fullcalendar/fullcalendar.min.js') }}"></script>

    <script src="{{ asset('js/fullcalendar/lang/es.js') }}"></script>

    <script>
        var densities = [];
        $.get( "json-density-parameters", function (response) {
            response.forEach(function (e) {
                densities.push(e);
            });        // response.
        }).done(() => {
            console.log(densities);
        });
    </script>

  <script defer>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            header: {
                right:  'today',
            },
            minTime: "07:00:00",
            maxTime: "22:00:00",
            editable: false,
            defaultView: 'agendaDay',
            slotDuration: '00:30:00',
            slotLabelFormat: 'h(:mm)a',
            hiddenDays: [0],
            eventColor: '#4c6c8b',
            eventRender: function( event, element, view ) {
                let percent = (event.reservation_count * 100) / event.quota;
                let colorPercentage = null;
                densities.forEach(function (density) {
                    if (percent <= density.to) {
                        colorPercentage = density.color;
                    }
                });

                element.find('.fc-time').append(
                    '<div> reservas: ' + event.reservation_count + '/' + event.quota+ '</div> ' +
                    '<div class="closeon circle-color" style="background-color: ' + colorPercentage + '"></div>');
            },
            viewRender: function (view, element,start,end) {
                $('#calendar').fullCalendar( 'removeEventSources');
                
                $('#calendar').fullCalendar( 'addEventSource', {
                    url: '/get-clases?datestart='+moment().startOf('day').format('Y-M-D')+'&dateend='+moment().startOf('day').format('Y-M-D'), // use the `url` property
                    textColor: 'black'  // an option!
                });
                
                $('#calendar').fullCalendar( 'addEventSource', {
                    url: '/get-wods?datestart='+moment().startOf('day').format('Y-M-D')+'&dateend='+moment().startOf('day').format('Y-M-D'), // use the `url` property
                    color: '#7DCCD1',    // an option!
                    textColor: 'black'  // an option!
                });
            },
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.1/dist/Chart.min.js"></script>

<script>
    var uri = "{{ url('withoutrenewal') }}";

    $( document ).ready(function () {
        $.get(uri, function (res) {
            Object.keys(res).forEach(element => {
                $('#' + element).text(res[element]);
            });
        });
    });
  </script>

<script>
    $(document).ready(function(){
        var op = "";
        $.ajax({
            type:'get',
            url: '/incomes-summary/',
            success: function (resp) {
                var obj = JSON.parse(resp);
                op+='<table class="table table-striped">';
                op+='<tr><th width="40%">Período</th><th width="15%">Cantidad</th><th width="45%">Ingresos</th></tr>';
                for(var i=0;i<obj.length;i++){
                   op += '<tr>';
                   op += '<td>' + obj[i].periodo + '</td>' +
                         '<td>' + obj[i].cantidad + '</td>' +
                         '<td>' + obj[i].ingresos + '</td></tr>';
                }
                op +='</table>';
                $('#incomes-summary').html(op);
            },
            error: function(){
                console.log("Error Occurred");
            }
        });
    });

    var uri = "{{ url('withoutrenewal') }}";

    $( document ).ready(function () {
        $.get('/incomes-summary/', function (res) {
            Object.keys(res).forEach(element => {
                // console.log(element);
                $('#' + element).text(res[element]);
            });
        });
    });
</script>

<script src="{{ asset('js/datatables.min.js') }}"></script>

<script>
    $('#inactive-users-table').DataTable({
        "processing": true,
        "serverSide": false,
        "order": [[ 2, "desc" ]],
        "dom": '<"top"><"bottom"><"clear">',
        "ajax": {
            "url": "<?= route('expiredplans') ?>",
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
                "first":    "Primero",
                "last":     "último",
                "next":     "Siguiente",
                "previous": "Anterior"
            },
        },
        "columns":[
            { "data": "first_name" },
            { "data": "plan" },
            { "data": "date" },
            { "data": "phone" }
        ]
    });
</script>

@endsection
