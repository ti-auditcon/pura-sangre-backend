@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'payments'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox form-control-air">
        <div class="ibox-head">
          <div class="ibox-title">
            Pagos
          </div>
          <div class="ibox-body">
            <div class="ibox-fullwidth-block">
              <table id="students-table" class="table table-hover">
                <thead class="thead-default thead-lg">
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
                  @foreach ($bills as $bill)
                  <tr>
                      <td>{{$bill->plan_user->user->first_name}} {{$bill->plan_user->user->last_name}}</td>
                      <td>{{$bill->plan_user->plan->plan}}</td>
                      <td>{{Carbon\Carbon::parse($bill->date)->format('d-m-Y')}}</td>
                      <td>{{Carbon\Carbon::parse($bill->start_date)->format('d-m-Y')}}</td>
                      <td>{{Carbon\Carbon::parse($bill->finish_date)->format('d-m-Y')}}</td>
                      <td>{{$bill->amount}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


@endsection
