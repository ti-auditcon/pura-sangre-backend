var Months =  new Array();
var incomes = [];
var incomes_sub = []

$.ajax({
    type: 'GET',
    url: 'reports/firstchart',
    success: function (response) {
        for (var i = 0; i < 12; i++) {
            
        }
        response.annual.forEach(function (data) {
            incomes.push(data);
        });
        response.months.forEach(function (data) {
            Months.push(data);
        });
        response.sub_annual.forEach(function (data) {
            incomes_sub.push(data);
        });

        var barChartData = {
        labels: Months,
        datasets: [
                {
                    label: '2019',
                    borderWidth: 3,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 1)',
                    data: incomes,
                }, 
                {
                    label: '2018',
                    borderWidth: 3,
                    borderColor: 'rgba(54, 162, 235, 0.6)',
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    data: incomes_sub,
                    borderDash: [6],
                    borderDashOffset: 5,
                }
            ]
        };

        var ctx = document.getElementById("all_plans_incomes").getContext('2d');
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: barChartData,
            options: {
                elements: {
                    line: { 
                        fill: false,
                        tension: 0,
                        backgroundColor: 'rgb(54, 162, 235, 0.2)',
                        borderColor: 'rgb(54, 162, 235, 0.7)', 
                    },
                    point: { 
                        radius: 0,
                        hitRadius: 30,
                        borderColor: 'rgb(54, 162, 235, 0.9)',
                        backgroundColor: 'rgb(54, 162, 235, 0.7)'
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: false
                    }],
                    yAxes: [{ 
                        ticks: {
                            // Include a dollar sign in the ticks
                            callback: function(value, index, values) {
                                return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }]
                },
                tooltips: {
                    mode: 'index',
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';
                            var label = tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            return datasetLabel + ': ' + label;
                        },
                        // Use the footer callback to display the sum of the items showing in the tooltip
                        beforeFooter: function(tooltipItems, data) {
                            var sum = 0;

                            tooltipItems.forEach(function(tooltipItem) {
                                if (sum == 0){
                                    sum += tooltipItem.yLabel;
                                } else {
                                    sum -= tooltipItem.yLabel;
                                }
                            });
                            
                            return 'Diferencia: ' + sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        },
                        footer: function( tooltipItem, data ) {
                            var dif = 0;
                            tasa = ((tooltipItem[0]['yLabel'] - tooltipItem[1]['yLabel'])*100)/tooltipItem[1]['yLabel'];
                            if (tasa == 'Infinity') {
                               tasa = 'Infinito';
                               return 'Tasa de crecimiento: '+ tasa;
                            } 
                            return 'Tasa de crecimiento: '+ tasa.toFixed(1) + '%';
                            
                            // valor de el mes 2019 (ej: 200000)
                            // console.log(tooltipItem[0]['yLabel']);
                            // DIFERENCIA ENTRE LOS VALORES DE 2019 Y 2018
                            // console.log(tooltipItem[0]['yLabel'] - tooltipItem[1]['yLabel']);
                            //LARGO DEL ARREGLO
                            // console.log(data.datasets[1]['data']['length']);
                            //AÑO DEL ARRAY (EJ: 2019)
                            // console.log(data.datasets[0]['label']);
                        },
                    },
                }
            },
        });
    }
});
