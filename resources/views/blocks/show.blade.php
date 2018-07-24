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
            <div class="row mb-4">
                <div class="col-lg-6 col-md-6">
                    <div class="card mb-4">
                        <div class="card-body ">
                          <div class="row mb-2">
                              <div class="col-12 text-muted">Fecha:</div>
                              <div class="col-12">23-12-2018</div>
                          </div>
                          <div class="row mb-2">
                              <div class="col-12 text-muted">Horario:</div>
                              <div class="col-12">09:00-10:00</div>
                          </div>
                          <div class="row mb-2">
                              <div class="col-12 text-muted">Coach:</div>
                              <div class="col-12">Max Maximo</div>
                          </div>
                          <br />


                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="card mb-4">
                        <div class="card-body flexbox-b">
                            <div class="row ">
                              <div class="easypie mr-4" data-percent="42" data-bar-color="#5c6bc0" data-size="80" data-line-width="8">
                                  <span class="easypie-data font-26 text-primary"><i class="ti-user"></i></span>
                              </div>

                              <h3 class="font-strong text-primary">15/25</h3>
                              <div class="text-muted">Cupos confirmados</div>
                            </div>
                            <div class="row ">
                              <button type="button" name="button" class="btn btn-danger">Deshabilitar clase</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
          </div>
      </div>
      <div class="ibox">
          <div class="ibox-head">
              <div class="ibox-title">WOD</div>
              <div class="ibox-tools">
                  <a ><i class="ti-pencil"></i></a>

              </div>

          </div>
          <div class="ibox-body">
            <div class="row">
              <div class="col-md-4">
                    <div class="ibox shadow-wide">
                        <div class="ibox-body text-center">
                            <h3 class="font-strong">Warm up</h3>

                            <div class="py-5">
                                <div class="flexbox-b mb-3"><i class="ti-check mr-3 font-18"></i> 5 HS Push Ups</div>
                                <div class="flexbox-b mb-3"><i class="ti-check mr-3 font-18"></i> 15 Pull Ups</div>
                                <div class="flexbox-b mb-3"><i class="ti-check mr-3 font-18"></i> 25 Push Ups</div>
                                <div class="flexbox-b mb-3"><i class="ti-check mr-3 font-18"></i> 25 Push Ups</div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                      <div class="ibox shadow-wide">
                          <div class="ibox-body text-center">
                              <h3 class="font-strong">Skills</h3>

                              <div class="py-5">
                                  <div class="flexbox-b mb-3"><i class="ti-check mr-3 font-18"></i> 5 HS Push Ups</div>
                                  <div class="flexbox-b mb-3"><i class="ti-check mr-3 font-18"></i> 15 Pull Ups</div>
                                  <div class="flexbox-b mb-3"><i class="ti-check mr-3 font-18"></i> 25 Push Ups</div>
                                  <div class="flexbox-b mb-3"><i class="ti-check mr-3 font-18"></i> 25 Push Ups</div>

                              </div>

                          </div>
                      </div>
                  </div>
                  <div class="col-md-4">
                        <div class="ibox shadow-wide">
                            <div class="ibox-body text-center">
                                <h3 class="font-strong">Wod</h3>

                                <div class="py-5">
                                    <div class="flexbox-b mb-3"><i class="ti-check mr-3 font-18"></i> 5 HS Push Ups</div>
                                    <div class="flexbox-b mb-3"><i class="ti-check mr-3 font-18"></i> 15 Pull Ups</div>
                                    <div class="flexbox-b mb-3"><i class="ti-check mr-3 font-18"></i> 25 Push Ups</div>
                                    <div class="flexbox-b mb-3"><i class="ti-check mr-3 font-18"></i> 25 Push Ups</div>

                                </div>

                            </div>
                        </div>
                    </div>
            </div>




          </div>
      </div>
    </div>
    <div class="col-6">
      {{-- <div class="ibox">
          <div class="ibox-head">
              <div class="ibox-title">Agregar alumno</div>
          </div>
          <div class="ibox-body">
            <div class="form-group m-4">

                <input class="form-control" type="text" id="addStudent" placeholder="Estudiantes">
                </br>
                    <span class="input-group-btn">
                                              <button class="btn btn-outline-secondary">Go!</button>
                    </span>

            </div>
          </div>
      </div> --}}
      <div class="ibox">
          <div class="ibox-head">
              <div class="ibox-title">Alumnos </div>
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
            <div class="form-group m-4">

                <input class="form-control" type="text" id="addStudent" placeholder="Estudiantes">
                </br>
                    <span class="input-group-btn">
                                              <button class="btn btn-outline-secondary">Agregar a la clase</button>
                    </span>

            </div>
              <div class="" >
                <ul class="media-list media-list-divider mr-2 scroller" data-height="580px" style="overflow: hidden; width: auto; height: 580px;">
                  @foreach (App\models\student::all()->take(15) as $student)
                    <li class="media align-items-center">

                        <a class="media-img" href="{{url('students/1')}}">
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
  <script src="{{ asset('js/typeahead.bundle.min.js') }}"></script>

  <script>
  $(document).ready(function() {
    // Basic demo

    var substringMatcher = function(strs) {
      return function findMatches(q, cb) {
        var matches, substringRegex;

        // an array that will be populated with substring matches
        matches = [];

        // regex used to determine if a string contains the substring `q`
        substrRegex = new RegExp(q, 'i');

        // iterate through the pool of strings and for any string that
        // contains the substring `q`, add it to the `matches` array
        $.each(strs, function(i, str) {
          if (substrRegex.test(str)) {
            matches.push(str);
          }
        });

        cb(matches);
      };
    };

    var states = {!!$student->get(['id', 'first_name', 'last_name'])->toJson()!!};

    $('#addStudent2').typeahead({
      hint: true,
      highlight: true,
      minLength: 1
    },
    {
      name: 'states',
      source: substringMatcher(states),
      templates: {
        suggestion: function (data) {
             return '<a  class="list-group-item">'+data.first_name+'</a>'
        }
      }
    });

  });
  </script>


@endsection
