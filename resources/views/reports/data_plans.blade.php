@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-xl-5">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Total de Ingresos de todos los planes</div>
                <div class="iboox-tools">
                    <div class="inline-items">
                        <select class="form-control">
                            @for ($i = 1; $i <= 31; $i++)
                                <option>{{ $i }}</option>
                            @endfor
                        </select>
                        
                        <button class="btn btn-success">Buscar</button>
                    </div>
                </div>
            </div>

            <div class="ibox-body">
                <div class="row">
                    <div class="col-3 mx-auto">
                        <h3 class="font-strong text-success">$ 135.600</h3>
                        <div class="text-muted">34 Planes Vendidos</div>
                    </div>

                    <div class="col-3 mx-auto">
                        <h3 class="font-strong text-secondary">$ 130.500</h3>
                        <div class="text-muted">30 Planes Vendidos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection



@section('scripts') {{-- scripts para esta vista --}}

<script src="{{ asset('js/datatables.min.js') }}"></script>

<script src="{{ asset('js/moment.min.js') }}"></script>

<script src="{{ asset('js/Chart.min.js') }}"></script>

<script>
var urltwo = "{{ url('report/secondchart') }}";

$(document).ready(function() {
    var Months =  new Array();
    var Quantities = new Array();
    var SubQuantities = new Array();
    
    $.get(urltwo, function(respuesta) {
        respuesta.q_anual.forEach(function(data) {
            Quantities.push(data);
        });
        
        respuesta.q_sub_anual.forEach(function( data ) {
            SubQuantities.push(data);
        });

        respuesta.months.forEach(function( data ) {
            Months.push(data);
        });
        
        var chartdata = {
            labels: Months,
            datasets: [
                { label: '2019', borderWidth: 1, borderColor: 'rgba(54, 162, 235, 1)',
                  backgroundColor: 'rgba(54, 162, 235, 1)', data: Quantities,
                },
                { label: '2018', borderWidth: 1, borderColor: 'rgba(180, 178, 180, 0.8)',
                  backgroundColor: 'rgba(180, 178, 180, 0.8)', data: SubQuantities, }
            ]
        };
        var chart_quantity = document.getElementById("quantity-plans").getContext('2d');
        
        var miChart = new Chart(chart_quantity, { type: 'bar', data: chartdata });
    });
});
</script>

@endsection