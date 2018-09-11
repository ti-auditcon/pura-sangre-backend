{{-- alertas success --}}
@if(Session::has('success'))
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
        "timeOut": "3000",
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

{{-- alerta error --}}
@if(Session::has('error'))
  {{-- <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ Session::get('error') }}
    </div> --}}
  <script defer>

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