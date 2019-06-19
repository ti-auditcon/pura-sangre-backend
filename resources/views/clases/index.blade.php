@extends('layouts.app')

@section('sidebar')

  @include('layouts.sidebar',['page'=>'clases'])

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
            
                    <a class="btn btn-primary" href="{{ route('wods.create') }}">Asignar Workout</a>
            
                    <a class="btn btn-primary" href="{{ route('blocks.index') }}">Ir a Horarios</a>
            
                </div>
            
                @endif
            
            </div>
            <div class="ibox-body">
                {{ Form::open(['route'=>'clases.type']) }}
                
                <div class="form-group m-0 mt-2 mb-4 row align-items-center">
                
                    <span>Tipo de clase:</span>
                
                    <div class="col-sm-4">
                
                        <select class="form-control" name="type">
                
                            @foreach(App\Models\Clases\ClaseType::all() as $type)
                
                            <option value="{{$type->id}}" @if($type->id == Session::get('clases-type-id')) selected @endif>
                
                                {{$type->clase_type}}
                
                            </option>
                
                            @endforeach
                
                        </select>
                
                    </div>
                
                    <div class="col-sm-1 pl-0">
                
                        <button class="btn btn-default">seleccionar</button>
                
                    </div>
                
                </div>
                
                {{Form::close()}}
                
                <div id="calendar" style="position: relative;">
                
                    <div id="calendar-spinner" class="loading-box d-none">
                
                        <div class="spinner "></div>
                
                        <h1>Cargando...</h1>
                
                    </div>
                
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmacion de clase-->
@include('clases.modals.delete-day-modal')
<!-- END Modal de confirmacion de clase-->


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
    </style>

@endsection


{{-- SCRIPTS PARA ESTA VISTA --}}
@section('scripts') 
    {{--  full caslendar --}}
    {{-- <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script> --}}
    
    <script src="{{ asset('js/moment.min.js') }}"></script>
  
    <script src="{{ asset('js/fullcalendar/fullcalendar.min.js') }}"></script>
  
    <script src="{{ asset('js/fullcalendar/lang/es.js') }}"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
  
    {{-- <script src="{{ asset('js/bootstrap-clockpicker.min.js') }}"></script> --}}
  
    {{-- <script src="{{ asset('js/jquery.multi-select.js') }}"></script> --}}
  
    {{-- <script src="{{ asset('js/jquery.easypiechart.min') }}"></script> --}}


  <script defer>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
          header: {
              right:  'today prev,next',
          },
          minTime: "07:00:00",
          maxTime: "22:00:00",
          editable: false,

          defaultView: 'agendaWeek',
          // allDaySlot: false,
          slotDuration: '00:30:00',
          slotLabelFormat: 'h(:mm)a',
          hiddenDays: [0],
          eventColor: '#4c6c8b',
          eventRender: function( event, element, view ) {
            element.find('.fc-time').append('<div> reservas: ' +event.reservation_count+'/'+event.quota+'</div> ');
          },
          viewRender: function (view, element,start,end) {

             var b = $('#calendar').fullCalendar('getDate');
             // console.log(b.startOf('week').format('Y-M-D'));
             $('#calendar').fullCalendar( 'removeEventSources');
             //alert(b.format('Y-M-D'));

            $('#calendar').fullCalendar( 'addEventSource',
             {
               url: '/get-clases?datestart='+b.startOf('week').format('Y-M-D')+'&dateend='+b.endOf('week').format('Y-M-D'), // use the `url` property
               textColor: 'black'  // an option!
             }
            );
            $('#calendar').fullCalendar( 'addEventSource',
              {
                url: '/get-wods?datestart='+b.startOf('week').format('Y-M-D')+'&dateend='+b.endOf('week').format('Y-M-D'), // use the `url` property
                color: 'yellow',    // an option!
                textColor: 'black'  // an option!
              }

            );
            //$('#calendar-spinner').addClass('d-none');
          },
          // loading: function (bool) {
          //    $('#calendar-spinner').removeClass('d-none');// Add your script to show loading
          // },
          // eventAfterAllRender: function (view) {
          //   console.log('listo');
          //   $('#calendar-spinner').addClass('d-none');
          //   $('#calendar-spinner').addClass('d-none'); // remove your loading
          // }
          // eventClick: function(calEvent, jsEvent, view) {
          //   $('#clase-resume').modal();
          // },

        });


    });
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
    
    $('#sweet-clase-delete').click(function() {
            Swal.fire({
        title: '¿Esta seguro que quiere eliminar el día completo?',
        text: 'Para eliminar definitivamente por favor ingresa la palabra que aparece en el campo de abajo',
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off',
            placeholder: 'ELIMINAR'
        },
        showCancelButton: true,
        confirmButtonText: 'Eliminar!',
        showLoaderOnConfirm: true,
        preConfirm: (input) => {
            if (input !== 'ELIMINAR') {
                Swal.showValidationMessage(
                  `Palabra incorrecta`
                )
            }
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {

        // console.log(result);
        //   if (result.value) {
        //     Swal.fire({
        //       title: `${result.value.login}'s avatar`,
        //       imageUrl: result.value.avatar_url
        //     })
        //   }
    })
    });





</script>



@endsection
