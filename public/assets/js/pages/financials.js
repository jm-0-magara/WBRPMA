$(document).ready(function() {
    // Ensure initial data is an array, even if empty
    if (!Array.isArray(initialChartLabels) || initialChartLabels.length === 0) {
        initialChartLabels = ['No Data'];
        initialGrossProfitData = [0];
        initialExpenditureData = [0];
        initialNetProfitData = [0];
    }

    // ApexCharts initialization for Profits, Expenditures, and Net Profit
    var options = {
        chart: {
            type: 'line',
            height: 350,
            foreColor: '#9ca3af', // Text color for labels, etc.
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    pan: true,
                    reset: true
                }
            }
        },
        series: [{
            name: 'Gross Profit (Ksh)',
            data: initialGrossProfitData
        }, {
            name: 'Expenditure (Ksh)',
            data: initialExpenditureData
        }, {
            name: 'Net Profit (Ksh)',
            data: initialNetProfitData
        }],
        xaxis: {
            categories: initialChartLabels,
            labels: {
                style: {
                    colors: '#9ca3af'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#9ca3af'
                }
            },
            title: {
                text: 'Amount (Ksh)',
                style: {
                    color: '#9ca3af'
                }
            }
        },
        title: {
            text: 'Financial Overview: Profit vs. Expenditure',
            align: 'center',
            style: {
                fontSize: '16px',
                color: '#fff' // Title color
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3,
            colors: ['#4ade80', '#ef4444', '#0ea5e9'] // Green for Gross, Red for Expenditure, Blue for Net
        },
        grid: {
            borderColor: '#4b5563' // Grid line color
        },
        tooltip: {
            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                const date = initialChartLabels[dataPointIndex];
                const grossProfit = series[0][dataPointIndex];
                const expenditure = series[1][dataPointIndex];
                const netProfit = series[2][dataPointIndex];

                return '<div class="p-2 bg-white rounded-md shadow-md dark:bg-zink-600">' +
                    '<b>Date: </b>' + date + '<br/>' +
                    '<b>Gross Profit: </b>Ksh ' + grossProfit.toFixed(2) + '<br/>' +
                    '<b>Expenditure: </b>Ksh ' + expenditure.toFixed(2) + '<br/>' +
                    '<b>Net Profit: </b>Ksh ' + netProfit.toFixed(2) +
                    '</div>';
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#profitsChart2"), options);
    chart.render();

    // Event listeners for the time range buttons
    $('.dropdown button').on('click', function() {
        const timeRange = $(this).text(); // "All", "1M", "6M", "1Y"
        let startDate = null;
        let endDate = moment().format('YYYY-MM-DD'); // Current date as end date

        if (timeRange === '1M') {
            startDate = moment().subtract(1, 'months').format('YYYY-MM-DD');
        } else if (timeRange === '6M') {
            startDate = moment().subtract(6, 'months').format('YYYY-MM-DD');
        } else if (timeRange === '1Y') {
            startDate = moment().subtract(1, 'years').format('YYYY-MM-DD');
        }
        // If "All" is selected, startDate remains null

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (!csrfToken) {
            console.error("CSRF token not found. Please ensure <meta name='csrf-token'> is in your HTML header.");
            return;
        }

        $.ajax({
            url: filterFinancialsRoute, // This variable needs to be defined in your Blade file
            type: 'POST',
            data: {
                startDate: startDate,
                endDate: endDate,
                _token: csrfToken
            },
            success: function(response) {
                if (response.success) {
                    let newLabels = response.labels;
                    let newGrossProfitSeries = response.grossProfitSeries;
                    let newExpenditureSeries = response.expenditureSeries;
                    let newNetProfitSeries = response.netProfitSeries;

                    if (!Array.isArray(newLabels) || newLabels.length === 0) {
                        newLabels = ['No Data'];
                        newGrossProfitSeries = [0];
                        newExpenditureSeries = [0];
                        newNetProfitSeries = [0];
                    }

                    // Update the chart with new data
                    chart.updateOptions({
                        xaxis: {
                            categories: newLabels
                        }
                    });
                    chart.updateSeries([{
                        name: 'Gross Profit (Ksh)',
                        data: newGrossProfitSeries
                    }, {
                        name: 'Expenditure (Ksh)',
                        data: newExpenditureSeries
                    }, {
                        name: 'Net Profit (Ksh)',
                        data: newNetProfitSeries
                    }]);

                } else {
                    console.error("Error from server:", response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error filtering data:', errorThrown);
                console.log('Server response:', jqXHR.responseText);
            }
        });
    });
});
