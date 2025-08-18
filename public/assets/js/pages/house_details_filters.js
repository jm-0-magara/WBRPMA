// A new JavaScript file to handle the filtering logic for the house details page.
document.addEventListener("DOMContentLoaded", function() {

    // Check if the necessary elements exist before proceeding
    if (document.getElementById("paymentTypeFilter") && document.getElementById("paymentsTableBody")) {

        // Get filter elements from the DOM
        const paymentTypeFilter = document.getElementById("paymentTypeFilter");
        const startDateFilter = document.getElementById("startDateFilter");
        const endDateFilter = document.getElementById("endDateFilter");
        const paymentsTableBody = document.getElementById("paymentsTableBody");
        const noPaymentsMessage = document.getElementById("noPaymentsMessage");

        // Function to fetch and update payment data
        const fetchAndUpdatePayments = async () => {
            // Get the current filter values
            const paymentType = paymentTypeFilter.value;
            const startDate = startDateFilter.value;
            const endDate = endDateFilter.value;

            // Construct the URL for the AJAX request
            let url = filterPaymentsRoute.replace(':houseNo', houseNo);
            url += `?paymentType=${paymentType}&startDate=${startDate}&endDate=${endDate}`;
            
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                
                // Update the payments table
                updatePaymentsTable(data.payments);
                
                // Update the payments chart with the new data
                updatePaymentsChart(data.chartData);

            } catch (error) {
                console.error('Error fetching payments:', error);
            }
        };
        
        // Function to update the table with new data
        const updatePaymentsTable = (payments) => {
            paymentsTableBody.innerHTML = ''; // Clear the current table body
            if (payments.length === 0) {
                noPaymentsMessage.classList.remove('hidden');
                return;
            }
            noPaymentsMessage.classList.add('hidden');
            
            payments.forEach(payment => {
                const row = document.createElement('tr');
                row.className = 'border-t border-slate-200 dark:border-zink-500';
                
                row.innerHTML = `
                    <td class="px-4 py-2">${payment.paymentID}</td>
                    <td class="px-4 py-2">Ksh. ${payment.amount.toLocaleString()}</td>
                    <td class="px-4 py-2">${payment.paymentType}</td>
                    <td class="px-4 py-2">${payment.paymentMethod}</td>
                    <td class="px-4 py-2">${new Date(payment.timePaid).toLocaleDateString()}</td>
                    <td class="px-4 py-2">${payment.narration || 'N/A'}</td>
                   
                `;
                paymentsTableBody.appendChild(row);
            });
        };
        
        // Function to update the chart with new data
        const updatePaymentsChart = (chartData) => {
            // Re-render the chart with the new data
            if (window.chartPayment) {
                // ApexCharts provides a method to update the series data
                window.chartPayment.updateSeries([{ data: chartData }]);
            }
        };


        // Add event listeners to the filter inputs
        // The apply button is used to trigger the filter.
        document.getElementById("applyFilterBtn").addEventListener('click', fetchAndUpdatePayments);

        // Initial fetch and render of payments on page load
        // This will populate the table and ensure the chart is rendered with initial data.
        fetchAndUpdatePayments();
    }
});
