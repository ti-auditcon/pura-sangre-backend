@extends('layouts.app')

@section('sidebar')
  
@include('layouts.sidebar', ['page' => 'users'])

@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="ibox ibox-fullheight">
            <div class="ibox-head">
                <div class="ibox-title">Usuarios del Sistema</div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="ibox-body">
                <div class="cargando"></div>
                <table id="users-table" class="table table-hover">
                    <thead class="thead-default">
                        <tr>
                            <th width="30%">Rut</th>
                            <th width="50%">Alumno</th>
                            <th width="20%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->rut }}</td>
                            
                            <td>{{ $user->full_name }}</td>
                            
                            <td>
                                <a href="{{ route('admin.role-user.edit', ['role_user' => $user->id]) }}" class="btn btn-success">
                                    Editar Roles
                                </a>
{{--                                 <button 
                                    class="btn btn-success save-value"
                                    name="save_value"
                                    data-target="#user-assign"
                                    data-toggle="modal"
                                    data-roles="{{ $user->roles }}"
                                    data-user_id="{{ $user->id }}"
                                    data-rut="{{ $user->rut }}"
                                    data-first_name="{{ $user->first_name }}"
                                    data-last_name="{{ $user->last_name }}"
                                >
                                    Editar Roles
                                </button> --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-7">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Personal</div>
            </div>
            <div class="ibox-body">
                <table id="personal-table" class="table table-hover">
                    <thead class="thead-default">
                        <tr>
                            <th width="30%">Rut</th>
                            <th width="50%">Usuario</th>
                            <th width="20%">Roles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users_with_roles as $user)
                        <tr>
                            <td>{{ $user->rut }}</td>
                            
                            <td>{{ $user->full_name }}</td>
                            
                            <td>
                                @foreach ($user->roles as $role)
                                    <li>{{ ucfirst($role->role) }}</li>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.personal.modals.assign-role')

@endsection


@section('css') {{-- stylesheet para esta vista --}}

<link href="{{asset('/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />

@endsection



@section('scripts') {{-- scripts para esta vista --}}

{{--  DATATABLE --}}
<script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
    
<script src="{{ asset('/js/datatables.min.js') }}"></script>
    
{{-- <script src="{{ asset('/js/dataTables.checkboxes.min.js') }}"></script> --}}
    
{{-- <script src="{{ asset('/js/bootstrap-tagsinput.min.js') }}"></script> --}}

<script>
    var table = $('#users-table').DataTable({
        "paging": true,
        "ordering": true,
        "dom": '<"top"lf>t<"bottom"p><"clear">',
        "language": {
            "lengthMenu": "Mostrar _MENU_ elementos",
            "zeroRecords": "Sin resultados",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Sin resultados",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Filtrar:",
             "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
        },
    });

    // $( document ).ready(function() {

    //     $('.save-value').click(function () {
    //         var modal = $('#user-assign').modal();

    //         console.log(modal);

    //         var user_id = $(this).data("user_id"), 
    //         roles = $(this).data("roles"), 
    //         rut = $(this).data("rut"), 
    //         first_name = $(this).data("first_name"),
    //         last_name = $(this).data("last_name");

    //         console.log(roles);

    //         modal.find( "#user_id" ).val( user_id );
    //         modal.find( "#rut" ).text( rut );
    //         modal.find( "#full_name" ).text( first_name + ' ' + last_name );

    //         $('#checkbox-roles input').prop('checked', false);

    //         $.each(roles, function (index, role) {
    //             $('#checkbox-roles input[value="'+ role.id +'"]').prop('checked', true);
    //         });

    //         $('#user-assign').modal('show');
    //     });
    // });
</script>
{{--  End datatable --}}


@endsection