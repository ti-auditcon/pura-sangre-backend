@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'payments'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="ibox">
        <div class="ibox-head">
          <div class="ibox-title">
            Pagos
          </div>
        </div>
        <div class="ibox-body pagos-body">
          <div class="table-responsive">
            <table id="payments-table" class="table table-hover">
              <thead class="thead-default">
                <tr>
                  <th width="20%">Usuario</th>
                  <th width="15%">Plan</th>
                  <th width="15%">Fecha de Pago</th>
                  <th width="15%">Fecha de Inicio</th>
                  <th width="15%">Fecha de Termino</th>
                  <th width="10%">Total</th>
                </tr>
              </thead>
              <tbody>
               {{--  @foreach ($bills->sortByDesc('date') as $bill)
                <tr>
                  <td><a href="{{url('/users/'.$bill->plan_user->user->id)}}">
                    {{$bill->plan_user->user->first_name}} {{$bill->plan_user->user->last_name}}</a>
                  </td>
                  <td>{{$bill->plan_user->plan->plan}}</td>
                  <td>{{Carbon\Carbon::parse($bill->date)->format('d-m-Y')}}</td>
                  <td>{{Carbon\Carbon::parse($bill->start_date)->format('d-m-Y')}}</td>
                  <td>{{Carbon\Carbon::parse($bill->finish_date)->format('d-m-Y')}}</td>
                  <td>{{'$ '.number_format($bill->amount, $decimal = 0, '.', '.') ?? "no aplica"}}</td>
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

@section('scripts') {{-- scripts para esta vista --}}
  {{--  datatable --}}
  <script src="{{ asset('js/datatables.min.js') }}"></script>
  <script>

      $(document).ready(function() {
         $('#payments-table').DataTable( {
            "paging": true,
            "ordering": true,
            "order": [[ 3, "asc" ]],
            "language": {
               "loadingRecords": "Cargando datos...",
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
            "ajax": {
                "url": "/bills",
                "type": "GET"
            },
            "columns": [
                { "data": "alumno" },
                { "data": "plan" },
                { "data": "fecha_boleta" },
                { "data": "fecha_de_inicio" },
                { "data": "fecha_de_termino" },
                { "data": "total" }
            ]
         });
      });
   </script> 
  {{--  End datatable --}}


<script>

   // $(document).ready(function() {
   //    var pushItemsToList = function(bills) {
   //       var tbody = $('#payments-table tbody'),
   //          props = ["alumno", "plan", "fecha_boleta", "fecha_de_inicio", "fecha_de_termino", "total"];
   //       $.each(bills, function(i, bill) {
   //           var tr = $('<tr>');
   //          $.each(props, function(i, prop) {
   //             $('<td>').html(bill[prop]).appendTo(tr);  
   //          });
   //          tbody.append(tr);
   //       });
   //    }

   //    var data = [];
   //    $.ajax({
   //       type:'GET',
   //       url: '/bills',
   //       success: function(bills){
   //          pushItemsToList(bills);
   //       },
   //       error: function(){
   //          console.log("Hay al menos un error");
   //       }
   //    });
   // });

</script>


@endsection
