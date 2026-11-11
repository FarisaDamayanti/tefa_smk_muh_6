<script>
    $('.btn-detail').click(function() {
        let name = $(this).data('name');
        let email = $(this).data('email');
        let status = $(this).data('status');
        let date = $(this).data('date');
        let position = $(this).data('position');
        let address = $(this).data('address');
        let needs = $(this).data('needs');
        let phone_number = $(this).data('phone_number');

        $('#detail-name').text(name);
        $('#detail-email').text(email);
        $('#detail-phone_number').text(phone_number);
        $('#detail-status').text(status);
        $('#detail-date').text(date);
        $('#detail-position').text(position);
        $('#detail-needs').text(needs);

        if (status == 'individual') {
            $("#address").hide();
        } else {
            $("#address").show();
            $('#detail-address').text(address);
        }

        $('#modal-detail').modal('show');
    });
</script>
