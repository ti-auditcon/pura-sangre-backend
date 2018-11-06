@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'payments'])
@endsection

@section('content')
  <div class="row">
      <div class="col-md-6">
          <div class="ibox">
              <div class="ibox-head">
                  <div class="ibox-title">Reporte de este mes</div>
              </div>
              <div class="ibox-body">
                  <div>
                      <canvas id="line_chart" style="height:200px;"></canvas>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-md-6">
          <div class="ibox">
              <div class="ibox-head">
                  <div class="ibox-title">Activos</div>
              </div>
              <div class="ibox-body">
                  <div>
                      <canvas id="bar_chart" style="height:200px;"></canvas>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection



@section('scripts') {{-- scripts para esta vista --}}
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script>

  // Bar Chart example
$(document).ready(function() {
  var barData = {
      labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "septiembre", "Octubre", "Noviembre", "Diciembre"],
      datasets: [
          {
              label: "2017",
              backgroundColor:'#DADDE0', //'rgba(220, 220, 220, 0.5)',
              data: [45, 80, 58, 74, 54, 59, 40, 54, 59, 40, 31, 85]
          },
          {
              label: "2018",
              //backgroundColor:'#84cac6',// 'rgba(26,179,148,0.5)',
              backgroundColor: '#18C5A9', // '#30C8B3'
              borderColor: "#fff",
              data: [29, 48, 40, 19, 78, 31, 85, 48, 40, 19, 78, 48]
          }
      ]
  };
  var barOptions = {
      responsive: true,
      maintainAspectRatio: false
  };

  var ctx = document.getElementById("bar_chart").getContext("2d");
  new Chart(ctx, {type: 'bar', data: barData, options:barOptions});
});
</script>
@endsection
