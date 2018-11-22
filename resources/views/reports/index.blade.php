@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'reports'])
@endsection

@section('content')
  <div class="row">
      <div class="col-md-6">
        <div class="ibox">
          <div class="ibox-head">
            <div class="ibox-title">Acomulado</div>
          </div>
          <div class="ibox-body">
              <div>
                  <canvas id="acumulative" ></canvas>
              </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
          <div class="ibox">
              <div class="ibox-head">
                  <div class="ibox-title">Total ingresos</div>
                  {{--  {{dd()}} --}}
              </div>
              <div class="ibox-body">
                  <div>
                      <canvas id="total_income" ></canvas>
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

  //////////////////total summary
  ///////////////////////////////
  var barData = {
      labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "septiembre", "Octubre", "Noviembre", "Diciembre"],
      datasets: [
          {
              label: "2017",
              backgroundColor:'#DADDE0', //'rgba(220, 220, 220, 0.5)',
              data:
              [{!!$summaries->where('month',1)->where('year',date("Y",strtotime("-1 year")))->sum('amount'); !!},
              {!!$summaries->where('month',2)->where('year',date("Y",strtotime("-1 year")))->sum('amount'); !!},
              {!!$summaries->where('month',3)->where('year',date("Y",strtotime("-1 year")))->sum('amount'); !!},
              {!!$summaries->where('month',4)->where('year',date("Y",strtotime("-1 year")))->sum('amount'); !!},
              {!!$summaries->where('month',5)->where('year',date("Y",strtotime("-1 year")))->sum('amount'); !!},
              {!!$summaries->where('month',6)->where('year',date("Y",strtotime("-1 year")))->sum('amount'); !!},
              {!!$summaries->where('month',7)->where('year',date("Y",strtotime("-1 year")))->sum('amount'); !!},
              {!!$summaries->where('month',8)->where('year',date("Y",strtotime("-1 year")))->sum('amount'); !!},
              {!!$summaries->where('month',9)->where('year',date("Y",strtotime("-1 year")))->sum('amount'); !!},
              {!!$summaries->where('month',10)->where('year',date("Y",strtotime("-1 year")))->sum('amount'); !!},
              {!!$summaries->where('month',11)->where('year',date("Y",strtotime("-1 year")))->sum('amount'); !!},
              {!!$summaries->where('month',12)->where('year',date("Y",strtotime("-1 year")))->sum('amount'); !!}]
          },
          {
              label: "2018",
              //backgroundColor:'#84cac6',// 'rgba(26,179,148,0.5)',
              backgroundColor: '#18C5A9', // '#30C8B3'
              borderColor: "#fff",
              data: [{!!$summaries->where('month',1)->where('year',date("Y"))->sum('amount'); !!},
                    {!!$summaries->where('month',2)->where('year',date("Y"))->sum('amount'); !!},
                    {!!$summaries->where('month',3)->where('year',date("Y"))->sum('amount'); !!},
                    {!!$summaries->where('month',4)->where('year',date("Y"))->sum('amount'); !!},
                    {!!$summaries->where('month',5)->where('year',date("Y"))->sum('amount'); !!},
                    {!!$summaries->where('month',6)->where('year',date("Y"))->sum('amount'); !!},
                    {!!$summaries->where('month',7)->where('year',date("Y"))->sum('amount'); !!},
                    {!!$summaries->where('month',8)->where('year',date("Y"))->sum('amount'); !!},
                    {!!$summaries->where('month',9)->where('year',date("Y"))->sum('amount'); !!},
                    {!!$summaries->where('month',10)->where('year',date("Y"))->sum('amount'); !!},
                    {!!$summaries->where('month',11)->where('year',date("Y"))->sum('amount'); !!},
                    {!!$summaries->where('month',12)->where('year',date("Y"))->sum('amount'); !!}]
          }
      ]
  };
  var barOptions = {
      responsive: true,


  };

  var ctx = document.getElementById("total_income").getContext("2d");
  new Chart(ctx, {type: 'bar', data: barData, options:barOptions});

  //////////////////END total summary
  ///////////////////////////////


  //////////////////Acomulativo
  ///////////////////////////////



  var options_acumulative = {
      responsive: true,
      maintainAspectRatio: false,

  };

  var ctx = document.getElementById("acumulative").getContext("2d");
  new Chart(ctx, {type: 'line', data: data_acomulative, options:options_acumulative});
});

</script>

@endsection
