@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'payments'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox form-control-air">
        <div class="ibox-head">
          <div class="ibox-title">pagos</div>
        </div>
        <div class="ibox-body">
        </div>
      </div>
    </div>
  </div>


@endsection
