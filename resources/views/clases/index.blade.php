@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Clases</div>

                @if (Auth::user()->hasRole(1))
                  <div class="ibox-tools">
                      <button
                          id="button-modal"
                          class="btn btn-danger"
                          data-toggle="modal"
                          data-target="#delete-entire-day-modal"
                      >
                          <i class="la la-trash-o"></i>
                          Eliminar un día
                      </button>

                      
                      <a class="btn btn-primary" href="{{ route('blocks.index') }}">Ir a Horarios</a>
                    </div>
                    @endif
                    @if (Auth::user()->hasRole(1) || Auth::user()->hasRole(2))
                    <a class="btn btn-primary" href="{{ route('wods.create') }}">Asignar Rutina</a>
                    @endif
            </div>
            <div class="ibox-body">
                {{ Form::open(['route' => 'clases.type']) }}

                <div class="form-group m-0 mt-2 mb-4 row align-items-center">
                    <span>Tipo de clase:</span>

                    <div class="col-sm-4">
                        <select class="form-control" name="type">
                            @foreach(App\Models\Clases\ClaseType::all() as $type)
                                <option
                                    value="{{ $type->id }}"
                                    @if($type->id == Session::get('clases-type-id')) selected @endif
                                >
                                    {{ $type->clase_type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-1 pl-0">
                        <button class="btn btn-default">seleccionar</button>
                    </div>
                </div>

                {{ Form::close() }}

                <div id="calendar" style="position: relative;">
                    <div id="calendar-spinner" class="loading-box" hidden>
                        <div class="spinner"></div>
                        <h1>Cargando...</h1>
                    </div>
                </div>
            </div>

            <div class="ibox-footer">
                <ul class="list-inline">
                    @forelse( $densities as $density )
                        <li class="list-inline-item">
                            -
                            <div class="circle-color"
                                style="background-color: {{ $density->color }}; display: inline-block;"
                            ></div>
                            Nivel: {{ $density->level }}:
                            Desde: {{ $density->from }}%
                            Hasta: {{ $density->to }}%
                        </li>
                    @empty
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<!--   Modal de Confirmación de Clase   -->
@include('clases.modals.delete-day-modal')

<!--   Modal para Agregar una Clase   -->
{{-- @include('clases.modals.new-clase-modal') --}}


@endsection

{{-- stylesheet para esta vista --}}
@section('css')

    <link href="{{asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet" />

    <link href="{{asset('css/fullcalendar.min.css')}}" rel="stylesheet" />

    <link href="{{asset('css/bootstrap-clockpicker.min.css')}}" rel="stylesheet" />

    <link href="{{asset('css/multi-select.css')}}" rel="stylesheet" />

    <style>
        .fc-axis.fc-widget-header{width:59px !important;}
        .fc-axis.fc-widget-content{width:51px !important;}
        .fc-scroller.fc-time-grid-container{height:100% !important;}
        .fc-time-grid.fc-event-container {left:10px}
        .closeon { top: 0; right: 8px; bottom: 0; position: absolute; }
    </style>

@endsection


{{-- SCRIPTS PARA ESTA VISTA --}}
@section('scripts')
    <script src="{{ asset('js/moment.min.js') }}"></script>

    <script src="{{ asset('js/fullcalendar/fullcalendar.min.js') }}"></script>

    <script src="{{ asset('js/fullcalendar/lang/es.js') }}"></script>

    <script src="{{ asset('js/sweetalert2.8.js') }}"></script>

    <script src="{{ asset('js/bootstrap-clockpicker.min.js') }}"></script>

    <script src="{{ asset('js/jquery.multi-select.js') }}"></script>

    <script>
        var densities = [];
        $.get( "json-density-parameters", function(response) {
            response.forEach(function (e) {
                densities.push(e);
            });
        }).done(() => {
            // console.log(densities);
        });
    </script>

    <script defer>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    right:  'today prev,next',
                },
                minTime: "06:00:00",
                maxTime: "23:59:59",
                editable: false,
                defaultView: 'agendaWeek',
                // allDaySlot: false,
                slotDuration: '00:30:00',
                slotLabelFormat: 'h(:mm)a',
                eventRender: function( event, element, view ) {
                    let percent = (event.reservations_count * 100) / event.quota;
                    let colorPercentage = null;
                    densities.forEach(function (density) {
                        if (percent >= density.from && percent <= density.to) {
                            colorPercentage = density.color;
                        }
                    });

                    element.find('.fc-time').append(
                        `<div> reservas: ${event.reservations_count}/${event.quota}</div>
                        <div class="closeon circle-color"
                            style="background-color: ${colorPercentage}">
                        </div>`
                    );
                },
                viewRender: function (view, element, start, end) {
                    var b = $('#calendar').fullCalendar('getDate');
                    $('#calendar').fullCalendar( 'removeEventSources');

                    // Add classes events to Calendar of the Week
                    $('#calendar').fullCalendar('addEventSource', {
                        url: `/get-clases?datestart=${b.startOf('week').format('Y-M-D')}
                            &dateend=${b.endOf('week').format('Y-M-D')}`,
                        textColor: 'black',
                    });

                    // Add all the Workouts of the Day for the Calendar of the Week
                    $('#calendar').fullCalendar( 'addEventSource', {
                        url: `/get-wods?datestart=${b.startOf('week').format('Y-M-D')}
                                    &dateend=${b.endOf('week').format('Y-M-D')}`,
                        color: '#7DCCD1',    // an option!
                        textColor: 'black'  // an option!
                    });
                },

                loading: function (bool) {
                    $('#calendar-spinner').attr("hidden", false);
                //    removeClass('d-none'); // Add your script to show loading
                },

                eventAfterAllRender: function (view) {
                    $('#calendar-spinner').attr('hidden', true);
                    // $('#calendar-spinner').addClass('d-none');

                    // $('#calendar-spinner').addClass('d-none'); // remove your loading
                }
            });
        });
    </script>

