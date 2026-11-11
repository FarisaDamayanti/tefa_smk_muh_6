<script>
    $('#create-description-task, #edit-description-task').summernote({
        blockquoteBreakingLevel: 2,
        height: 200,
        placeholder: 'Masukkan Deskripsi Tugas disini..',
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
            ['help', ['help']],
            ['insert', ['ul', 'blockquote']]
        ],

        fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact',
            'Lucida Grande', 'Tahoma', 'Times New Roman', 'Verdana'
        ],
        fontNamesIgnoreCheck: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica',
            'Impact', 'Lucida Grande', 'Tahoma', 'Times New Roman', 'Verdana'
        ]
    });
</script>
