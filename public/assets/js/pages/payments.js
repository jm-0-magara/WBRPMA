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
                    '<b>Date: </b>' + date + '<br/>' +
                    '<b>Amount Paid: </b>Ksh ' + amount.toFixed(2) +
                    '</div>';
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#paymentsChart"), options);
    chart.render();

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

                    // Update the total amount paid display
                    $('#totalAmountPaid').text('Ksh ' + response.totalAmountPaid.toFixed(2));

                    // Update the payments table with new data
                    var paymentsTableBody = $('#paymentsTableBody');
                    paymentsTableBody.empty(); // Clear existing rows

                    if (response.payments.length > 0) {
                        $.each(response.payments, function(index, payment) {
                            paymentsTableBody.append(
                                '<tr class="border-b border-slate-200 last:border-b-0 dark:border-zink-500">' +
                                    '<td class="px-4 py-2">' + payment.paymentID + '</td>' +
                                    '<td class="px-4 py-2">' + payment.houseNo + '</td>' +
                                    '<td class="px-4 py-2">' + payment.paymentType + '</td>' +
                                    '<td class="px-4 py-2">' + payment.amount.toFixed(2) + '</td>' +
                                    '<td class="px-4 py-2">' + payment.timePaidFormatted + '</td>' +
                                    '<td class="px-4 py-2">' + payment.paymentMethod + '</td>' +
                                    '<td class="px-4 py-2 first:pl-5 last:pr-5 ltr:text-right rtl:text-left">' +
                                        '<div class="flex items-center gap-2 justify-end">' +
                                            '<div class="relative group">' +
                                                '<button type="button" data-modal-target="editPaymentModal-' + payment.paymentID + '" class="edit-payment-btn flex items-center justify-center p-2 text-sky-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-sky-500 hover:text-white">' +
                                                    '<i data-lucide="edit-2" class="size-4"></i>' +
                                                '</button>' +
                                                '<div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">' +
                                                    'Edit' +
                                                '</div>' +
                                            '</div>' +
                                            '<div class="relative group">' +
                                                '<button type="button" data-modal-target="deletePaymentModal-' + payment.paymentID + '" class="delete-payment-btn flex items-center justify-center p-2 text-red-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-red-500 hover:text-white">' +
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
                        paymentsTableBody.append(
                            '<tr><td colspan="7" class="text-center py-4">No payment records found for the selected filters.</td></tr>'
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

    // Handle Edit Payment Modal Population (using event delegation for dynamically added buttons)
    $(document).on('click', '.edit-payment-btn', function() {
        const paymentID = $(this).data('modal-target').replace('editPaymentModal-', '');
        const modalId = `editPaymentModal-${paymentID}`;

        // Fetch payment data via AJAX
        $.ajax({
            url: `${showPaymentRouteBase}/${paymentID}`,
            type: 'GET',
            success: function(payment) {
                // Populate the form fields in the specific modal
                $(`#${modalId} #updateHouseNoPayment-${paymentID}`).val(payment.houseNo);
                $(`#${modalId} #updatePaymentType-${paymentID}`).val(payment.paymentType);
                $(`#${modalId} #updatePaymentAmount-${paymentID}`).val(payment.amount);
                $(`#${modalId} #updatePaymentMethod-${paymentID}`).val(payment.paymentMethod);
                // Format date for input type="date"
                $(`#${modalId} #updatePaymentDate-${paymentID}`).val(moment(payment.timePaid).format('YYYY-MM-DD'));
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching payment data:', errorThrown);
                console.log('Server response:', jqXHR.responseText);
            }
        });
    });

    // Print functionality
    $('#printButton').on('click', function() {
        window.print();
    });
});
