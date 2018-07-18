<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/css/font-awesome.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/css/line-awesome.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/css/themify-icons.css')}}" rel="stylesheet" />
    <link href="{{asset('/css/line-awesome.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/css/animate.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/css/bootstrap-select.min.css')}}" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <!-- THEME STYLES-->
    <link href="{{asset('/css/main.min.css')}}" rel="stylesheet" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

</head>
<body class="fixed-navbar">
  <div class="page-wrapper">

    @include('layouts.header')
    @yield('sidebar')

    <div class="wrapper content-wrapper">
      <div class="page-content fade-in-up" >
        @yield('content')
      </div>

    </div>
  </div>

  <!-- CORE PLUGINS-->
  <script src="{{asset('/js/jquery.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.5/umd/popper.min.js"></script>
  <script src="{{asset('/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('/js/idle-timer.min.js')}}"></script>
  <script src="{{asset('/js/jquery.slimscroll.min.js')}}"></script>
  <script src="{{asset('/js/toastr.min.js')}}"></script>
  <script src="{{asset('/js/jquery.validate.min.js')}}"></script>
  <script src="{{asset('/js/bootstrap-select.min.js')}}"></script>
  <!-- PAGE LEVEL PLUGINS-->

  <!-- CORE SCRIPTS-->
  <script src="{{asset('/js/app.min.js')}}"></script>
  <!-- PAGE LEVEL SCRIPTS-->

</body>

</html>
