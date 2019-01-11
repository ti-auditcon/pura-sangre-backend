<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width initial-scale=1.0">
      <title>Bienvenido de vuelta! - Pura Sangre CrossFit</title>
      <!-- GLOBAL MAINLY STYLES-->
      <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet" />
      <link href="{{asset('/css/animate.min.css')}}" rel="stylesheet" />
      <link href="{{asset('/css/bootstrap-select.min.css')}}" rel="stylesheet" />
      <link href="{{asset('/css/font-awesome.min.css')}}" rel="stylesheet" />
      <link href="{{asset('/css/jquery-jvectormap-2.0.3.css')}}" rel="stylesheet" />
      <link href="{{asset('/css/line-awesome.min.css')}}" rel="stylesheet" />
      <link href="{{asset('/css/sweetalert.css')}}" rel="stylesheet" />
      <link href="{{asset('/css/themify-icons.css')}}" rel="stylesheet" />
      <link href="{{asset('/css/toastr.min.css')}}" rel="stylesheet" />
      <link href="{{asset('/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" />

      <link href="{{asset('/css/main.min.css')}}" rel="stylesheet" />
      <link href="{{asset('/css/ps-app.css')}}" rel="stylesheet" />
   </head>
   <body class="fixed-navbar body-auth">


     <div class="page-wrapper">

       <div class="wrapper content-wrapper content-wrapper-login">
         <div class="page-content">
           <div class="container">
               <div class="row justify-content-center">
                   <div class="col-md-8">
                       <div class="card login-box">
                           <div class="card-header">
                             <a href="/login">
                               <img src="/img/logo_login.png">
                             </a>
                           </div>

                           <div class="card-body my-4 text-center mx-4">
                             <h3 class="pb-3">Se ha actualizado tu Contraseña</h3>
                             <p class="mb-4">Ahora puedes ingresar a la app de Pura Sangre CrossFit para poder reservar clases, tomar notas, ver el workout del día, tus pagos y asistencia.</p>
                             <p class="mb-3"><b>Si aún no la tienes ¡Descárgala gratis!</b></p>
                             <div class="app-logos">
                               <a href="https://itunes.apple.com/cl/app/pura-sangre-crossfit/id1447657358">
                                 <img class="icon-ios" alt="Disponible en la App Store" src='{{ asset('/img/icon-store-ios.png') }}'>
                               </a>
                               <a href='https://play.google.com/store/apps/details?id=purasangrecrossfit.app.com&amp;hl=es&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'>
                                 <img class="icon-android" alt='Disponible en Google Play' src='{{ asset('/img/icon-store-android.png') }}'/>
                               </a>
                             </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
         </div>

       </div>
     </div>



     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.5/umd/popper.min.js"></script>
     <script src="{{asset('/js/jquery.min.js')}}"></script>
     <script src="{{asset('/js/bootstrap.min.js')}}"></script>
     <script src="{{asset('/js/bootstrap-select.min.js')}}"></script>
     <script src="{{asset('/js/idle-timer.min.js')}}"></script>
     <script src="{{asset('/js/jquery.easypiechart.min.js')}}"></script>
     <script src="{{asset('/js/jquery.slimscroll.min.js')}}"></script>
     <script src="{{asset('/js/jquery.validate.min.js')}}"></script>
     <script src="{{ asset('js/sweetalert.min.js') }}"></script>
     <script src="{{asset('/js/bootstrap-datepicker.min.js')}}"></script>
     <script src="{{asset('/js/toastr.min.js')}}"></script>
   </body>
</html>
