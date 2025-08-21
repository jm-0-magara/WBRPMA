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


                    // Update the payments table with new data
                    function escapeHtml(str) {
                        if (str === null || str === undefined) return '';
                        return String(str)
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#39;');
                    }

                    // Update the expenditures table with new data
                    var expendituresTableBody = $('#expendituresTableBody');

                    // Ensure there is a container where we will append modals. If not present, create it at end of body.
                    var expendituresModalsContainer = $('#expendituresModalsContainer');
                    if (!expendituresModalsContainer.length) {
                        $('body').append('<div id="expendituresModalsContainer"></div>');
                        expendituresModalsContainer = $('#expendituresModalsContainer');
                    }

                    // Clear existing rows and modals
                    expendituresTableBody.empty();
                    expendituresModalsContainer.empty();

                    if (response.expenditures.length > 0) {
                        $.each(response.expenditures, function(index, expenditure) {
                            // Build table row
                            var rowHtml =
                                '<tr class="border-b border-slate-200 last:border-b-0 dark:border-zink-500">' +
                                '<td class="px-4 py-2">' + escapeHtml(expenditure.expenditureID) + '</td>' +
                                '<td class="px-4 py-2">' + escapeHtml(expenditure.expenditureType) + '</td>' +
                                '<td class="px-4 py-2">' + (Number(expenditure.amount).toFixed(2)) + '</td>' +
                                '<td class="px-4 py-2">' + escapeHtml(expenditure.timePaidFormatted || '') + '</td>' +
                                '<td class="px-4 py-2 first:pl-5 last:pr-5 ltr:text-right rtl:text-left">' +
                                        '<div class="flex items-center gap-2 justify-end">' +
                                            '<div class="relative group">' +
                                                '<button type="button" data-modal-target="editExpenditureModal-' + expenditure.expenditureID + '" class="edit-expenditure-btn flex items-center justify-center p-2 text-sky-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-sky-500 hover:text-white">' +
                                                    '<i data-lucide="edit-2" class="size-4"></i>' +
                                                '</button>' +
                                                '<div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">Edit</div>' +
                                            '</div>' +
                                            '<div class="relative group">' +
                                                '<button type="button" data-modal-target="deleteExpenditureModal-' + expenditure.expenditureID + '" class="delete-expenditure-btn flex items-center justify-center p-2 text-red-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-red-500 hover:text-white">' +
                                                    '<i data-lucide="trash-2" class="size-4"></i>' +
                                                '</button>' +
                                                '<div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">Delete</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</td>' +
                                '</tr>';

                            expendituresTableBody.append(rowHtml);

                            var csrfToken = $('meta[name="csrf-token"]').attr('content') || '';
                            var dateVal = '';
                            try {
                                dateVal = expenditure.timePaid ? moment(expenditure.timePaid).format('YYYY-MM-DD') : '';
                            } catch (e) {
                                dateVal = '';
                            }

                            // Generate Expenditure Type options
                            var expenditureTypeOptionsHtml = '';
                            expenditureTypes.forEach(function(expenditureType) {
                                var selected = (expenditureType === expenditure.expenditureType) ? 'selected' : '';
                                expenditureTypeOptionsHtml += '<option value="' + escapeHtml(expenditureType) + '" ' + selected + '>' + escapeHtml(expenditureType) + '</option>';
                            });


                            // Build edit modal HTML
                            var editModalHtml =
                                '<div id="editExpenditureModal-' + expenditure.expenditureID + '" modal-center class="fixed inset-0 z-50 hidden items-center justify-center p-4">' +
                                '<div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">' +
                                '<div class="flex items-center justify-between p-4 border-b dark:border-zink-500">' +
                                '<h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Update Expenditure Record</h5>' +
                                '<button type="button" class="modal-close-btn text-gray-400 hover:text-gray-600 dark:hover:text-zink-200">' +
                                '<i data-lucide="x" class="size-4"></i>' +
                                '</button>' +
                                '</div>' +
                                '<div class="max-h-[calc(theme(\'height.screen\')_-_180px)] p-4 overflow-y-auto">' +
                                '<form id="updateExpenditureForm-' + expenditure.expenditureID + '" method="POST" action="' + (updateExpenditureRouteBase ? updateExpenditureRouteBase + '/' + expenditure.expenditureID : '#') + '">' +
                                '<input type="hidden" name="_token" value="' + escapeHtml(csrfToken) + '">' +
                                '<div class="grid grid-cols-1 gap-5 md:grid-cols-2">' +
                                '<div>' +
                                '<label for="updateExpenditureType-' + expenditure.expenditureID + '" class="inline-block mb-2 text-base font-medium">Expenditure Type</label>' +
                                '<select name="expenditureType" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="updateExpenditureType-' + expenditure.expenditureID + '" required>' +
                                expenditureTypeOptionsHtml +
                                '</select>' +
                                '</div>' +
                                '<div>' +
                                '<label for="updateAmount-' + expenditure.expenditureID + '" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount</label>' +
                                '<input type="number" step="0.01" name="amount" value="' + (Number(expenditure.amount).toFixed(2)) + '" id="updateAmount-' + expenditure.expenditureID + '" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="e.g. 1500.00" required>' +
                                '</div>' +
                                '<div class="col-span-full">' +
                                '<label for="updateTimePaid-' + expenditure.expenditureID + '" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Paid</label>' +
                                '<input type="date" name="timePaid" id="updateTimePaid-' + expenditure.expenditureID + '" value="' + escapeHtml(dateVal) + '" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" required>' +
                                '</div>' +
                                '</div>' +
                                '<div class="flex justify-end gap-2 mt-4">' +
                                '<button type="submit" class="text-white btn bg-yellow-500 border-yellow-500 hover:text-white hover:bg-yellow-600">Update Expenditure</button>' +
                                '</div>' +
                                '</form>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            // Build delete modal HTML
                            var deleteModalHtml =
                                '<div id="deleteExpenditureModal-' + expenditure.expenditureID + '" modal-center class="fixed inset-0 z-50 hidden items-center justify-center p-4">' +
                                '<div class="w-screen md:w-[25rem] bg-white shadow rounded-md dark:bg-zink-600">' +
                                '<div class="max-h-[calc(theme(\'height.screen\')_-_180px)] overflow-y-auto px-6 py-8">' +
                                '<div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-zink-500">' +
                                '<h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Confirm Deletion</h5>' +
                                '<button type="button" class="modal-close-btn text-gray-400 hover:text-gray-600 dark:hover:text-zink-200">' +
                                '<i data-lucide="x" class="size-4"></i>' +
                                '</button>' +
                                '</div>' +
                                '<div class="p-4 overflow-y-auto">' +
                                '<p class="text-gray-700 dark:text-zink-200">Are you sure you want to delete the expenditure record for <span class="font-semibold text-red-500">' + escapeHtml(expenditure.expenditureType) + '</span> with ID <span class="font-semibold text-red-500">' + escapeHtml(expenditure.expenditureID) + '</span>?</p>' +
                                '<div class="flex justify-end gap-2 mt-4">' +
                                '<button type="button" class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 modal-close-btn">Cancel</button>' +
                                '<form id="deleteExpenditureForm-' + expenditure.expenditureID + '" method="POST" action="' + (deleteExpenditureRouteBase ? deleteExpenditureRouteBase + '/' + expenditure.expenditureID : '#') + '">' +
                                '<input type="hidden" name="_token" value="' + escapeHtml(csrfToken) + '">' +
                                '<input type="hidden" name="_method" value="DELETE">' +
                                '<input type="hidden" name="expenditureID" value="' + escapeHtml(expenditure.expenditureID) + '">' +
                                '<button type="submit" class="btn bg-red-500 text-white hover:bg-red-600">Delete</button>' +
                                '</form>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            // Append modals to container
                            expendituresModalsContainer.append(editModalHtml);
                            expendituresModalsContainer.append(deleteModalHtml);
                        });
                    } else {
                        expendituresTableBody.append(
                            '<tr><td colspan="5" class="text-center py-4">No expenditure records found for the selected filters.</td></tr>'
                        );
                    }

                    // Re-initialize Lucide icons for newly-added elements
                    if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
                        lucide.createIcons();
                    }

                    // Simple modal open/close behavior for dynamically added modals
                    $(document).off('click', '[data-modal-target]').on('click', '[data-modal-target]', function() {
                        var targetId = $(this).data('modal-target');
                        $('#' + targetId).removeClass('hidden').addClass('flex');
                    });

                    $(document).off('click', '.modal-close-btn').on('click', '.modal-close-btn', function() {
                        $(this).closest('[modal-center]').removeClass('flex').addClass('hidden');
                    });

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

    // Print functionality
    $('#printButton').on('click', function() {
        window.print();
    });

    // Handle the expenditure type change for maintenance fields
    const expenditureTypeSelect = document.getElementById('addExpenditureType');
    const maintenanceFields = document.getElementById('maintenanceFields');
    const addHouseNoInput = document.getElementById('addHouseNo');
    const addMaintenanceDescriptionInput = document.getElementById('addMaintenanceDescription');

    if (expenditureTypeSelect) {
        expenditureTypeSelect.addEventListener('change', function() {
            if (this.value === 'Maintenance') {
                maintenanceFields.style.display = 'block';
                addHouseNoInput.setAttribute('required', 'required');
            } else {
                maintenanceFields.style.display = 'none';
                addHouseNoInput.removeAttribute('required');
            }
        });
    }
});
