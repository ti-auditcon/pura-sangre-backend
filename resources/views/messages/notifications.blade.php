@extends('layouts.app')
@section('sidebar')
   @include('layouts.sidebar', ['page'=>'messages'])
@endsection

@section('content')
   <div class="row justify-content-center">

      <div class="col-6">
         <div class="ibox">
           <div class="ibox-head">
             <div class="ibox-title">
               Notificaciones a la App
             </div>
           </div>
           {!!Form::open(['url' => ['/notifications'], 'method' => 'post'])!!}
           	<div class="ibox-body">
               <div class="row">
                  <div class="col-sm-12">
                     <label class="font-normal">Asunto</label>
                     <input type="text" class="form-control" name="title">
                  </div>
               </div>
               <br>
               <div class="row">
                  <div class="col-sm-12">
          			   <label class="font-normal">Contenido</label>
                     <textarea rows="8" id="summernote" class="form-control" name="message"></textarea>
                 	</div>
              	</div>
     	         <br>
               <button class="btn btn-primary" type="submit">Enviar notificación</button>
       		</div>
        		{!! Form::close() !!}
      	</div>
      </div>

       <div class="col-6">
         <div class="ibox">
           <div class="ibox-head">
             <div class="ibox-title">
               Últimos mensajes
             </div>
           </div>
            <div class="ibox-body">
               <div class="row">
                  <div class="col-sm-12">
                     <label class="font-normal">Asunto</label>
                     <input type="text" class="form-control" name="" required>
                  </div>
               </div>
               <br>
            </div>
            {!! Form::close() !!}
         </div>
      </div>
   </div>

@endsection


@section('css') {{-- stylesheet para esta vista --}}
	<link href="{{asset('css/summernote.css')}}" rel="stylesheet" />
@endsection



@section('scripts') {{-- scripts para esta vista --}}

	<script src="{{asset('/js/summernote.min.js')}}"></script>

	<script>
	$(document).ready(function() {
  		$('#summernote').summernote({
  			height: 250,
  		});
	});
	</script>

	<script>
	// Bootstrap datepicker
	$('#start_date .input-group.date').datepicker({
  		todayBtn: "linked",
  		keyboardNavigation: false,
  		forceParse: false,
  		calendarWeeks: true,
  		autoclose: true
	});
	</script>

	<script>
	// Bootstrap datepicker
	$('#finish_date .input-group.date').datepicker({
  		todayBtn: "linked",
  		keyboardNavigation: false,
  		forceParse: false,
  		calendarWeeks: true,
  		autoclose: true
	});
	</script>

@endsection

{{--  if($('input[type=checkbox]').prop('checked');){
      console.log('si esta checkeado');
   } --}}
