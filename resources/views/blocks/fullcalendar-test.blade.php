<!DOCTYPE html>
<html>
<head>
    <title>Fullcalendar</title>

    <link href="{{asset('/js/fullcalendar/fullcalendar.min.css')}}" rel="stylesheet"/>
    {{-- <link href="{{asset('/js/fullcalendar/fullcalendar.print.min.css')}}" rel="stylesheet" /> --}}


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.5/umd/popper.min.js"></script>
    <script src="{{asset('/js/fullcalendar/moment.min.js')}}"></script>
    <script src="{{asset('/js/fullcalendar/jquery.min.js')}}"></script>
    <script src="{{asset('/js/fullcalendar/jquery-ui.min.js')}}"></script>
    <script src="{{asset('/js/fullcalendar/fullcalendar.min.js')}}"></script>
    {{-- <script src="{{asset('/js/bootstrap.min.js')}}"></script> --}}
    <script src="{{asset('/js/fullcalendar/lang/es.js')}}"></script>


 
    {{-- <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.print.css"> --}}

{{-- <link href="http://fullcalendar.io/js/fullcalendar-2.1.1/lib/jquery.min.js"> --}}
{{-- <link href="http://fullcalendar.io/js/fullcalendar-2.1.1/lib/jquery-ui.custom.min.js">
<link href="http://fullcalendar.io/js/fullcalendar-2.1.1/fullcalendar.min.js"> --}}


</head>
    <body>
        <div id='calendar'></div>

        <script>
            $(document).ready(function() {
                $('#calendar').fullCalendar({
                    defaultDate: '2014-09-12',
                    editable: true,
                    eventLimit: true, // allow "more" link when too many events
                });
            });
        </script>
    </body>
</html>