@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'reports'])
@endsection

@section('content')

  <div class="row justify-content-center">
    <div class="col-10">
      <div class="ibox">
        <div class="ibox-head">
          <div class="ibox-title">
            Pagos
          </div>
        </div>
        <div class="ibox-body pagos-body">
          <div class="table-responsive">
            <table id="inactives-table" class="table table-hover">
              <thead class="thead-default">
                <tr>
                  <th width="30%">Alumno</th>
                  <th width="30%">Plan</th>
                  <th width="20%">Fecha de término del plan</th>
                  <th width="20%">N° teléfono</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($inactive_users as $plan)
                <tr>
                   <td><a href="{{url('/users/'.$plan->user->id)}}">{{$plan->user->first_name}} {{$plan->user->last_name}}</a></td>
                   <td>{{$plan->plan->plan}}</td>
                   <td>{{Date::parse($plan->finish_date)->format('d-m-Y')}}</td>
                   <td>{{'+56 9 '.$plan->user->phone}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts') {{-- scripts para esta vista --}}
  {{--  datatable --}}
  <script src="{{ asset('js/datatables.min.js') }}"></script>
  <script>

      $('#inactives-table').DataTable({
         "language": {
            "lengthMenu": "Mostrar _MENU_ elementos",
            "zeroRecords": "Sin resultados",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Sin resultados",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Filtrar:",
            "paginate": {
               "first":      "Primero",
               "last":       "último",
               "next":       "Siguiente",
               "previous":   "Anterior"
            },
         },
      } );
   </script> 
  {{--  End datatable --}}

@endsection
