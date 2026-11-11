<script>
    $(document).ready(function() {
        $('.btn-detail').on('click', function() {
            var name = $(this).data('name');
            var classroom = $(this).data('classroom');
            var date = $(this).data('date');
            var status = $(this).data('status');
            var statusColor = $(this).data('status-color');
            var proof = $(this).data('proof');
            $('#name-detail').text(name);
            $('#classroom-detail').text(classroom);
            $('#status-detail').text(status);
            $('#status-detail').attr('class', 'badge bg-light-' + statusColor + ' text-' + statusColor);
            $('#date-detail').text(date);
            $('#proof-detail').attr('src', proof);
            $('#modal-detail').modal('show');
        });
    });
</script>
