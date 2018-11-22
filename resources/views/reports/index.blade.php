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

  //////////////////TOTAL SUMMARY
  /////////////////////////////////////////////////////////////////////
  var barData = {
      labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "septiembre", "Octubre", "Noviembre", "Diciembre"],
      datasets: [
          {
              label: "{!!(string)date("Y",strtotime("-1 year"))!!}",
              backgroundColor:'#DADDE0',
              data:
              [
                @for($i = 1; $i <= 12; $i++)
                {!!$summaries->where('month',$i)->where('year',date("Y",strtotime("-1 year")))->sum('amount'); !!},
                @endfor
              ]
          },
          {
              label: "{!!(string)date("Y")!!}",
              backgroundColor: '#18C5A9',
              borderColor: "#fff",
              data:
              [
                @for($i = 1; $i <= 12; $i++)
                {!!$summaries->where('month',$i)->where('year',date("Y"))->sum('amount'); !!},
                @endfor
              ]
          }
      ]
  };
  var barOptions = {
      responsive: true,
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';
            var label = tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            return datasetLabel + ': ' + label;
          }
        }
      },
      scales: {
            yAxes: [{
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            }]
        }


  };

  var ctx = document.getElementById("total_income").getContext("2d");
  new Chart(ctx, {type: 'bar', data: barData, options:barOptions});

  //////////////////END TOTAL SUMMARY
  ///////////////////////////////


  //////////////////ACOMULATIVO
  ////////////////////////////////////////////////////////////////////////////



  var options_acumulative = {
      responsive: true,
      maintainAspectRatio: false,

  };

  var ctx = document.getElementById("acumulative").getContext("2d");
  new Chart(ctx, {type: 'line', data: data_acomulative, options:options_acumulative});
});

</script>

@endsection
