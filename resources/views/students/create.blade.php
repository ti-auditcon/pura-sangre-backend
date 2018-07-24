@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox">
        <div class="ibox-body">
hola
        </div>

      </div>
    </div>

  </div>



@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection



@section('scripts') {{-- scripts para esta vista --}}


@endsection
