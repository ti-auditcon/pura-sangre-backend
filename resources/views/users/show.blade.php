@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'profile'])
@endsection

@section('content')
  <div class="ibox flex-1">
    <div class="ibox-body">
      <div class="flexbox">
        <div class="flexbox-b align-items-start">

          <div class="img-avatar img-avatar-header align-self-start" style="background-image: @if ($user->avatar) url('{{$user->avatar}}') @else url('{{ asset('/img/default_user.png') }}') @endif "></div>
          <div class="ml-1">

            <h4 class="mb-1">{{$user->first_name}} {{$user->last_name}}</h4>
            <span class="mr-3">{{$user->actual_plan->plan->plan ?? "sin plan actualmente" }}</span>

            <div class="text-left mt-2">
              <span class="badge badge-{{$user->status_user->type}} badge-pills">{{$user->status_user->status_user}}</span>
            </div>
          </div>
        </div>
        <div class="flexbox-b align-self-start">
          @if ($user->actual_plan != null)
            @if ($user->actual_plan->plan->has_clases == true)
              <div class="pr-2 text-right">
                <div class="h2 mt-2 mb-0 text-warning">
                  {{$user->actual_plan->counter}}
                </div>
                <div class="text-muted font-13">Clases disponibles</div>
              </div>
            @endif
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-12 col-xl-4">
      <div class="ibox">
        <div class="ibox-head d-flex">
          <div class="ibox-title">Datos</div>
          <div class="ibox-tools">
            <a class="btn btn-success text-white mr-1" style="display: inline-block;" href="{{ route('users.edit', $user->id) }}">Editar</a>

              @if (Auth::user()->hasRole(1))
                 <button class="btn btn-info btn-danger sweet-user-delete" style="display: inline-block;" data-id="{{$user->id}}" data-name="{{$user->first_name}} {{$user->last_name}}"><i class="la la-trash"></i></button>
                 {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete', 'class' => 'user-delete']) !!}
                 {!! Form::close() !!}
              @endif
          </div>
        </div>
        <div class="ibox-body">
              <div class="row mb-2">
                <div class="col-12 text-muted">Rut:</div>
                <div class="col-12">{{Rut::set($user->rut)->fix()->format()}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Email:</div>
                <div class="col-12">{{$user->email}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Fecha de nacimiento:</div>
                <div class="col-12">{{$user->birthdate->format('d-m-Y')}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Teléfono</div>
                <div class="col-12">{{'+56 9 '.$user->phone ?? ''}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Direccción:</div>
                <div class="col-12">{{$user->address}}</div>
              </div>
              <div class="row mb-2">
                <div class="col-12 text-muted">Contacto de emergencia</div>
                @if ($user->emergency)
                <div class="col-12">{{$user->emergency->contact_name.'  -' ?? ''}}  {{'+56 9 '.$user->emergency->contact_phone ?? 'No ingresado'}}</div>
                @endif
              </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-8">

      <div class="ibox">
        <div class="ibox-head">
          <div class="ibox-title">Planes</div>
            @if (Auth::user()->hasRole(1))
            <div class="ibox-tools">
              <a class="btn btn-success text-white" href="{{ route('users.plans.create', $user->id) }}">Asignar Plan</a>
            </div>
            @endif
        </div>
        <div class="ibox-body">
          <div class="table-responsive">
            <table id="students-table" class="table table-hover">
              <thead class="thead-default thead-lg">
                <tr>
                  <th width="15%">Plan</th>
                  <th width="12%">Fecha Pago</th>
                  <th width="19%">Periodo</th>
                  <th width="7%">Clases</th>
                  <th width="15%">Medio de pago</th>
                  <th width="10%">Monto</th>
                  <th width="10%">Estado</th>
                  {{-- @if (Auth::user()->hasRole(1))
                  @endif --}}
                  <th width="10%">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($user->plan_users as $plan_user)
                <tr>
                  <td>
                    <a class="sweet-user-plan-info" data-user-id="{{$user->id}}" data-plan-id="{{$plan_user->id}}">{{$plan_user->plan->plan}}</a>@if (\Carbon\Carbon::parse($plan_user->finish_date)->gte(toDay()))
                    - <a href="{{url('/users/'.$user->id.'/plans/'.$plan_user->id.'/edit')}}"><span class="la la-edit"></span></a>
                    @endif
                  </td>

                  @if($plan_user->bill)
                     <td>{{Carbon\Carbon::parse($plan_user->bill->date)->format('d-m-Y')}}</td>
                  @else
                     <td>no aplica</td>
                  @endif
                  <td>{{$plan_user->start_date->format('d-m-Y')}} al {{$plan_user->finish_date->format('d-m-Y')}}</td>
                  <td>{{$plan_user->counter}}/{{$plan_user->plan->class_numbers}}</td>
                  <td>{{$plan_user->bill->payment_type->payment_type ?? "no aplica"}}</td>
                  @if($plan_user->bill)
                     <td>{{'$ '.number_format($plan_user->bill->amount, $decimal = 0, '.', '.')}}</td>
                  @else
                     <td>no aplica</td>
                  @endif
                  <td><span class="badge badge-{{$plan_user->plan_status->type}} badge-pill">
                     {{strtoupper($plan_user->plan_status->plan_status)}}</span>
                  </td>
                  <td>
                    @if (Auth::user()->hasRole(1) && $plan_user->plan_status->can_delete == true)
                      {!! Form::open(['route' => ['users.plans.annul', 'user' => $user->id, 'plan' => $plan_user->id], 'method' => 'post', 'class' => 'user-plan-annul',  'id'=>'annul'.$plan_user->id]) !!}
                      {!! Form::close() !!}
                      <button class="btn btn-info btn-icon-only btn-danger sweet-user-plan-annul" data-id="{{$plan_user->id}}" data-name="{{$plan_user->plan->plan}}"><i class="la la-ban"></i></button>
                    @elseif (Auth::user()->hasRole(1) && $plan_user->plan_status_id == 5)
                      {!! Form::open(['route' => ['users.plans.destroy', 'user' => $user->id, 'plan' => $plan_user->id], 'method' => 'delete', 'class' => 'user-plan-delete',  'id'=>'delete'.$plan_user->id]) !!}

                      {!! Form::close() !!}
                      <button class="btn btn-info btn-icon-only btn-danger sweet-user-plan-delete" data-id="{{$plan_user->id}}" data-name="{{$plan_user->plan->plan}}"><i class="la la-trash"></i></button>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      @if ($user->future_reservs)
     {{-- <div class="col-8"> --}}
       <div class="ibox proximas-clases">
         <div class="ibox-head">
           <div class="ibox-title">Próximas Clases</div>
         </div>
         <div class="ibox-body">
           <div class="table-responsive">
             <table id="next-clases-table" class="table table-hover">
               <thead class="thead-default thead-lg">
                  <tr>
                     <th width="10%">ID Clase</th>
                     <th width="20%">Fecha Clase</th>
                     <th width="20%">Hora</th>
                     <th width="20%">Estado</th>
                     <th width="10%">N° Plan</th>
                     <th width="20%">Plan</th>
                 </tr>
               </thead>
               <tbody>
                 @foreach($user->future_reservs as $reserv)
                 <tr>
                     <td><a href="{{url('/clases/'.$reserv->clase->id)}}">{{$reserv->clase->id}}</a></td>
                     <td>{{ $reserv->clase->date }}</td>
                     <td>{{Carbon\Carbon::parse($reserv->clase->start_at)->format('H:i')}}  a  {{Carbon\Carbon::parse($reserv->clase->finish_at)->format('H:i')}}</td>
                     <td>{{$reserv->reservation_status->reservation_status}}</td>
                     @if ($reserv->plan_user)
                        <td>{{ $reserv->plan_user->id }}</td>
                        <td>{{ $reserv->plan_user->plan->plan }}</td>
                     @else
                        <td>{{ 'No aplica' }}</td>
                        <td>{{ 'No aplica' }}</td>
                     @endif
                 </tr>
                 @endforeach
               </tbody>
             </table>
           </div>
         </div>
       </div>
     {{-- </div> --}}
      @endif

      @if ($user->past_reservs)
      {{-- <div class="col-8"> --}}
       <div class="ibox clases-pasadas">
         <div class="ibox-head">
           <div class="ibox-title">Clases Anteriores</div>
         </div>
         <div class="ibox-body">
           <div class="table-responsive">
             <table id="past-classes-table" class="table table-hover">
               <thead class="thead-default thead-lg">
                  <tr>
                     <th width="10%">ID Clase</th>
                     <th width="20%">Fecha Clase</th>
                     <th width="20%">Hora</th>
                     <th width="20%">Estado</th>
                     <th width="10%">N° Plan</th>
                     <th width="20%">Plan</th>
                  </tr>
               </thead>
               <tbody>
                 @foreach($user->past_reservs as $reserv)
                   <tr>
                     <td><a href="{{url('/clases/'.$reserv->clase->id)}}">{{$reserv->clase->id}}</a></td>
                     <td>{{$reserv->clase->date}}</td>
                     <td>{{Carbon\Carbon::parse($reserv->clase->start_at)->format('H:i')}} a {{Carbon\Carbon::parse($reserv->clase->finish_at)->format('H:i')}}</td>
                     <td>{{$reserv->reservation_status->reservation_status}}</td>
                     {{-- {{dd($reserv->plan_user)}} --}}
                  @if ($reserv->plan_user)
                        <td>{{ $reserv->plan_user->id }}</td>
                        <td>{{ $reserv->plan_user->plan->plan }}</td>
                     @else
                        <td>{{ 'No aplica' }}</td>
                        <td>{{ 'No aplica' }}</td>
                     @endif
                   </tr>
                 @endforeach
               </tbody>
             </table>
           </div>
         </div>
       </div>
      {{-- </div> --}}
      @endif
    </div>

  </div>
@endsection

{{-- stylesheet para esta vista --}}
@section('css')
@endsection

{{-- scripts para esta vista --}}
@section('scripts')

  <script>
	$('.sweet-user-delete').click(function(e){
	   var id = $(this).data('id');
			swal({
					title: "Desea eliminar al usuario: "+$(this).data('name')+"?",
					text: "(Se borrarán todas las cuotas o planes futuros, manteniendo los ya consumidos)",
					type: 'warning',
					showCancelButton: true,
					confirmButtonClass: 'btn-danger',
					cancelButtonText: 'Cancelar',
					confirmButtonText: 'Eliminar',
					closeOnConfirm: false,
			},function(){
				//redirección para eliminar usuario
         $('form.user-delete').submit();
		});
	});
	</script>

  <script>
  $('.sweet-user-plan-annul').click(function(e){
    var id = $(this).data('id');
      swal({
          title: "Desea cancelar el plan: "+$(this).data('name')+"?",
          text: "(Se borrarán todas las cuotas futuras de este plan, manteniendo los ya consumidos)",
          type: 'warning',
          showCancelButton: true,
          confirmButtonClass: 'btn-danger',
          cancelButtonText: 'Volver',
          confirmButtonText: 'Cancelar Plan',
          closeOnConfirm: false,
      },function(){
        //redirección para eliminar plan del usuario
         $('form#annul'+id).submit();
      });
  });
  </script>

  {{-- ELIMINAR UN PLAN A UN USUARIO --}}
  <script>
  $('.sweet-user-plan-delete').click(function(e){
    var id = $(this).data('id');

      swal({
          title: "Desea borrar el plan: "+$(this).data('name')+"?",
          text: "Se eliminará completamente del sistema",
          type: 'warning',
          showCancelButton: true,
          confirmButtonClass: 'btn-danger',
          cancelButtonText: 'cancelar',
          confirmButtonText: 'Eliminar',
          closeOnConfirm: false,
      },function(){
        //redirección para eliminar plan del usuario
         $('form#delete'+id).submit();
      });
  });
  </script>

   <script src="{{ asset('js/datatables.min.js') }}"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
      <script src="//cdn.datatables.net/plug-ins/1.10.19/dataRender/datetime.js"></script>

   <script>
      $(document).ready(function() {
         $('#next-clases-table').DataTable({
            columnDefs: [{
               targets: 1,
                  render: $.fn.dataTable.render.moment('', 'DD-MM-YYYY')
            }],
            "paging": true,
            "ordering": true,
            "order": [[ 0, 'asc' ]],
            "pageLength": 10,
            "bLengthChange" : false,
            "bpageLength": false,
            "bPaginate": false,
            "language": {
               "lengthMenu": "Mostrar _MENU_ elementos",
               "zeroRecords": "Sin Registros",
               "info": "",
               "infoEmpty": "Sin Registros",
               "infoFiltered": "(filtrado de _MAX_ registros totales)",
               "search": "<span>Filtrar:</span>",
               "paginate": {
                  "first": "Primero",
                  "last": "Ultimo",
                  "next": "Siguiente",
                  "previous": "Anterior"
               },
            },
         });
      });
   </script>

   <script>
      $(document).ready(function() {
         $('#past-classes-table').DataTable({
            columnDefs: [ {
               targets: 1,
                  render: $.fn.dataTable.render.moment('', 'DD-MM-YYYY')
            } ],
            "paging": true,
            "ordering": true,
            "order": [[ 0, 'desc' ]],
            "pageLength": 10,
            "bLengthChange" : false,
            "bpageLength": false,
            "bPaginate": false,
            "language": {
               "lengthMenu": "Mostrar _MENU_ elementos",
               "zeroRecords": "Sin Registros",
               "info": "",
               "infoEmpty": "Sin Registros",
               "infoFiltered": "(filtrado de _MAX_ registros totales)",
               "search": "<span>Filtrar:</span>",
               "paginate": {
                  "first": "Primero",
                  "last": "Ultimo",
                  "next": "Siguiente",
                  "previous": "Anterior"
               },
            },
         });
      });
   </script>

   <script>


  $('.sweet-user-plan-info').click(function(e){
      var user_id = $(this).data('user-id');
      var plan_id = $(this).data('plan-id');
    $.ajax({
      url: '/users/'+user_id+'/plans/'+plan_id+'/info',
      method: "GET",
      dataType: 'json',
      success: function(response) {
        response = response;
        var htmlcontent = '<div class="container"> <br/> <div class="row">Plan desde el '+response.dates+'</div>  <div class="row">Total: '+response.amount+'</div><div class="row">Estado del plan: '+response.status_plan+'</div><div class="row">Clases restantes: '+response.left_clases+'</div><div class="row">Observaciones: '+response.observations+'</div> </div>';
        swal({
          title: 'Plan '+response.plan,
          text: htmlcontent,
          showCancelButton: false,
          confirmButtonClass: 'btn-info',
          confirmButtonText: 'Ok',
          closeOnConfirm: false,
          html: true,
        });
      }
        });
    });
   </script>


@endsection
