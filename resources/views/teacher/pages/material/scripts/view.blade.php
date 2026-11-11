<script>
    $(document).on('click', '.btn-view-material', async function() {
        const moduleId = $(this).data('id');
        
        try {
            Swal.fire({
                title: 'Memuat data...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch(`/api/teacher/modules/${moduleId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const result = await response.json();
            
            if (response.ok && result.data) {
                Swal.close();
                
                const module = result.data;
                
                // Populate modal view
                $('#view-material-modal .modal-title').text(module.title);
                
                if (module.type === 'content') {
                    $('#view-material-modal .modal-body').html(module.content);
                } else {
                    let linkHtml = `
                        <div class="mb-3">
                            <strong>Jenis Konten:</strong> ${module.link_type.toUpperCase()}
                        </div>
                        <div class="mb-3">
                            <strong>Tautan:</strong><br>
                            <a href="${module.link}" target="_blank" class="btn btn-primary">
                                <i class="ti ti-external-link me-2"></i>Buka Tautan
                            </a>
                        </div>
                    `;
                    
                    // Jika youtube, embed video
                    if (module.link_type === 'youtube') {
                        const videoId = extractYoutubeId(module.link);
                        if (videoId) {
                            linkHtml += `
                                <div class="ratio ratio-16x9">
                                    <iframe src="https://www.youtube.com/embed/${videoId}" 
                                            allowfullscreen></iframe>
                                </div>
                            `;
                        }
                    }
                    
                    $('#view-material-modal .modal-body').html(linkHtml);
                }
                
                $('#view-material-modal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: result.meta?.message || 'Gagal mengambil data materi',
                });
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat mengambil data',
            });
        }
    });

    function extractYoutubeId(url) {
        const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
        const match = url.match(regex);
        return match ? match[1] : null;
    }
</script>