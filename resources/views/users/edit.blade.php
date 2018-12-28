@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
<div class="row justify-content-center">
   <div class="col-6">
      <div class="ibox">
         <div class="ibox-head">
            <div class="ibox-title">Editar datos de {{$user->first_name}} {{$user->last_name}}</div>
         </div>
      {!! Form::open(['route' => ['users.update', $user->id], 'method' => 'put', 'files' => true]) !!}
         <div class="ibox-body">
            <div class="row">
               <div class="col-sm-6 form-group mb-2">
                  <div class="form-group inline @if($errors->has('first_name')) has-warning  @endif">
                     <label class="col-form-label">Nombre</label>
                     <input class="form-control " name="first_name" value="{{ $user->first_name }}" required>
                  </div>
               </div>
               <div class="col-sm-6 form-group mb-2">
                  <div class="form-group inline @if($errors->has('last_name')) has-warning  @endif">
                     <label class="col-form-label">Apellido</label>
                     <input class="form-control " name="last_name" value="{{ $user->last_name }}" required>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="form-group col-md-4">
                  {{Session::get('error')}}
                  <label class="btn btn-info btn-edit file-input mr-2">
                     <span class="btn-icon"><i class="la la-upload"></i>Subir o cambiar imagen</span>
                     <input style="display: none" name="image" id="photoinput" type="file" accept="image/*" max-file-size=1234>
                  </label>
                  <a class="media-img align-self-start">
                  @if ($user->avatar)
                     <img class="img-circle mr-3" src="{{$user->avatar}}" id="imgback" alt="image" width="72">
                  @endif
                  </a>
                  <span class="help-block"></span>
               </div>
               <div id="container-logo" class="pull-right" style="display: none">
                  <img class="img-responsive" width="200" id="logo-img" src="#" />
               </div>
            </div>

         <div class="row">
            <div class="col-sm-6 form-group mb-2">
               <div class="form-group inline @if($errors->has('phone')) has-warning  @endif">
                  <label class="col-form-label">Número de celular</label>
                  <div class="input-group mb-3">
                     <span class="input-group-addon">+56 9</span>
                  <input class="form-control " name="phone" value="{{ $user->phone }}" type="tel">
                  </div>
               </div>
            </div>
            {{-- {{dd($user->emergency)}} --}}
            <div class="col-sm-6 form-group mb-2">
               <div class="form-group inline @if($errors->has('email')) has-warning  @endif">
                  <label class="col-form-label">Email</label>
                  <input class="form-control" name="email" value="{{ $user->email }}" @if (!Auth::user()->hasRole(1)) readonly @endif required>
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-sm-6 form-group mb-2">
               <div class="form-group">
                  <label class="font-normal">Fecha de nacimiento</label>
                  <div class="input-group date">
                     <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                     <input class="form-control" id="sandbox-container" name="birthdate" value="{{ Carbon\Carbon::parse($user->birthdate)->format('d-m-Y') }}" type="text" value="{{ date('d/m/Y') }}">
                  </div>
               </div>
            </div>


            <div class="col-sm-6 form-group mb-2">
               <div class="form-group" id="since">
                  <label class="font-normal">Atleta desde</label>
                  <div class="input-group date">
                     <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                     <input class="form-control" name="since" value="{{ Carbon\Carbon::parse($user->since)->format('d-m-Y') }}" type="text" value="{{ date('d/m/Y') }}">
                  </div>
               </div>
            </div>    
         </div>     

            <br>
            <div>
               <button class="btn btn-primary mr-2" type="submit">Actualizar datos</button>
               <a class="btn btn-secondary" href="{{ route('users.show', $user->id) }}">Volver</a>
            </div>
         </div>
      {!! Form::close() !!}
      </div>
   </div>
</div>



@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection



@section('scripts') {{-- scripts para esta vista --}}

   <script>
   // Bootstrap datepicker
   // $('#start_date .input-group.date').datepicker({
   //    todayBtn: "linked",
   //    format: "dd/mm/yyyy",
   //    language: "es",
   //    autoclose: true
   //    keyboardNavigation: false,
   //    forceParse: false,
   //    calendarWeeks: true,
   //    autoclose: true
   // });

$('#sandbox-container .input-group.date').datepicker({
    format: "dd/mm/yyyy",
    weekStart: 1,
    language: "es",
    autoclose: true,
    todayHighlight: true
});

   </script>
   <script>
      $('#sandbox-container input').datepicker({
          format: "dd/mm/yyyy",
          weekStart: 1,
          language: "es",
          autoclose: true,
          todayHighlight: true
      });
   </script>
{{--    <script defer>
   // Bootstrap datepicker
   $('#since .input-group.date').datepicker({
      todayBtn: "linked",
      format: "dd/mm/yyyy",
      language: "es",
      autoclose: true
      keyboardNavigation: false,
      forceParse: false,
      calendarWeeks: true,
      autoclose: true
   });
  </script> --}}

<script>
jQuery(function ()
{
    jQuery("input[type=file]").change(function() {
        readURL(this);
    });

    const readURL = (input) => {

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = (e) => {
                jQuery('#logo-img').attr('src', e.target.result)
                jQuery('#container-logo').css('display', 'block');
            }
            reader.readAsDataURL(input.files[0]);
        }
    };
})
</script>

<script>
   $(function() {
      $("input:file").change(function (){
         $("#imgback").prop('hidden', true);
         // console.log("si");
         // var fileName = $(this).val();
         // $(".filename").html(fileName);
     });
  });
</script>
@endsection
