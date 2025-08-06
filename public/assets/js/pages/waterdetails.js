$(document).ready(function() {
    // Check if initial data is available
    // These are the global variables set in the Blade file
    // No need to redeclare them with var
    // Also, the Blade code handles the empty array case, but it's good to have a check here too.
    if (!Array.isArray(initialChartLabels) || initialChartLabels.length === 0) {
        initialChartLabels = ['No Data'];
        initialChartData = [0];
    }

    //holds consumed units for the chart
    let chartUnitsData = initialUnitsConsumedData;
    let chartPaymentsData = initialPaymentsData;
    let chartBalanceData = initialBalanceData;
    
    // ApexCharts initialization
    var options = {
        chart: {
            type: 'line',
            height: 350,
            foreColor: '#9ca3af',
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    pan: true,
                    reset: true
                }
            }
        },
        // NEW: Multiple series for Consumption and Payments
        series: [{
            name: 'Payments (Ksh)',
            data: initialChartData
        }, {
            name: 'Consumption (Ksh)',
            data: initialPaymentsData
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
            }
        },
        title: {
            text: 'Water Consumption vs. Payments',
            align: 'center',
            style: {
                fontSize: '16px',
                color: '#fff'
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3,
            colors: ['#4ade80', '#0ea5e9'] // NEW: Different colors for each line
        },
        grid: {
            borderColor: '#4b5563'
        },
        // NEW: Custom tooltip to show consumption, payments, and balance
        tooltip: {
            custom: function({
                series,
                seriesIndex,
                dataPointIndex,
                w
            }) {
                const date = initialChartLabels[dataPointIndex];
                const consumptionAmount = series[0][dataPointIndex];
                const units = chartUnitsData[dataPointIndex];
                const paymentAmount = series[1][dataPointIndex];
                const balance = chartBalanceData[dataPointIndex];

                return '<div class="p-2 bg-white rounded-md shadow-md dark:bg-zink-600">' +
                    '<b>Date: </b>' + date + '<br/>' +
                    '<b>Consumption: </b>Ksh ' + consumptionAmount.toFixed(2) + '<br/>' +
                    '<b>Units Consumed: </b>' + (units ? units.toFixed(2) : 'N/A') + '<br/>' +
                    '<b>Payment: </b>Ksh ' + paymentAmount.toFixed(2) + '<br/>' +
                    '<b>Balance: </b>Ksh ' + balance.toFixed(2) + '<br/>' +
                    '</div>';
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#waterChart"), options);
    chart.render();

    // Event listener for the filter button
    $('#filterButton').on('click', function() {
        var houseNo = $('#houseSelect').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (!csrfToken) {
            console.error("CSRF token not found. Please ensure <meta name='csrf-token'> is in your HTML header.");
            return;
        }

        $.ajax({
            url: filterRoute,
            type: 'POST',
            data: {
                houseNo: houseNo,
                startDate: startDate,
                endDate: endDate,
                _token: csrfToken
            },
            success: function(response) {
                if (response.success) {
                    let newLabels = response.labels;
                    let newData = response.data;
                    let newUnitsConsumedData = response.unitsConsumedData;
                    // NEW: Get the payments and balance data from the response
                    let newPaymentsData = response.paymentsData;
                    let newBalanceData = response.balanceData;

                    if (!Array.isArray(newLabels) || newLabels.length === 0) {
                        newLabels = ['No Data'];
                        newData = [0];
                        newUnitsConsumedData = [0];
                        newPaymentsData = [0];
                        newBalanceData = [0];
                    }

                    // Update the global data arrays
                    chartUnitsData = newUnitsConsumedData;
                    chartPaymentsData = newPaymentsData;
                    chartBalanceData = newBalanceData;

                    // Update the chart with new data
                    chart.updateOptions({
                        xaxis: {
                            categories: newLabels
                        }
                    });
                    chart.updateSeries([{
                        name: 'Consumption (Ksh)',
                        data: newData
                    }, {
                        name: 'Payments (Ksh)',
                        data: newPaymentsData
                    }]);

                    // Update the totals display
                    $('#totalUnits').text(response.totalUnits.toFixed(2) + ' units');
                    $('#totalAmount').text('Ksh ' + response.totalAmount.toFixed(2));
                    // NEW: Update total payments
                    $('#totalPayments').text('Ksh ' + response.totalPayments.toFixed(2));

                    // Update the consumption table with new data
                    var consumptionTableBody = $('#waterDetailsBody');
                    consumptionTableBody.empty();

                    if (response.waterDetails.length > 0) {
                        $.each(response.waterDetails, function(index, detail) {
                            var amount = detail.unitsConsumed * waterPrice;
                            consumptionTableBody.append(
                                '<tr class="border-b border-slate-600 last:border-b-0">' +
                                    '<td class="px-4 py-2">' + detail.houseNo + '</td>' +
                                    '<td class="px-4 py-2">' + detail.date + '</td>' +
                                    '<td class="px-4 py-2">' + detail.unitsConsumed.toFixed(2) + '</td>' +
                                    '<td class="px-4 py-2">' + amount.toFixed(2) + '</td>' +
                                '</tr>'
                            );
                        });
                    } else {
                        consumptionTableBody.append(
                            '<tr><td colspan="5" class="text-center py-4">No water consumption data found.</td></tr>'
                        );
                    }

                    // NEW: Update the payments table with new data
                    var paymentsTableBody = $('#waterPaymentsBody');
                    paymentsTableBody.empty();
                    if (response.payments.length > 0) {
                        $.each(response.payments, function(index, payment) {
                            paymentsTableBody.append(
                                '<tr class="border-b border-slate-600 last:border-b-0">' +
                                    '<td class="px-4 py-2">' + payment.houseno + '</td>' +
                                    '<td class="px-4 py-2">' + payment.timePaid.split(' ')[0] + '</td>' +
                                    '<td class="px-4 py-2">' + payment.amount.toFixed(2) + '</td>' +
                                    '<td class="px-4 py-2">' + payment.paymentMethod + '</td>' +
                                '</tr>'
                            );
                        });
                    } else {
                        paymentsTableBody.append(
                            '<tr><td colspan="4" class="text-center py-4">No water payments found.</td></tr>'
                        );
                    }
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