<script>
    $('.clockpicker').clockpicker({ autoclose: true });

    $('#plan-select-add').multiSelect();
</script>

<script>
    let start_date = moment().format("DD-MM-YYYY");

    let end_date = moment().add(31, 'days').format("DD-MM-YYYY");

    $('.datepicker-delete-entire-day').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: true,
        calendarWeeks: true,
        format: "dd-mm-yyyy",
        startDate: start_date,
        endDate: end_date,
        language: "es",
        orientation: "bottom auto",
        autoclose: true,
        maxViewMode: 3,
        todayHighlight: true
    });

    // Allow to get focus in the input text modal
    $('#delete-entire-day-modal').on('shown.bs.modal', function() {
        $(document).off('focusin.modal');
    });

    $("#input-date-day").change(function() {
        if ( this.value.length != 0 ) {
            $('#sweet-confirm-day-delete').attr( "disabled", false );
        } else {
            $("#sweet-confirm-day-delete").attr("disabled", true);
        }
    });

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#sweet-confirm-day-delete').click(function() {
        Swal.fire({
            title: '¿Esta seguro que quiere eliminar el día completo?',
            text: `Para eliminar definitivamente por favor ingresa la palabra
                   que aparece en el campo de abajo`,
            input: "text",
            inputAttributes: {
                autocapitalize: 'off',
                placeholder: 'ELIMINAR'
            },
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Eliminar!',
            showLoaderOnConfirm: true,
            preConfirm: (input) => {
                if (input !== 'ELIMINAR') {
                    Swal.showValidationMessage(
                      `Palabra incorrecta`
                    )
                } else {
                    var date = $("#input-date-day").val();
                    var type_clase = $('#select-entire-day-delete').find(":selected").val();

                    let remove_day_url = '{{ url('calendar/clases/delete') }}';

                    return $.post(remove_day_url, { date: date, type_clase: type_clase })
                        .fail(error => {
                            Swal.showValidationMessage(
                                `Algo ha fallado: ${error}`
                            )
                        })
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((response) => {
            if (response.value.success) {
                Swal.fire({
                    title: response.value.success,
                    text: 'Presiona "OK" para recargar la página',
                    confirmButtonText: 'OK!',
                }).then(() => {
                    // Refresh page
                    location.reload();
                })
            }
        })
    });
</script>

@endsection
