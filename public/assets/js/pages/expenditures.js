$(document).ready(function() {
    // Ensure initial data is an array, even if empty
    if (!Array.isArray(initialChartLabels) || initialChartLabels.length === 0) {
        initialChartLabels = ['No Data'];
        initialChartData = [0];
    }

    // ApexCharts initialization for expenditures
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
            name: 'Amount Spent (Ksh)',
            data: initialChartData
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
            text: 'Expenditures Over Time',
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
            colors: ['#ef4444'] // Red color for the line
        },
        grid: {
            borderColor: '#4b5563' // Grid line color
        },
        tooltip: {
            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                const date = initialChartLabels[dataPointIndex];
                const amount = series[seriesIndex][dataPointIndex];
                return '<div class="p-2 bg-white rounded-md shadow-md dark:bg-zink-600">' +
                    //'<b>Date: </b>' + date + '<br/>' +
                    '<b>Amount Spent: </b>Ksh ' + amount.toFixed(2) +
                    '</div>';
            }
        }
    };
    var chart = new ApexCharts(document.querySelector("#expendituresChart"), options);
    chart.render();

    var monthlyExpendituresOptions = {
        chart: {
            type: 'bar',
            height: 350,
            foreColor: '#9ca3af',
            toolbar: {
                show: true
            }
        },
        series: [{
            name: 'Amount Spent (Ksh)',
            data: initialMonthlyExpendituresData
        }],
        xaxis: {
            categories: initialMonthlyLabels,
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
            text: 'Monthly Expenditures Trend',
            align: 'center',
            style: {
                fontSize: '16px',
                color: '#fff'
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        fill: {
            opacity: 1,
            colors: ['#ef4444'] // A different color, like red, to distinguish from payments
        },
        tooltip: {
            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                const month = initialMonthlyLabels[dataPointIndex];
                const amount = series[seriesIndex][dataPointIndex];
                return '<div class="p-2 bg-white rounded-md shadow-md dark:bg-zink-600">' +
                    //'<b>Month: </b>' + month + '<br/>' +
                    '<b>Total Expenditures: </b>Ksh ' + amount.toFixed(2) +
                    '</div>';
            }
        }
    };

    var monthlyExpendituresChart = new ApexCharts(document.querySelector("#monthlyExpendituresChart"), monthlyExpendituresOptions);
    monthlyExpendituresChart.render();

    // Event listener for the filter button
    $('#filterButton').on('click', function() {
        var expenditureType = $('#expenditureTypeSelect').val();
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
                expenditureType: expenditureType,
                startDate: startDate,
                endDate: endDate,
                _token: csrfToken
            },
            success: function(response) {
                if (response.success) {
                    let newLabels = response.labels;
                    let newData = response.data;

                    if (!Array.isArray(newLabels) || newLabels.length === 0) {
                        newLabels = ['No Data'];
                        newData = [0];
                    }

                    // Update the chart with new data
                    chart.updateOptions({
                        xaxis: {
                            categories: newLabels
                        }
                    });
                    chart.updateSeries([{
                        name: 'Amount Spent (Ksh)',
                        data: newData
                    }]);

                    //Bar chart
                    monthlyExpendituresChart.updateOptions({
                        xaxis: {
                            categories: response.monthlyLabels
                        }
                    });
                    monthlyExpendituresChart.updateSeries([{
                        name: 'Amount Spent (Ksh)',
                        data: response.monthlyExpendituresData
                    }]);

                    // Update the total amount spent display
                    $('#totalAmountSpent').text('Ksh ' + response.totalAmountSpent.toFixed(2));

                    // Update the expenditures table with new data
                    var expendituresTableBody = $('#expendituresTableBody');
                    expendituresTableBody.empty(); // Clear existing rows

                    if (response.expenditures.length > 0) {
                        $.each(response.expenditures, function(index, expenditure) {
                            expendituresTableBody.append(
                                '<tr class="border-b border-slate-200 last:border-b-0 dark:border-zink-500">' +
                                    '<td class="px-4 py-2">' + expenditure.expenditureID + '</td>' +
                                    '<td class="px-4 py-2">' + expenditure.expenditureType + '</td>' +
                                    '<td class="px-4 py-2">' + expenditure.amount.toFixed(2) + '</td>' +
                                    '<td class="px-4 py-2">' + expenditure.timePaidFormatted + '</td>' +
                                    '<td class="px-4 py-2 first:pl-5 last:pr-5 ltr:text-right rtl:text-left">' +
                                        '<div class="flex items-center gap-2 justify-end">' +
                                            '<div class="relative group">' +
                                                '<button type="button" data-modal-target="editExpenditureModal-' + expenditure.expenditureID + '" class="edit-expenditure-btn flex items-center justify-center p-2 text-sky-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-sky-500 hover:text-white">' +
                                                    '<i data-lucide="edit-2" class="size-4"></i>' +
                                                '</button>' +
                                                '<div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">' +
                                                    'Edit' +
                                                '</div>' +
                                            '</div>' +
                                            '<div class="relative group">' +
                                                '<button type="button" data-modal-target="deleteExpenditureModal-' + expenditure.expenditureID + '" class="delete-expenditure-btn flex items-center justify-center p-2 text-red-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-red-500 hover:text-white">' +
                                                    '<i data-lucide="trash-2" class="size-4"></i>' +
                                                '</button>' +
                                                '<div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">' +
                                                    'Delete' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</td>' +
                                '</tr>'
                            );
                        });
                    } else {
                        expendituresTableBody.append(
                            '<tr><td colspan="5" class="text-center py-4">No expenditure records found for the selected filters.</td></tr>'
                        );
                    }
                    // Re-initialize Lucide icons for new elements
                    lucide.createIcons();

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

    // Handle Edit Expenditure Modal Population (using event delegation for dynamically added buttons)
    $(document).on('click', '.edit-expenditure-btn', function() {
        const expenditureID = $(this).data('modal-target').replace('editExpenditureModal-', '');
        const modalId = `editExpenditureModal-${expenditureID}`;

        // Fetch expenditure data via AJAX
        $.ajax({
            url: `${showExpenditureRouteBase}/${expenditureID}`,
            type: 'GET',
            success: function(expenditure) {
                // Populate the form fields in the specific modal
                $(`#${modalId} #updateExpenditureType-${expenditureID}`).val(expenditure.expenditureType);
                $(`#${modalId} #updateAmount-${expenditureID}`).val(expenditure.amount);
                // Format date for input type="date"
                $(`#${modalId} #updateTimePaid-${expenditureID}`).val(moment(expenditure.timePaid).format('YYYY-MM-DD'));
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching expenditure data:', errorThrown);
                console.log('Server response:', jqXHR.responseText);
            }
        });
    });

    // Print functionality
    $('#printButton').on('click', function() {
        window.print();
    });
});
