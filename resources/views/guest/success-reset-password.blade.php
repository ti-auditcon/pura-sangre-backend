<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width initial-scale=1.0">
      <title>Pura </title>
      <!-- GLOBAL MAINLY STYLES-->
      <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet" />
      <link href="{{asset('/css/bootstrap-select.min.css')}}" rel="stylesheet" />
      <link href="{{asset('/css/line-awesome.min.css')}}" rel="stylesheet" />
      <link href="{{asset('/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" />
      <link href="{{asset('/css/main.min.css')}}" rel="stylesheet" />
   </head>
   <body>
      <div class="error-content">
         <div class="flexbox">
            <span class="error-icon"></span>
            <div class="flex-1">
               <h1 class="error-code">404</h1>
               <h3 class="font-strong">Tu contraseña ha sido actualizada.</h3>
               {{-- <p>Tu contraseña ha sido actualizada.</p> --}}
            </div>
         </div>
      </div>
      <script src="{{asset('/js/jquery.min.js')}}"></script>
     	<script src="{{asset('/js/bootstrap.min.js')}}"></script>
   </body>
</html>