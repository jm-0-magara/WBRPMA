document.addEventListener('DOMContentLoaded', function() {
    const tenantSelect = document.getElementById('deliveryStatusSelect');
    const tenantName = document.querySelector('.card-body h6 a');
    const tenantImg = document.querySelector('.card-body img');
    const tenantInfo = document.querySelector('.card-body p');
    const tableBody = document.querySelector('table tbody');

    tenantSelect.addEventListener('change', function() {
        const tenantId = this.value;

        fetch(`/tenants/${tenantId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Update the card with tenant details
                tenantName.textContent = data.names;
                tenantImg.src = data.img;
                tenantInfo.textContent = data.phoneNo;

                // Clear the table
                tableBody.innerHTML = '';

                // Populate the table with tenant details
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-3.5 py-2.5">${data.names}</td>
                    <td class="px-3.5 py-2.5">${data.phoneNo}</td>
                    <td class="px-3.5 py-2.5">${data.houseNo}</td>
                    <td class="px-3.5 py-2.5">
                        <span class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border ${data.isPaid ? 'bg-green-100 text-green-500' : 'bg-red-100 text-red-500'}">${data.isPaid ? 'Paid' : 'Unpaid'}</span>
                    </td>
                    <td class="px-3.5 py-2.5">
                        <div class="flex justify-end gap-2">
                            <a href="#" class="text-slate-500 hover:text-custom-500" data-tenant-id="${data.id}">
                                <i data-lucide="eye" class="inline-block size-3"></i>
                            </a>
                            <a href="#" class="text-slate-500 hover:text-custom-500">
                                <i data-lucide="pencil" class="size-4"></i>
                            </a>
                            <a href="#" class="text-slate-500 hover:text-custom-500">
                                <i data-lucide="trash-2" class="size-4"></i>
                            </a>  
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    });
});