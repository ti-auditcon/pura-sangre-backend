@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox">
          <div class="ibox-head">
              <div class="ibox-title">CLASE</div>

          </div>
          <div class="ibox-body">

          </div>
      </div>
      <div class="ibox">
          <div class="ibox-head">
              <div class="ibox-title">WOD</div>

          </div>
          <div class="ibox-body">
            <label>bla</label>
            <textarea class="form-control"></textarea>
            <label>bla</label>
            <textarea class="form-control"></textarea>
            <label>bla</label>
            <textarea class="form-control"></textarea>

          </div>
      </div>
    </div>
    <div class="col-6">
      <div class="ibox">
          <div class="ibox-head">
              <div class="ibox-title">Alumnos</div>
              <div class="ibox-tools">
                  <a class="dropdown-toggle" data-toggle="dropdown"><i class="ti-user"></i></a>
                  <div class="dropdown-menu dropdown-menu-right">
                      <a class="dropdown-item"><i class="ti-pencil mr-2"></i>Create</a>
                      <a class="dropdown-item"><i class="ti-pencil-alt mr-2"></i>Edit</a>
                      <a class="dropdown-item"><i class="ti-close mr-2"></i>Remove</a>
                  </div>
              </div>
          </div>
          <div class="ibox-body">
              <div class="" >
                <ul class="media-list media-list-divider mr-2 scroller" data-height="580px" style="overflow: hidden; width: auto; height: 580px;">
                  @foreach (App\models\student::all()->take(15) as $student)
                    <li class="media align-items-center">
                        <a class="media-img" href="javascript:;">
                            <img class="img-circle" src="{{url('/img/users/'.$student->avatar)}}" alt="image" width="54">
                        </a>
                        <div class="media-body d-flex align-items-center">
                            <div class="flex-1">
                                <div class="media-heading">{{$student->fist_name}} {{$student->last_name}} <span class="badge badge-success badge-pill ml-2">Confirmado</span></div>
                                <small class="text-muted">{{$student->email}} </small></div>
                            <button class="btn btn-sm btn-outline-secondary btn-rounded">remover</button>
                        </div>
                    </li>
                  @endforeach

              </ul>
          </div>
      </div>

    </div>

  </div>



@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection



@section('scripts') {{-- scripts para esta vista --}}

@endsection
