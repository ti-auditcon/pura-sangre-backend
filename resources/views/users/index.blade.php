@extends('layouts.app')

@section('sidebar')
  
  @include('layouts.sidebar',['page'=>'users'])

@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="ibox ibox-fullheight">
            
            <div class="ibox-head">
                
                <div class="ibox-title">Alumnos</div>
               
                <div class="ibox-tools">
                    
                    <a class="btn btn-success text-white" href="{{ route('users.create')}}">Nuevo alumno</a>
                
                </div>
           
            </div>

            <div class="ibox-body">
                
                <div class="flexbox mb-4">
                
                    <div class="flexbox">
                
                        <span class="flexbox mr-3">
                
                            <div class="btn-group">
                
                                <button class="btn btn-outline-success user-filter" data-status="1">

                                    <span class="btn-icon">ACTIVOS</span>
                
                                </button>
                
                                <span class="btn-label-out btn-label-out-right btn-label-out-success pointing">

                                    {{ $status_users->where('status_user_id', 1)->pluck('total')->pull(0) }}
                                
                                </span>
                            
                            </div>
                        
                        </span>
                
                        <span class="flexbox mr-3" >
                
                            <div class="btn-group">
                
                                <button class="btn btn-outline-danger user-filter" data-status="2">
                
                                    <span class="btn-icon">INACTIVOS</span>
                
                                </button>
                                
                                <span class="btn-label-out btn-label-out-right btn-label-out-danger pointing">

                                    {{ $status_users->where('status_user_id', 2)->pluck('total')->pull(0) }}
                                
                                </span>
                            
                            </div>

                        </span>
                        
                        <span class="flexbox mr-3">
                            
                            <div class="btn-group">
                                
                                <button class="btn btn-outline-warning user-filter" data-status="3">
                                
                                    <span class="btn-icon">PRUEBA</span>
                                
                                </button>
                                
                                <span class="btn-label-out btn-label-out-right btn-label-out-warning pointing">

                                    {{ $status_users->where('status_user_id', 3)->pluck('total')->pull(0) }}
                                
                                </span>
                            
                            </div>
                        
                        </span>
                        
                        <span class="flexbox mr-3">
                            
                            <div class="btn-group">
                                
                                <button class="btn btn-outline-primary user-filter" data-status="">
                                
                                    <span class="btn-icon">TODOS</span>
                                
                                </button>
                                
                                <span class="btn-label-out btn-label-out-right btn-label-out-primary pointing">
                                    {{ $status_users->sum('total') }}
                                </span>
                            </div>
                        </span>
                        <a
                            class="btn btn-info btn-labeled btn-labeled-left btn-icon"
                            style="display: inline-block;" href="{{ route('users.export')}}"
                        >
                            <span class="btn-label"><i class="la la-cloud-download"></i></span>
                            
                            Excel alumnos
                        </a>
                    
                    </div>
                
                </div>

                <div class="modal fade bd-example-modal-lg show" id="modal-avatar" role="dialog">
                    <div class="modal-dialog">
                            <div class="modal-content" style="max-width: 400px">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="dynamic-content">
                                    <img id="avatar-img" alt="" style="width: 400px;"/>
                                </div>
                            </div>
                       </div>
                </div> 

                <div class="table-responsive">
                    <table id="students-table" class="table table-hover">
                        <thead class="thead-default thead-lg">
                            <tr>
                                <th width="30%">Alumno</th>

                                <th width="10%">Correo</th>
                            
                                <th width="10%">RUN</th>
                            
                                <th width="10%">Plan Activo</th>
                            
                                <th width="10%">Vencimiento</th>
                            
                                <th width="15%">Período</th>
                            
                                <th width="5%">Acciones</th>
                            
                                <th width="10%">status</th>
                            </tr>
                        </thead>
                        <tbody>
                    {{--         @foreach ($users as $user)
                            <tr>
                                <td style="vertical-align: middle;">
                                    <div
                                        class="img-avatar"
                                        style="background-image: @if ($user->avatar) url('{{ $user->avatar }}') @else url('{{ asset('/img/default_user.png') }}') @endif"
                                    >
                                    </div>

                                    <a href="{{ url('/users/'.$user->id) }}">
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </a>
                                </td>
                                
                                <td>{{ Rut::set($user->rut)->fix()->format() }}</td>
                                
                                @if ($user->actual_plan)
                                
                                    <td>{{ $user->actual_plan->plan->plan ?? 'No aplica' }}</td>
                                
                                    @if ($user->actual_plan->finish_date >= (Carbon\Carbon::today()))
                                        <td>{{ 'Quedan ' }}{{ $user->actual_plan->finish_date->diffInDays(Carbon\Carbon::now()) }}{{ ' días' }}
                                        </td>
                                    @else
                                        <td>{{ '--' }}</td>
                                    @endif
                                <td>{{ $user->actual_plan->start_date->format('d-m-Y') }} a {{ $user->actual_plan->finish_date->format('d-m-Y') }}</td>
                                @else
                                    <td>{{ 'Sin plan' }}</td>
                                    
                                    <td>{{ 'No aplica' }}</td>
                                    
                                    <td>{{ 'No aplica' }}</td>
                                @endif
                                <td>
                                    <a href="{{url('/users/'.$user->id)}}" class="btn btn-info btn-icon-only btn-success"><i class="la la-eye"></i></a>
                                    <a href="" class="btn btn-info btn-icon-only btn-message"><i class="la la-envelope"></i></a>
                                    <a href="" class="btn btn-info btn-icon-only btn-pay"><i class="la la-usd"></i></a>
                                </td>
                                <td>{{ $user->status_user_id }}</td>
                            </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection


@section('css') {{-- stylesheet para esta vista --}}
	<link href="{{asset('css/datatables.min.css')}}" rel="stylesheet" />
@endsection



@section('scripts') {{-- scripts para esta vista --}}
	{{--  datatable --}}
	<script src="{{ asset('js/datatables.min.js') }}"></script>

    <script src="{{ asset('js/moment.min.js') }}"></script>

	<script >
		$(document).ready(function() {
			table = $('#students-table').DataTable({
                "ajax": {
                    "url": "<?= route('users-json') ?>",
                    "dataType": "json",
                    "type": "GET",
                },
				"paging": true,
                "processing": true,
				"ordering": true,
                "order": [[ 3, "asc" ]],
				"language": {
                    "processing": "Cargando...",
					"lengthMenu": "Mostrar _MENU_ elementos",
					"zeroRecords": "Sin resultados",
					"info": "Mostrando página _PAGE_ de _PAGES_",
					"infoEmpty": "Sin resultados",
					"infoFiltered": "(filtrado de _MAX_ registros totales)",
					"search": "Filtrar:"
				},
                "columns":[
                    { "data": "full_name",
                      "render": function (data, other, row) {
                            return '<div class="img-avatar div-user-avatar" style="background-image: url('+ row.avatar +')" data-image="'+ row.avatar +'"></div>'+
                                   '<a href="/users/'+ row.id +'">'+
                                   data + '</a>';
                      }
                    },
                    { "data": "email" },
                    { "data": "rut_formated" },
                    {  "data": "actual_plan",
                        "render": function (data, other, row) {
                            return data && data.plan ? data.plan.plan : 'no aplica';
                        },
                    },
                    { "data": "actual_plan",
                        "render": function (data, other, row) {
                            return data && data.plan ? moment(data.finish_date).format("DD-MM-YYYY") : 'no aplica';
                        },
                    },
                    { "data": "actual_plan",
                        "render": function (data, other, row) {
                            return data && data.plan ?
                                   moment(data.start_date).format("DD-MM-YYYY") +' al '+ moment(data.finish_date).format("DD-MM-YYYY") :
                                   'no aplica';
                        }
                    }, 
                    { "data": "actions",
                        "render": function (data, other, row) {
                            return '<a href="/users/'+ row.id +'" class="btn btn-info btn-icon-only btn-success"><i class="la la-eye"></i></a>';
                        }
                    },
                    { "data": "status_user_id",
                      "render": function ( data, other, row ) {
                           return data;
                      }
                    }
                ],
                "columnDefs": [
                    {
                        "targets": [ 7 ],
                        "visible": false,
                        "searchable": true
                    }
                ],
                "drawCallback": function( settings ) {
                    $(".div-user-avatar").click(function () {
                        $('#avatar-img').attr('src', $(this).data('image'));

                        $('#modal-avatar').modal('show');
                    });
                }
			});
		});


  $('button.user-filter').on("click", function(){
      table.columns( 7 ).search( $(this).data('status') ).draw();
    });

	</script>
	{{--  End datatable --}}

{{--     <script>

    </script> --}}
@endsection
