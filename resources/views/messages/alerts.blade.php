@extends('layouts.app')
@section('sidebar')
   @include('layouts.sidebar', ['page'=>'messages'])
@endsection

@section('content')
   <div class="row justify-content-center">
      <div class="col-12 col-xl-7">
         <div class="ibox">
           <div class="ibox-head">
             <div class="ibox-title">
               Alertas
             </div>

           </div>
           {!! Form::open(['route' => 'alerts.store']) !!}
           	<div class="ibox-body">
          			<div class="row">
                   <div class="col-sm-6 form-group mb-2">
                      <div class="form-group" id="start_date">
                        	<label class="font-normal">Desde</label>
                        	<div class="input-group date">
                          	<span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                          	<input class="form-control" name="from" value="{{ old('from') }}" type="text" value="{{ date('d-m-Y') }}" required>
                        	</div>
                      </div>
                   </div>

                   <div class="col-sm-6 form-group mb-2">
                      <div class="form-group" id="finish_date">
                        	<label class="font-normal">Hasta</label>
                        	<div class="input-group date">
                          	<span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                          	<input class="form-control" name="to" value="{{ old('to') }}" type="text" value="{{ date('d-m-Y') }}" required>
                        	</div>
                      </div>
                   </div>
          			</div>

                <div class="row">
                  <div class="col-sm-12">
          			   <label class="font-normal">Contenido</label>
                     <textarea rows="8" id="summernote" class="form-control form-control-air" name="message" required></textarea>
                 	</div>
              	</div>

          	         <br>
                <button class="btn btn-primary" type="submit">Publicar Anuncio</button>
          		</div>
        		 {!! Form::close() !!}
      	</div>
      </div>
  <div class="col-12 col-xl-5">
     <div class="ibox">
      <div class="ibox-head">
        <div class="ibox-title">Lista de alertas</div>
          <div class="ibox-tools">
        </div>
      </div>
      <div class="ibox-body" >
        <div class="table-responsive" id="alert-list-table"></div>
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
         toolbar: [
          ["font", ["bold"]],
        ],
  		});
	});
	</script>

	<script>
   $('#start_date .input-group.date').datepicker({
      todayBtn: "linked",
      keyboardNavigation: false,
      forceParse: false,
      calendarWeeks: true,
      format: "dd-mm-yyyy",
      startDate: "01-01-1910",
      endDate: "01-01-2030",
      language: "es",
      orientation: "bottom auto",
      autoclose: true,
      maxViewMode: 3,
      todayHighlight: true
   });
   </script>

   <script>
   $('#finish_date .input-group.date').datepicker({
      todayBtn: "linked",
      keyboardNavigation: false,
      forceParse: false,
      calendarWeeks: true,
      format: "dd-mm-yyyy",
      startDate: "01-01-1910",
      endDate: "01-01-2030",
      language: "es",
      orientation: "bottom auto",
      autoclose: true,
      maxViewMode: 3,
      todayHighlight: true
   });
   </script>

   <script>
  $(document).ready(function(){
    var op = "";
    $.ajax({
      type:'get',
      url: '/alert-list',
      success: function(resp){
        console.log('0hola');
        op+='<table class="table table-striped">';
        op+='<tr><th width="50%">Mensaje</th><th width="20%">Desde</th><th width="20%">Hasta</th><th width="10%">Acciones</th></tr>';
          for(var i=0;i<resp.length;i++){
            op += '<tr>';
            op += '<td>'+resp[i].message+'</td>'+
              '<td>'+resp[i].from+'</td>'+
              '<td>'+resp[i].to+'</td><td><button class="btn btn-danger remove-item" data-id="'+resp[i].id+'" data-name="'+resp[i].message+'">Eliminar</button></td></tr>';
          }
          op+='</table>';
          $('#alert-list-table').html(op);
      },
      error: function(){
        console.log("Error Occurred");
      }
    });
  });

 $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

    /* REMOVE ALERT */
    $("body").on("click",".remove-item",function() {
    var id = $(this).data('id');
    var c_obj = $(this).parents("tr");
      swal({
          title: "Seguro desea eliminar esta alerta?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonClass: 'btn-danger',
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Eliminar',
          closeOnConfirm: false,
      },function(){
        $.ajax({
          dataType: 'json',
          type:'delete',
          url: '/alert-list/' + id,
        }).done(function(data) {
          c_obj.remove();
          swal.close();
          toastr.success('Alerta eliminada', {timeOut: 5000});
        });
      });
  });

</script>

@endsection