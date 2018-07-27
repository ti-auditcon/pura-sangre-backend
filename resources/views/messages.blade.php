@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'messages'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox">
          <div class="ibox-head">
              <div class="ibox-title">Mensaje</div>

          </div>
          <div class="ibox-body" >

                <div id="summernote" data-plugin="summernote" data-air-mode="true">
                    <h2>WYSIWYG Editor</h2> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ullamcorper sapien non nisl facilisis bibendum in quis tellus. Duis in urna bibendum turpis pretium fringilla. Aenean neque velit, porta eget
                    mattis ac, imperdiet quis nisi. Donec non dui et tortor vulputate luctus. Praesent consequat rhoncus velit, ut molestie arcu venenatis sodales.
                    <h4>Lacinia</h4>
                    <ul>
                        <li>Suspendisse tincidunt urna ut velit ullamcorper fermentum.</li>
                        <li>Nullam mattis sodales lacus, in gravida sem auctor at.</li>
                        <li>Praesent non lacinia mi.</li>
                        <li>Mauris a ante neque.</li>
                        <li>Aenean ut magna lobortis nunc feugiat sagittis.</li>
                    </ul>


                  </div>

          </div>
        </div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Plantillas</div>
            </div>
            <div class="ibox-body">

            </div>
        </div>
      </div>
      <div class="col-6">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Remitentes</div>
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
                  <ul class="media-list media-list-divider mr-2 scroller" data-height="580px">
                    @foreach (App\models\student::all()->take(15) as $student)
                      <li class="media align-items-center ">

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
<link href="{{asset('css/summernote.css')}}" rel="stylesheet" />
@endsection



@section('scripts') {{-- scripts para esta vista --}}
<script src="{{ asset('js/summernote.min.js') }}"></script>
<script>
    $(function() {
        $('#summernote').summernote();
        // $('#summernote_air').summernote({
        //     airMode: true
        // });
    });
</script>
@endsection
