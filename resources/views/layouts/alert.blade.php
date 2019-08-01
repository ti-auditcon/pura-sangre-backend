{{-- alertas success --}}
@if(Session::has('success'))
  <script >
  $(function ()
  {
    window.onload = function()
    {
      toastr.options =
      {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "5000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }
      toastr.success("{!! Session::get('success') !!}");
    };
  });
  </script>
@endif
{{-- FIN alertas success --}}

{{-- alerta warning --}}
@if(Session::has('warning'))
  <script >
  $(function ()
  {
    window.onload = function()
    {
      toastr.options =
      {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "600",
        "hideDuration": "5000",
        "timeOut": "8000",
        "extendedTimeOut": "500",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "slideDown",
        "hideMethod": "fadeOut"
      }
      toastr.warning("{!! Session::get('warning') !!}");
    };
  });
  </script>
@endif
{{-- FIN alertas warning --}}

{{-- alerta warning --}}
@if(Session::has('error-tap'))
  <script >
  $(function ()
  {
    window.onload = function()
    {
      toastr.options =
      {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": 0,
        "extendedTimeOut": 0,
        "showEasing": "swing",
        "closeEasing" : "swing",
        "closeMethod" : "fadeOut",
        "closeDuration" : 300,
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "tapToDismiss": false
      }
      toastr.error('{!! Session::get('error-tap') !!}<br /><br /><button id="btn-toast" type="button" class="btn clear">Entendido</button>');

      $('#btn-toast').click(function () {
        toastr.remove()
      });
    };
  });
  </script>
@endif
{{-- FIN alertas warning --}}


{{-- alerta error --}}
@if(Session::has('error'))

  <script >

  $(function ()
  {
    window.onload = function()
    {
       //Command: toastr["error"]("Clear itself?<br /><br /><button type="button" class="btn clear">Yes</button>")
      toastr.options =
      {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "0",
        "hideDuration": "0",
        "timeOut": "0",
        "extendedTimeOut": "0",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }
      toastr.options.escapeHtml = false;
      toastr.error("{!! Session::get('error') !!}");
    };
  });
  </script>
@endif
{{-- FIN alerta error --}}

{{-- errores --}}
@if(count($errors->all())!=0)
  <script defer>
  $(function ()
  {
    window.onload = function()
    {
      toastr.options =
      {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "5000",
        "timeOut": "5000",
        "extendedTimeOut": "500",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "slideDown",
        "hideMethod": "fadeOut"
      }
      @foreach($errors->all() as $error)
      toastr.warning("{!! $error !!}");
      @endforeach
    };
  });
  </script>


@endif
{{-- fin errores --}}
