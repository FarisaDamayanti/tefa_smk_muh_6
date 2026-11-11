<script>
    $(function () {
        if ($('#exam-additional-rules').length) {
            $('#exam-additional-rules').summernote({
                blockquoteBreakingLevel: 2,
                height: 200,
                placeholder: 'Masukkan Peraturan Tambahan disini..',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph', 'height']],
                    ['table', ['table']],
                    ['link', ['link']],
                    ['picture', ['picture']],
                    ['video', ['video']],
                    ['codeview', ['codeview']],
                    ['help', ['help']]
                ]
            });
        }
    });
</script>
