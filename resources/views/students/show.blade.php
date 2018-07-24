@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="ibox flex-1">
      <div class="ibox-body">
          <div class="flexbox">
              <div class="flexbox-b">
                  <div class="ml-5 mr-5">
                      <img class="img-circle" src="{{url('/img/users/'.$student->avatar)}}" alt="image" width="110">
                  </div>
                  <div>
                      <h4>{{$student->first_name}} {{$student->last_name}}</h4>
                      <div class="text-muted font-13 mb-3">
                          <span class="mr-3"><i class="mr-2"></i>PLAN FULL</span>
                          <span class="badge badge-success badge-pills">ACTIVO</span>

                      </div>

                  </div>
              </div>
              <div class="d-inline-flex">
                  <div class="px-4 text-center">
                      <div class="text-muted font-13">Clases asistidas</div>
                      <div class="h2 mt-2">134</div>
                  </div>
                  <div class="px-4 text-center">
                      <div class="text-muted font-13">Clases disponibles</div>
                      <div class="h2 mt-2 text-warning">7</div>
                  </div>
              </div>
          </div>
      </div>

  </div>
  <div class="row justify-content-center">
    <div class="col-4">
      <div class="ibox">
          <div class="ibox-head">
              <div class="ibox-title">DETALLES</div>
              <div class="ibox-tools">
                    <a class="btn btn-success text-white">Editar</a>
                </div>
          </div>
          <div class="ibox-body">

            <div class="card mb-4">
                <div class="card-body ">
                  <div class="row mb-2">
                      <div class="col-12 text-muted">RUT:</div>
                      <div class="col-12">{{$student->rut}}</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-12 text-muted">EMAIL:</div>
                      <div class="col-12">{{$student->email}}</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-12 text-muted">Fecha de nacimiento:</div>
                      <div class="col-12">22-07-1985</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-12 text-muted">Direcccion:</div>
                      <div class="col-12">bla bla bla</div>
                  </div>
                </div>

              </div>
          </div>
      </div>
      <div class="ibox">
          <div class="ibox-head">
              <div class="ibox-title">Planes</div>
              <div class="ibox-tools">
                    <a class="btn btn-success text-white">Nuevo Plan</a>
                </div>
          </div>
          <div class="ibox-body">
            <table id="plans-table" class="table table-hover">
                <thead class="thead-default thead-lg">
                    <tr>

                        <th width="30%">Plan</th>
                        <th width="30%">Ultimo pago</th>
                        {{-- <th width="20%">ultimo pago</th> --}}
                        <th width="20%"></th>
                    </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>PLAN FULL</td>
                    <td>05-07-2018</td>
                    <td><span class="badge badge-success">activo</span></td>
                  </tr>
                  <tr>
                    <td>PLAN AM</td>
                    <td>05-05-2018</td>
                    <td><span class="badge badge-danger">inactivo</span></td>
                  </tr>
                </tbody>
            </table>
          </div>
      </div>
    </div>
    <div class="col-8">
      <div class="ibox ibox-fullheight">
            <div class="ibox-head">
                <div class="ibox-title">Pagos</div>
                <div class="ibox-tools">
                      <button class="btn btn-success">Realizar pago</button>

                  </div>
            </div>
            <div class="ibox-body">
              <div class="flexbox mb-4">
                  <div class="flexbox">
                      <span class="flexbox mr-3">
                          <span class="mr-2 text-muted">Dia de pago</span>
                          <span class="h3 mb-0 text-primary font-strong">08</span>
                      </span>
                      <span class="flexbox mr-3">
                          <span class="mr-2 text-muted">Dias disponibles</span>
                          <span class="h3 mb-0 text-primary font-strong">9</span>
                      </span>
                  </div>
              </div>
                <div class="ibox-fullwidth-block">
                    <table id="students-table" class="table table-hover">
                        <thead class="thead-default thead-lg">
                            <tr>

                                <th width="20%">Plan</th>
                                <th width="30%">Periodo</th>
                                <th width="15%">total</th>
                                <th width="20%">Medio de pago</th>
                                <th width="15%" >Día de pago</th>
                            </tr>
                        </thead>
                        <tbody>
                          <tr>
                              <td >Plan Full</td>
                              <td >08-07-2018 al 08-08-2018</td>
                              <td >$30.000</td>
                              <td >Transferencia</td>
                              <td  >05-07-2018</td>
                          </tr>
                          <tr>
                              <td >Plan Full</td>
                              <td >08-06-2018 al 08-07-2018</td>
                              <td >$30.000</td>
                              <td >Transferencia</td>
                              <td  >05-06-2018</td>
                          </tr>
                          <tr>
                              <td >Plan AM</td>
                              <td >08-05-2018 al 08-06-2018</td>
                              <td >$20.000</td>
                              <td >Transferencia</td>
                              <td  >05-05-2018</td>
                          </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


  </div>
@endsection


@section('css') {{-- stylesheet para esta vista --}}
@endsection



@section('scripts') {{-- scripts para esta vista --}}
@endsection
