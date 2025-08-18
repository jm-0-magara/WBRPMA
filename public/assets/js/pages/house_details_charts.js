document.addEventListener("DOMContentLoaded", function() {
    // Payment Records Chart
    if (document.getElementById("payment-records-chart")) {
        var optionsPayment = {
            chart: {
                type: 'scatter',
                height: 350,
                toolbar: { show: false }
            },
            series: [{
                name: 'Payments',
                data: paymentRecordsChartData
            }],
            xaxis: {
                type: 'datetime',
                title: { text: 'Date Paid' },
                labels: {
                    formatter: function(val) {
                        return new Date(val).toLocaleDateString();
                    }
                }
            },
            yaxis: {
                title: { text: 'Amount (Ksh.)' }
            },
            tooltip: {
                custom: function({ series, seriesIndex, dataPointIndex, w }) {
                    var data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];
                    return '<div class="p-2 bg-white border dark:bg-zink-600 dark:border-zink-500 rounded-md shadow-md">' +
                        '<div><strong>Payment Type:</strong> ' + data.title + '</div>' +
                        '<div><strong>Amount:</strong> Ksh. ' + data.y + '</div>' +
                        '<div><strong>Date:</strong> ' + new Date(data.x).toLocaleDateString() + '</div>' +
                        '</div>';
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2
            }
        };
        var chartPayment = new ApexCharts(document.querySelector("#payment-records-chart"), optionsPayment);
        chartPayment.render();
    }

    // Water Consumption Chart
    if (document.getElementById("water-consumption-chart")) {
        var optionsWater = {
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false }
            },
            series: [{
                name: 'Units Consumed',
                data: waterConsumptionData
            }],
            xaxis: {
                categories: waterConsumptionLabels,
                title: { text: 'Month' }
            },
            yaxis: {
                title: { text: 'Units' }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            }
        };
        var chartWater = new ApexCharts(document.querySelector("#water-consumption-chart"), optionsWater);
        chartWater.render();
    }
});