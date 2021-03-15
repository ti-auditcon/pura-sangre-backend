
@extends('layouts.app')

@section('sidebar')

    @include('layouts.sidebar',['page'=>'profile'])

@endsection

@section('content')

{{-- ///////////////////////////////////////////////////////////////// --}}
{{--                                                                   --}}
{{-- //                      HEADER USER INFO                        //--}}
{{--                                                                   --}}
{{-- ///////////////////////////////////////////////////////////////// --}}
<div class="ibox flex-1">
    <div class="ibox-body">
        <div class="flexbox">
            <div class="flexbox-b align-items-start">
                <div
                    class="img-avatar img-avatar-header align-self-start"
                    style="background-image: @if ($user->avatar) url('{{$user->avatar}}') @else url('{{ asset('/img/default_user.png') }}') @endif"
                    role="button" id="div-avatar" data-image="{{ $user->avatar }}"
                ></div>

                <div class="modal fade bd-example-modal-lg show" id="modal-avatar" role="dialog">
                    <div class="modal-dialog">
                            <div class="modal-content" style="max-width: 400px">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                {{-- {{ dd(auth()->user()->isAdmin()) }} --}}
                                <div class="modal-body" id="dynamic-content">
                                    <img id="avatar-img" alt="" style="width: 400px;"/>
                                </div>
                            </div>
                       </div>
                </div>

{{-- <a >Launch modal</a> --}}

                <div class="ml-1">

                    <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>

                    <span class="mr-3">{{ $user->actual_plan->plan->plan ?? "sin plan actualmente" }}</span>

                    <div class="text-left mt-2">

                        <span
                            class="badge badge-{{ $user->status_user->type }} badge-pills"
                        >
                            {{ $user->status_user->status_user }}
                        </span>

                    </div>
                </div>
            </div>
            <div class="flexbox-b align-self-start">

                {{-- @if ($user->actual_plan && $user->actual_plan->plan->has_clases == true) --}}

                    <div class="pr-2 text-right">

                        <div class="h2 mt-2 mb-0 text-warning">

                            {{ $user->actual_plan->counter ?? '-' }}

                        </div>

                        <div class="text-muted font-13">Clases disponibles</div>

                    </div>

                {{-- @endif --}}

            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">

    {{-- ///////////////////////////////////////////////////////////////// --}}
    {{--                                                                   --}}
    {{-- //                      USER PERSONAL DATA                      //--}}
    {{--                                                                   --}}
    {{-- ///////////////////////////////////////////////////////////////// --}}
    <div class="col-12 col-xl-3">
        <div class="ibox">
            <div class="ibox-head d-flex">

                <div class="ibox-title">Datos</div>

                <div class="ibox-tools">
                    @if (in_array(1, auth()->user()->rolesId()))
                        <a href="{{ route('role-user.edit', ['role_user' => $user->id]) }}"
                           class="btn btn-warning"
                        >
                            Roles
                        </a>
                    @endif

                    <a
                        class="btn btn-success text-white mr-1" style="display: inline-block;"
                        href="{{ route('users.edit', $user->id) }}"
                    >
                        Editar
                    </a>

                    @if (in_array(1, $auth_roles))

                        <button
                            class="btn btn-info btn-danger sweet-user-delete"
                            style="display: inline-block;"
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->first_name }} {{ $user->last_name }}"
                        >
                            <i class="la la-trash"></i>
                        </button>

                        {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete', 'class' => 'user-delete']) !!}
                        {!! Form::close() !!}

                    @endif
                </div>
            </div>

            <div class="ibox-body">
                <div class="row mb-2">
                    <div class="col-12 text-muted">Rut:</div>
                    <div class="col-12">{{ $user->rut_formated }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-12 text-muted">Email:</div>
                    <div class="col-12">{{ $user->email }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-12 text-muted">Fecha de nacimiento:</div>
                    <div class="col-12">{{ $user->birthdate->format('d-m-Y') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-12 text-muted">Teléfono</div>
                    <div class="col-12">{{ '+56 9 '.$user->phone ?? '' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-12 text-muted">Direccción:</div>
                    <div class="col-12">{{ $user->address }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-12 text-muted">Contacto de emergencia</div>
                    @if ($user->emergency)
                        <div class="col-12">
                            {{ $user->emergency->contact_name.'  -' ?? '' }}  {{ '+56 9 ' . $user->emergency->contact_phone ?? 'No ingresado' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-9">
        {{-- ///////////////////////////////////////////////////////////////// --}}
        {{--                                                                   --}}
        {{-- //                      USER PLANS TABLE                        //--}}
        {{--                                                                   --}}
        {{-- ///////////////////////////////////////////////////////////////// --}}
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">
                    <h5>Planes de {{ $user->first_name }}</h5>
                </div>

                @if (in_array(1, $auth_roles))
                    <div class="ibox-tools">
                        <a
                            class="btn btn-success text-white"
                            href="{{ route('users.plans.create', $user->id) }}"
                        >
                            Asignar Plan
                        </a>
                    </div>
                @endif
            </div>

            <div class="ibox-body">
                <div class="table-responsive">
                    <table id="students-table" class="table table-hover">
                        <thead class="thead-default thead-lg">
                            <tr>
                                <th width="15%">Plan</th>
                                <th width="10%">Fecha Pago</th>
                                <th width="17%">Periodo</th>
                                <th width="7%">Clases</th>
                                <th width="10%">Medio de pago</th>
                                <th width="10%">Monto</th>
                                <th width="10%">Estado</th>
                                <th width="12%">Acciones</th>
                                <th>plan_status_id</th>
                                <th>start_date</th> {{-- 9 --}}
                                <th>payment_date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($user->planes_del_usuario() as $plan_user)
                            <tr>
                                <td>
                                    <a href="{{ url("/users/{$user->id}/plans/{$plan_user->id}/edit") }}"
                                        {{-- class="sweet-user-plan-info" --}}
                                        data-user-id="{{ $user->id }}"
                                        data-plan-id="{{ $plan_user->id }}"
                                    >
                                        {{ optional($plan_user->plan)->plan }} <span class="la la-edit"></span>
                                    </a>
                                </td>

                                @if ($plan_user->bill)
                                    <td>{{ Carbon\Carbon::parse($plan_user->bill->date)->format('d-m-Y') }}</td>
                                @else
                                    <td>no aplica</td>
                                @endif

                                <td>
                                    {{ $plan_user->start_date->format('d-m-Y') }} al {{ $plan_user->finish_date->format('d-m-Y') }}
                                </td>

                                <td>{{ $plan_user->counter }} / {{ optional($plan_user->plan)->class_numbers ?? 1}}</td>

                                <td>{{ $plan_user->bill->payment_type->payment_type ?? "no aplica" }}</td>

                                <td>{{ '$ '.number_format(optional($plan_user->bill)->amount, $decimal = 0, '.', '.') }}</td>

                                <td>
                                    <span class="badge badge-{{ $plan_user->statusColor }} badge-pill">
                                        {{ strtoupper($plan_user->status) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="row">
                                        @if (in_array(1, $auth_roles) && $plan_user->plan_status->can_delete)
                                            <form action="{{ route('users.plans.annul', [
                                                                   'user' => $user->id, 'plan' => $plan_user->id
                                                          ]) }}"
                                                  method="POST"
                                                  id="annul{{ $plan_user->id }}"
                                                  class="user-plan-annul"
                                            >
                                                @csrf
                                            </form>
                                            <div class="col-2">
                                                <button
                                                class="btn btn-info btn-icon-only btn-danger sweet-user-plan-annul"
                                                data-id="{{ $plan_user->id }}"
                                                data-name="{{ optional($plan_user->plan)->plan }}"
                                                >
                                                    <i class="la la-ban"></i>
                                                </button>
                                            </div>
                                            @if ($plan_user->plan_status_id === 1)
                                                <div class="col-2">
                                                    <button class="btn btn-icon-only btn-warning freeze-plan-button"
                                                            data-toggle="modal"
                                                            data-target="#freeze-plan-modal"
                                                            data-plan-user="{{ $plan_user->id }}"
                                                            data-user="{{ optional($plan_user->user)->id }}"
                                                    >
                                                        <i class="la la-power-off"></i>
                                                    </button>
                                                </div>
                                            @endif
                                            @if($plan_user->plan_status_id === App\Models\Plans\PlanStatus::CONGELADO && $plan_user->postpone)
                                                <form method="POST" class="user-plan-unfreeze"
                                                        action="{{ route('postpones.destroy', ['postpone' => $plan_user->postpone->id]) }}"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <div class="col-2">
                                                    <button
                                                        class="btn btn-info btn-icon-only btn-success sweet-user-plan-unfreeze"
                                                        data-plan-name="{{ $plan_user->plan->plan }}"
                                                        data-user-name="{{ $plan_user->user->first_name }}"
                                                    >
                                                        <i class="la la-play-circle-o"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        {{-- Check if User has role ADMIN and if status plan is Clompleted --}}
                                        @elseif (in_array(1, $auth_roles) && $plan_user->plan_status_id == 5)
                                            {!! Form::open([
                                                    'route' => [
                                                        'users.plans.destroy',
                                                        'user' => $user->id,
                                                        'plan' => $plan_user->id
                                                    ],
                                                    'method' => 'delete',
                                                    'class' => 'user-plan-delete',
                                                    'id' => 'delete' . $plan_user->id
                                            ]) !!}
                                            {!! Form::close() !!}
                                            <div class="col-2">
                                                <button
                                                    class="btn btn-info btn-icon-only btn-danger sweet-user-plan-delete"
                                                    data-id="{{ $plan_user->id }}"
                                                    data-name="{{ $plan_user->plan->plan }}"
                                                >
                                                    <i class="la la-trash"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $plan_user->plan_status_id }}</td>
                                <td>{{ $plan_user->start_date }}</td>

                                @if ($plan_user->bill)
                                    <td>{{ $plan_user->bill->date }}</td>
                                @else
                                    <td>no aplica</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ///////////////////////////////////////////////////////////////// --}}
        {{--                                                                   --}}
        {{-- //                      PPROXIMAS CLASES                        //--}}
        {{--                                                                   --}}
        {{-- ///////////////////////////////////////////////////////////////// --}}
        {{-- @if ($user->future_reservs) --}}

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
                            @forelse($user->future_reservs as $reserv)
                            <tr>
                                <td>
                                    <a href="{{ url('/clases/' . optional($reserv->clase)->id) }}">
                                        {{ optional($reserv->clase)->id }}
                                    </a>
                                </td>

                                <td>{{ optional($reserv->clase)->date }}</td>

                                <td>{{ $reserv->clase ? Carbon\Carbon::parse($reserv->clase->start_at)->format('H:i') . ' a ' . Carbon\Carbon::parse($reserv->clase->finish_at)->format('H:i') : '' }}</td>

                                <td>{{ $reserv->reservation_status->reservation_status }}</td>


                                <td>{{ optional($reserv->plan_user)->id ?? 'No Aplica' }}</td>

                                <td>{{ $reserv->plan_user ? $reserv->plan_user->plan->plan : 'No Aplica' }}</td>

                            </tr>
                            @empty
                                {{-- Sin Clases que mostrar --}}
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- @endif --}}

        {{-- ///////////////////////////////////////////////////////////////// --}}
        {{--                                                                   --}}
        {{-- //                         PAST RESERVS                         //--}}
        {{--                                                                   --}}
        {{-- ///////////////////////////////////////////////////////////////// --}}
        @if ($past_reservations)
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
                                @foreach($past_reservations as $reserv)
                                <tr>
                                    @if ($reserv->clase)
                                        <td>
                                            <a href="{{ url("/clases/{$reserv->clase->id}") }}">
                                                {{ $reserv->clase->id }}
                                            </a>
                                        </td>
                                        <td>{{ $reserv->clase->date }}</td>
                                        <td>{{ Carbon\Carbon::parse($reserv->clase->start_at)->format('H:i') }} a {{ Carbon\Carbon::parse($reserv->clase->finish_at)->format('H:i') }}</td>
                                    @else 
                                        <td>sin clase asociada</td>
                                        <td>sin fecha</td>
                                        <td>sin hora</td>
                                    @endif
                                    
                                    <td>{{ $reserv->reservation_status->reservation_status }}</td>

                                    <td>{{ optional($reserv->plan_user)->id ?? 'No Aplica' }}</td>

                                    <td>{{ $reserv->plan_user ? optional($reserv->plan_user->plan)->plan : 'No Aplica' }}</td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

<!-- Modal para congelar plan -->
@include('users.modals.freeze-plan')
<!-- END Modal para congelar plan -->

@endsection

{{-- stylesheet para esta vista --}}
@section('css')

@endsection

{{-- scripts para esta vista --}}
@section('scripts')

<script>
	$('.sweet-user-delete').click(function(e) {
        var id = $(this).data('id');

        swal({
			title: "Desea eliminar al usuario: "+$(this).data('name')+"?",
			text: "(Se borrarán todas las cuotas o planes futuros, manteniendo los ya consumidos)",
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: 'btn-danger',
			cancelButtonText: 'Cancelar',
			confirmButtonText: 'Eliminar',
			closeOnConfirm: false
        },function(){
			//redirección para eliminar usuario
            $('form.user-delete').submit();
		});
	});
</script>

<script>
    $(document).ready(function () {
        $("#div-avatar").click(function () {
            $('#avatar-img').attr('src', $("#div-avatar").data('image'));

            $('#modal-avatar').modal('show');
        });
    });
</script>

<script>
    $( '.sweet-user-plan-unfreeze' ).click(function() {
        // var id = $(this).data('id');
        var plan_name = $( this ).data( 'plan-name' );

        var user_name = $( this ).data( 'user-name' );

        console.log('golad');

        swal({
            title: "Desea reanudar el Plan "+ plan_name +" a: " + user_name + "?",
            // text: "(Se borrarán todas las cuotas o planes futuros, manteniendo los ya consumidos)",
            type: 'success',
            showCancelButton: true,
            confirmButtonClass: 'btn-success',
            confirmButtonText: 'Reanudar',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: false,
        }, function() {
            $('form.user-plan-unfreeze').submit();
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
            $('#students-table').DataTable({
                "paging": true,
                "ordering": true,
                "order": [[ 7, 'desc' ]],
                "columnDefs": [
                    { "targets": [ 8, 9, 10 ], "visible": false },
                    { "targets": [ 1 ], "orderData": [ 10 ] },
                    { "targets": [ 2 ], "orderData": [ 9 ] }
                ],
                "pageLength": 8,
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
        columnDefs: [{
           targets: 1,
              render: $.fn.dataTable.render.moment('', 'DD-MM-YYYY')
        }],
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

   <script>
        let start_date = moment().format("DD-MM-YYYY");

        $('.datepicker-date-start-freeze').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: true,
            calendarWeeks: true,
            format: "dd-mm-yyyy",
            startDate: start_date,
            endDate: "01-01-2035",
            language: "es",
            orientation: "bottom auto",
            autoclose: true,
            maxViewMode: 3,
            todayHighlight: true
        });

        $('.datepicker-date-end-freeze').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: true,
            calendarWeeks: true,
            format: "dd-mm-yyyy",
            startDate: start_date,
            endDate: "01-01-2035",
            language: "es",
            orientation: "bottom auto",
            autoclose: true,
            maxViewMode: 3,
            todayHighlight: true
        });

        $('.freeze-plan-button').click( function(e) {
            var plan_user_id = $(this).data('plan-user'),

                route = '{!! route('plan-user.postpones.store', ['plan_user' => ":plan_user"]) !!}',

                url = route.replace(':plan_user', plan_user_id);

            $('#form-plan-freeze').attr('action', url);

            // plan_user_id log
            console.log('plan_user_id: ' + plan_user_id);
            // user_id log
            console.log('user_id: ' + user_id);
            // url log
            console.log('url: ' + url);
        });

        // FALTA CORRER EL PLAN HACIA ADELANTE EN EL CONTROLADOR

   </script>
@endsection
