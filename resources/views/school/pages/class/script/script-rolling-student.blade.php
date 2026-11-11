<script>
    $(document).ready(function() {
        // Handle move to right
        $('#move-to-right').click(function() {
            const $rightTbody = $('#right-table tbody');
            clearIfEmptyState($rightTbody);

            $('#left-table tbody tr').each(function () {
                const $checkbox = $(this).find('.form-check-input');
                if ($checkbox.is(':checked')) {
                    $checkbox.prop('checked', false);
                    $rightTbody.append($(this));
                }
            });
        });

        // Handle move to left
        $('#move-to-left').click(function() {
            const $leftTbody = $('#left-table tbody');
            clearIfEmptyState($leftTbody);

            $('#right-table tbody tr').each(function () {
                const $checkbox = $(this).find('.form-check-input');
                if ($checkbox.is(':checked')) {
                    $checkbox.prop('checked', false);
                    $leftTbody.append($(this));
                }
            });
        });

        // Handle save button
        $('#save-button').click(function() {
            var addStudents = [];
            var removeStudents = [];

            $('.empty-tr').remove();
            $('#right-table tbody tr').each(function() {
                addStudents.push($(this).data('id'));
            });

            $('#left-table tbody tr').each(function() {
                removeStudents.push($(this).data('id'));
            });

            $('#add-students').val(addStudents.join(','));
            $('#remove-students').val(removeStudents.join(','));

            $('#save-form').submit();
        });
    });

    function clearIfEmptyState($tbody) {
        const emptyText = 'Siswa belum ditambahkan';

        if ($tbody.text().trim().includes(emptyText)) {
            $tbody.empty();
        }
    }
</script>
