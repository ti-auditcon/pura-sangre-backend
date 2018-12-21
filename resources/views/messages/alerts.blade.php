@extends('layouts.app')
@section('sidebar')
   @include('layouts.sidebar', ['page'=>'messages'])
@endsection

@section('content')
   <div class="row justify-content-center">
      <div class="col-12">
         <div class="ibox">
           <div class="ibox-head">
             <div class="ibox-title">
               Alertas
             </div>
           </div>
           	<div class="ibox-body">
          			<div class="row">
                   <div class="col-sm-6 form-group mb-2">
                      <div class="form-group" id="start_date">
                        	<label class="font-normal">Desde</label>
                        	<div class="input-group date">
                          	<span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                          	<input class="form-control form-control-air" name="birthdate" value="{{ old('birthdate') }}" type="text" value="{{ date('d/m/Y') }}">
                        	</div>
                      </div>
                   </div>

                   <div class="col-sm-6 form-group mb-2">
                      <div class="form-group" id="finish_date">
                        	<label class="font-normal">Hasta</label>
                        	<div class="input-group date">
                          	<span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                          	<input class="form-control form-control-air" name="birthdate" value="{{ old('birthdate') }}" type="text" value="{{ date('d/m/Y') }}">
                        	</div>
                      </div>
                   </div>
          			</div>

                <div class="row">
                  <div class="col-sm-12">
          					        <label class="font-normal">Contenido</label>
                          <textarea rows="8" id="summernote" class="form-control form-control-air" name="content" required></textarea>
                 	</div>
              	</div>

          	         <br>
                <button class="btn btn-primary" type="submit">Publicar Anuncio</button>
          		</div>
        		{{-- {!! Form::close() !!} --}}
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
