$(document).ready(function() {
    $('#houseType').on('change', function() {
        var houseType = $(this).val();
        if (houseType) {
            $.ajax({
                url: '/get-house-price/' + houseType,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.price) {
                        $('#housePrice').val(data.price);
                    } else {
                        $('#housePrice').val('');
                    }
                },
                error: function() {
                    $('#housePrice').val('');
                }
            });
        } else {
            $('#housePrice').val('');
        }
    });
});