@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'payments'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="ibox form-control-air">
        <div class="ibox-head">
          <div class="ibox-title">Pagos</div>
        </div>
        <div class="ibox-body">
          <div class="ibox-fullwidth-block">
            <table id="students-table" class="table table-hover">
              <thead class="thead-default thead-lg">
                <tr>
                  <th width="20%">Alumno</th>
                  <th width="20%">Plan pagado</th>
                  <th width="10%">Fecha pago</th>
                  <th width="10%">Inicio</th>
                  <th width="10%">Termino</th>
                  <th width="10%">Forma de pago</th>
                  <th width="10%">Monto</th>
                  <th width="10%">Acciones</th>

                </tr>
              </thead>
              <tbody>
                @foreach ($userPlans as $up)
                <tr>
                  <td>
                    {{$up->user->first_name}} {{$up->user->last_name}}
                  </td>
                  <td>
                    {{$up->plan->plan}}
                  </td>
                  <td>
                    {{$up->bill->date ?? "no aplica"}}
                  </td>
                  <td>
                    {{$up->start_date ?? "----"}}
                  </td>
                  <td>
                    {{$up->finish_date ?? "----" }}
                  </td>
                  <td>
                    {{$up->bill->payment_type->payment_type ?? "no aplica"}}
                  </td>
                  <td>
                    {{$up->bill->amount ?? "no aplica"}}
                  </td>
                  <td>

                  </td>

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
