// This file assumes jQuery is available, as hinted by the code structure.
$(document).ready(function() {

    // Initialize the ApexCharts graph with initial data from the controller
    var options = {
        chart: {
            type: 'line',
            height: 350
        },
        series: [{
            name: 'Maintenance Costs',
            data: initialChartData // Data passed from the blade view
        }],
        xaxis: {
            categories: initialChartLabels // Labels passed from the blade view
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$" + val.toFixed(2);
                }
            }
        },
        colors: ["#ef4444"], // A red color for expenditure
        stroke: {
            curve: 'smooth'
        }
    };
    var chart = new ApexCharts(document.querySelector("#maintenanceChart"), options);
    chart.render();

    // Event listener for the filter button
    $('#filterButton').on('click', function() {
        var houseNo = $('#houseSelect').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        
        // CSRF token is necessary for POST requests in Laravel
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: filterRoute, // Make sure this route exists(defineed in the blade view)
            type: 'POST',
            data: {
                houseNo: houseNo,
                startDate: startDate,
                endDate: endDate,
                _token: csrfToken // Passing CSRF token
            },
            success: function(response) {
                // Update the total expenditure display
                $('#totalExpenditureDisplay').text(response.totalExpenditure.toFixed(2));
                
                // Update the chart with new data
                chart.updateSeries([{
                    name: 'Maintenance Costs',
                    data: response.chartData
                }]);
                chart.updateOptions({
                    xaxis: {
                        categories: response.chartLabels
                    }
                });

                // Clear the current table body
                $('#maintenanceRecordsTableBody').empty();

                // Populate the table with new filtered data
                response.maintenances.forEach(function(record) {
                    var newRow = `
                        <tr>
                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">${record.houseNo}</td>
                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">${record.maintenanceDate}</td>
                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">${record.maintenanceDescription}</td>
                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">$${record.amount.toFixed(2)}</td>
                        </tr>
                    `;
                    $('#maintenanceRecordsTableBody').append(newRow);
                });
            },
            error: function(error) {
                console.log('Error filtering data:', error);
            }
        });
    });
});
