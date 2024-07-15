$(document).ready(function() {
    // Handle click event on eye icons
    $('a[data-tenant-id]').on('click', function(e) {
        e.preventDefault();
        
        updateTenantDetails($(this));
    });

    // Handle change event on dropdown
    $('#clientsSelect').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        updateTenantDetails(selectedOption);
    });

    // Function to update tenant details
    function updateTenantDetails(element) {
        var tenantImg = element.data('tenant-img');
        var tenantName = element.data('tenant-name');
        var tenantPhone = element.data('tenant-phone');
        var tenantHouseNo = element.data('tenant-houseno');
        var tenantIsPaid = element.data('tenant-ispaid');
        var tenantEmail = element.data('tenant-email');
        var tenantDateAdded = element.data('tenant-dateadded');
        var tenantIDNO = element.data('tenant-idno');
        
        $('#tenant-details img').attr('src', tenantImg);
        $('#tenant-details .tenant-name').text(tenantName);
        $('#tenant-details .tenant-status').text(tenantIsPaid);
        $('#tenant-details .tenant-phone').text(tenantPhone);
        $('#tenant-details .tenant-houseno').text(tenantHouseNo);
        $('#tenant-details .tenant-email').text(tenantEmail);
        $('#tenant-details .tenant-dateadded').text(tenantDateAdded);
        $('#tenant-details .tenant-idno').text(tenantIDNO);
    }
});