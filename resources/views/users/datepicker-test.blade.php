<!DOCTYPE html>
<html>
<head>
    <title>Datepicker</title>
 
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <!-- Latest compiled and minified CSS -->
   {{--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"> --}}
       <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet" />
        <link href="{{asset('/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" />
   {{--  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker3.css" crossorigin="anonymous"> --}}
    <!-- Optional theme -->
{{--     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous"> --}}
  {{--   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.standalone.min.css" crossorigin="anonymous"> --}}
      <link href="{{asset('/css/bootstrap-datepicker.standalone.min.css')}}" rel="stylesheet" />
    <!-- Latest compiled and minified JavaScript -->
  {{--   <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script> --}}
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.5/umd/popper.min.js"></script>
      <script src="{{asset('/js/jquery.min.js')}}"></script>
  <script src="{{asset('/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('/js/bootstrap-datepicker.es.min.js')}}"></script>
    <!-- Jquery -->
      {{-- <script src="{{asset('/js/jquery.min.js')}}"></script> --}}

    <!-- Datepicker Files -->
    {{-- <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker3.css')}}"> --}}
    {{-- <link href="{{asset('/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" /> --}}
    {{-- <link href="{{asset('/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" /> --}}
    {{-- <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-standalone.css')}}"> --}}
    {{-- <script src="{{asset('datePicker/js/bootstrap-datepicker.js')}}"></script> --}}
    <!-- Languaje -->
    {{-- <script src="{{asset('datePicker/locales/bootstrap-datepicker.es.min.js')}}"></script> --}}
 
</head>
    <body>
        <div class="container">
            <div class="content">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-4 col-md-offset-4">
                            <form action="/test/save" method="post">
                                <label for="date">Fecha</label>
                                <input type="text" class="form-control datepicker" name="date">
                                <button type="submit" class="btn btn-default btn-primary">Enviar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        autoclose: true
    });
</script>
    </body>
</html>