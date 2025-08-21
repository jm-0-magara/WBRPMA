$(document).ready(function() {
    // Ensure initial data is an array, even if empty
    if (!Array.isArray(initialChartLabels) || initialChartLabels.length === 0) {
        initialChartLabels = ['No Data'];
        initialChartData = [0];
    }

    // ApexCharts initialization for payments
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
            name: 'Amount Paid (Ksh)',
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
            text: 'Payments Over Time',
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
            colors: ['#0ea5e9'] // Blue color for the line
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
                    '<b>Amount Paid: </b>Ksh ' + amount.toFixed(2) +
                    '</div>';
            }
        }
    };
    var chart = new ApexCharts(document.querySelector("#paymentsChart"), options);
    chart.render();

    var monthlyPaymentsOptions = {
        chart: {
            type: 'bar',
            height: 350,
            foreColor: '#9ca3af',
            toolbar: {
                show: true
            }
        },
        series: [{
            name: 'Amount Paid (Ksh)',
            data: initialMonthlyPaymentsData
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
            text: 'Monthly Payments Trend',
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
            colors: ['#0ea5e9'] // Match line chart color or use a different one
        },
        tooltip: {
            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                const month = initialMonthlyLabels[dataPointIndex];
                const amount = series[seriesIndex][dataPointIndex];
                return '<div class="p-2 bg-white rounded-md shadow-md dark:bg-zink-600">' +
                    //'<b>Month: </b>' + month + '<br/>' +
                    '<b>Total Payments: </b>Ksh ' + amount.toFixed(2) +
                    '</div>';
            }
        }
    };
    var monthlyPaymentsChart = new ApexCharts(document.querySelector("#monthlyPaymentsChart"), monthlyPaymentsOptions);
    monthlyPaymentsChart.render();

    // Event listener for the filter button
    $('#filterButton').on('click', function() {
        var paymentType = $('#paymentTypeSelect').val();
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
                paymentType: paymentType,
                houseNo: houseNo,
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
                        name: 'Amount Paid (Ksh)',
                        data: newData
                    }]);

                    //Update bar chart now
                    monthlyPaymentsChart.updateOptions({
                        xaxis: {
                            categories: response.monthlyLabels
                        }
                    });
                    monthlyPaymentsChart.updateSeries([{
                        name: 'Amount Paid (Ksh)',
                        data: response.monthlyPaymentsData
                    }]);

                    // Update the total amount paid display
                    $('#totalAmountPaid').text('Ksh ' + response.totalAmountPaid.toFixed(2));

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

                    var paymentsTableBody = $('#paymentsTableBody');

                    // Ensure there is a container where we will append modals. If not present, create it at end of body.
                    var paymentsModalsContainer = $('#paymentsModalsContainer');
                    if (!paymentsModalsContainer.length) {
                        $('body').append('<div id="paymentsModalsContainer"></div>');
                        paymentsModalsContainer = $('#paymentsModalsContainer');
                    }

                    // Clear existing rows and modals
                    paymentsTableBody.empty();
                    paymentsModalsContainer.empty();

                    if (response.payments.length > 0) {
                        $.each(response.payments, function(index, payment) {

                            // Build table row (same markup you had; kept here so code remains self-contained)
                            var rowHtml =
                                '<tr class="border-b border-slate-200 last:border-b-0 dark:border-zink-500">' +
                                    '<td class="px-4 py-2">' + escapeHtml(payment.paymentID) + '</td>' +
                                    '<td class="px-4 py-2">' + escapeHtml(payment.houseNo) + '</td>' +
                                    '<td class="px-4 py-2">' + escapeHtml(payment.paymentType) + '</td>' +
                                    '<td class="px-4 py-2">' + (Number(payment.amount).toFixed(2)) + '</td>' +
                                    '<td class="px-4 py-2">' + escapeHtml(payment.timePaidFormatted || '') + '</td>' +
                                    '<td class="px-4 py-2">' + escapeHtml(payment.paymentMethod || '') + '</td>' +
                                    '<td class="px-4 py-2">' + escapeHtml(payment.narration || '') + '</td>' +
                                    '<td class="px-4 py-2 first:pl-5 last:pr-5 ltr:text-right rtl:text-left">' +
                                        '<div class="flex items-center gap-2 justify-end">' +
                                            '<div class="relative group">' +
                                                '<button type="button" data-modal-target="editPaymentModal-' + payment.paymentID + '" class="edit-payment-btn flex items-center justify-center p-2 text-sky-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-sky-500 hover:text-white">' +
                                                    '<i data-lucide="edit-2" class="size-4"></i>' +
                                                '</button>' +
                                                '<div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">Edit</div>' +
                                            '</div>' +
                                            '<div class="relative group">' +
                                                '<button type="button" data-modal-target="deletePaymentModal-' + payment.paymentID + '" class="delete-payment-btn flex items-center justify-center p-2 text-red-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-red-500 hover:text-white">' +
                                                    '<i data-lucide="trash-2" class="size-4"></i>' +
                                                '</button>' +
                                                '<div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">Delete</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</td>' +
                                '</tr>';

                            paymentsTableBody.append(rowHtml);

                            // Build edit modal HTML (IDs and field IDs must match what your click handler expects)
                            // Use CSRF token meta tag, and set form action to updatePaymentRouteBase/<id>
                            var csrfToken = $('meta[name="csrf-token"]').attr('content') || '';

                            // Format date for date input (requires moment.js available as in your file)
                            var dateVal = '';
                            try {
                                dateVal = payment.timePaid ? moment(payment.timePaid).format('YYYY-MM-DD') : '';
                            } catch (e) { dateVal = ''; }


                            // Generate House No. options
                            var houseNoOptionsHtml = '';
                            houseNos.forEach(function(houseNo) {
                                var selected = (payment.houseNo == houseNo) ? 'selected' : '';
                                houseNoOptionsHtml += '<option value="' + escapeHtml(houseNo) + '" ' + selected + '>' + escapeHtml(houseNo) + '</option>';
                            });

                            // Generate Payment Type options
                            var paymentTypeOptionsHtml = '';
                            // Pre-defined types
                            var allPaymentTypes = ['Rent', 'Rent Deposit'].concat(paymentTypes.filter(type => type !== 'Rent' && type !== 'Rent Deposit'));
                            allPaymentTypes.forEach(function(type) {
                                var selected = (payment.paymentType == type) ? 'selected' : '';
                                paymentTypeOptionsHtml += '<option value="' + escapeHtml(type) + '" ' + selected + '>' + escapeHtml(type) + '</option>';
                            });

                            // Generate Payment Method options
                            var paymentMethodOptionsHtml = '';
                            var paymentMethods = ['M-Pesa', 'Cash', 'Bank Transfer', 'Cheque', 'Other'];
                            paymentMethods.forEach(function(method) {
                                var selected = (payment.paymentMethod == method) ? 'selected' : '';
                                paymentMethodOptionsHtml += '<option value="' + escapeHtml(method) + '" ' + selected + '>' + escapeHtml(method) + '</option>';
                            });

                            var editModalHtml =
                                '<div id="editPaymentModal-' + payment.paymentID + '" modal-center class="fixed inset-0 z-50 hidden items-center justify-center p-4">' +
                                 '<div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600" data-modal-close-outside="modal-close-btn px-3 py-1 rounded bg-gray-200">' +
                                        '<div class="flex items-center justify-between p-4 border-b dark:border-zink-500">' +
                                            '<h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Update Payment Record</h5>' +
                                            '<button type="button" class="modal-close-btn px-3 py-1 rounded bg-gray-200">' +
                                                '<i data-lucide="x" class="size-4"></i>' +
                                            '</button>' +
                                        '</div>' +
                                        '<div class="max-h-[calc(theme(\'height.screen\')_-_180px)] p-4 overflow-y-auto">' +
                                '<div class="w-full max-w-md bg-white rounded-md shadow-lg dark:bg-zink-600">' +
                                    '<div class="p-4">' +
                                    '<form id="updatePaymentForm-' + payment.paymentID + '" method="POST" action="' + (updatePaymentRouteBase ? updatePaymentRouteBase + '/' + payment.paymentID : '#') + '">' +
                                        '<input type="hidden" name="_token" value="' + escapeHtml(csrfToken) + '">' +
                                        '<div class="mb-2">' +
                                        '<label for="updateHouseNoPayment-' + payment.paymentID + '" class="inline-block mb-2 text-base font-medium">Select House</label>' +
                                        '<select name="houseNo" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="updateHouseNoPayment-' + payment.paymentID + '" required>' +
                                        houseNoOptionsHtml +
                                        '</select>' +
                                        '</div>' +
                                        '<div>' +
                                        '<label for="updatePaymentType-' + payment.paymentID + '" class="inline-block mb-2 text-base font-medium">Payment Type</label>' +
                                        '<select name="paymentType" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="updatePaymentType-' + payment.paymentID + '" required>' +
                                        paymentTypeOptionsHtml +
                                        '</select>' +
                                        '</div>' +
                                        '<div>' +
                                        '<label for="updatePaymentAmount-' + payment.paymentID + '" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount Paid</label>' +
                                        '<input type="number" step="0.01" name="amount" value="' + (Number(payment.amount).toFixed(2)) + '" id="updatePaymentAmount-' + payment.paymentID + '" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="e.g. 1500.00" required>' +
                                        '</div>' +
                                        '<div>' +
                                        '<label for="updatePaymentMethod-' + payment.paymentID + '" class="inline-block mb-2 text-base font-medium">Payment Method</label>' +
                                        '<select name="paymentMethod" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="updatePaymentMethod-' + payment.paymentID + '" required>' +
                                        paymentMethodOptionsHtml +
                                        '</select>' +
                                        '</div>' +
                                        '<div class="col-span-full">' +
                                        '<label for="updatePaymentDate-' + payment.paymentID + '" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Paid</label>' +
                                        '<input type="date" name="timePaid" id="updatePaymentDate-' + payment.paymentID + '" value="' + escapeHtml(dateVal) + '" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" required>' +
                                        '</div>' +
                                        '<div class="col-span-full">' +
                                        '<label for="updatePaymentNarration-' + payment.paymentID + '" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Narration (Optional)</label>' +
                                        '<textarea name="narration" id="updatePaymentNarration-' + payment.paymentID + '" class="form-textarea border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" rows="3">' + escapeHtml(payment.narration || '') + '</textarea>' +
                                        '</div>' +
                                        '<div class="flex justify-end gap-2">' +
                                        '<button type="button" class="modal-close-btn px-3 py-1 rounded bg-gray-200">Cancel</button>' +
                                        '<button type="submit" class="px-3 py-1 rounded bg-sky-500 text-white">Update</button>' +
                                        '</div>' +
                                    '</form>' +
                                    '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            // Build delete modal HTML
                            var deleteModalHtml =
                                '<div id="deletePaymentModal-' + payment.paymentID + '" modal-center class="fixed inset-0 z-50 hidden items-center justify-center p-4">' +
                                '<div class="w-screen md:w-[25rem] bg-white shadow rounded-md dark:bg-zink-600">' +
                                        '<div class="max-h-[calc(theme(\'height.screen\')_-_180px)] overflow-y-auto px-6 py-8">' +
                                            '<div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-zink-500">' +
                                                '<h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Confirm Deletion</h5>' +
                                                '<button type="button" class="modal-close-btn px-3 py-1 rounded bg-gray-200">' +
                                                    '<i data-lucide="x" class="size-4"></i>' +
                                                '</button>' +
                                            '</div>' +
                                '<div class="w-full max-w-sm bg-white rounded-md shadow-lg dark:bg-zink-600">' +
                                    '<div class="p-4">' +
                                    '<form id="deletePaymentForm-' + payment.paymentID + '" method="POST" action="' + (deletePaymentRouteBase ? deletePaymentRouteBase + '/' + payment.paymentID : '#') + '">' +
                                        '<input type="hidden" name="_token" value="' + escapeHtml(csrfToken) + '">' +
                                        '<input type="hidden" name="_method" value="DELETE">' +
                                        '<p class="mb-3">Are you sure you want to delete payment <strong>' + escapeHtml(payment.paymentID) + '</strong> for house <strong>' + escapeHtml(payment.houseNo) + '</strong>?</p>' +
                                        '<div class="flex justify-end gap-2">' +
                                        '<button type="button" class="modal-close-btn px-3 py-1 rounded bg-gray-200">Cancel</button>' +
                                        '<button type="submit" class="px-3 py-1 rounded bg-red-500 text-white">Delete</button>' +
                                        '</div>' +
                                    '</form>' +
                                    '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            // Append modals to container
                            paymentsModalsContainer.append(editModalHtml);
                            paymentsModalsContainer.append(deleteModalHtml);
                        });
                    } else {
                        paymentsTableBody.append(
                            '<tr><td colspan="7" class="text-center py-4">No payment records found for the selected filters.</td></tr>'
                        );
                    }

                    // Re-initialize Lucide icons for newly-added elements
                    if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
                        lucide.createIcons();
                    }

                    // Optional: wire up simple modal open/close behavior for the modal HTML we added above,
                    // only if your project doesn't already provide it. This code simply toggles the hidden class.
                    // If you already have a modal library that opens modals via data-modal-target, you can skip this.
                    // -------------------------
                    $(document).off('click', '[data-modal-target]').on('click', '[data-modal-target]', function() {
                        var targetId = $(this).data('modal-target');
                        $('#' + targetId).removeClass('hidden').addClass('flex');
                    });

                    // Close buttons with .modal-close-btn that we placed above
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
});
