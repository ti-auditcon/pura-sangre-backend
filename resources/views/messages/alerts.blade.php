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
              <h3 class="font-strong">
                <i class="fa fa-bell" aria-hidden="true"></i> Alertas en PuraSangre App
              </h3>
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
                     <textarea rows="8" id="summernote" class="form-control form-control-air" name="message" required>
                       {{ old('message') }}
                     </textarea>
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
        <div class="table-responsive">
        <div class="input-group-icon input-group-icon-left mr-3">
          <span class="input-icon input-icon-right font-16"><i class="ti-search"></i></span>
          <input class="form-control form-control-rounded form-control-solid" id="key-search" type="text" placeholder="Buscar ...">
        </div>
        <table class="table table-bordered table-hover table-striped collapsed" id="alert-list-table" style="width: 1592px;">
            <thead class="thead-default thead-lg">
              <tr role="row">
                <th class="sorting" width="40%">Mensaje</th>
                <th class="sorting">Desde</th>
                <th class="sorting">Hasta</th>
                <th class="sorting" width="10%">Acciones</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>


@endsection


@section('css') {{-- stylesheet para esta vista --}}
	<link href="{{asset('css/summernote.css')}}" rel="stylesheet" />
@endsection


{{-- SCRIPTS PARA ESTA VISTA --}}
@section('scripts') 
  <script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('/js/datatables.min.js') }}"></script>
  <script src="{{ asset('/js/dataTables.checkboxes.min.js') }}"></script>
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

      var table = $('#alert-list-table').DataTable({
        "ajax": {
          "url": '/alert-list',
          "dataType": "json",
          "type": "GET",
          "data": {"_token": "<?= csrf_token() ?>"},
        },
        "language": {
            "zeroRecords": "Sin resultados",
            "info": " ",
            "infoEmpty": "Sin resultados",
            "paginate": {
              "next":     "Siguiente",
              "previous": "Anterior"
            },
          },
        // "bFilter": false,
        "dom": '<"top">rt<"bottom"ilp><"clear">',
        "lengthChange": false,
        "pageLength": 8,
        "columnDefs": [ {
            "targets": -1,
            "orderable": false,
            "data": "id",
            "defaultContent": "<button class='btn btn-danger remove-item'>Borrar</button>",
        } ],
        "columns":[
          {"data": "message"},
          {"data": "from"},
          {"data": "to"}, 
          {"data": null}
        ],
      });

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    // $(document).ready(function() {
      var table = $('#alert-list-table').DataTable();
      $('#key-search').on('keyup', function() {
        table.search(this.value).draw();
      });
    // });

    /* REMOVE ALERT */
    $("body").on("click",".remove-item",function() {
      var data = table.row( $(this).parents('tr') ).data();
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
          url: '/alert-list/' + data.id,
        }).done(function(data) {
          c_obj.remove();
          swal.close();
          toastr.success('Alerta eliminada', {timeOut: 5000});
        });
      });
  });

</script>

@endsection