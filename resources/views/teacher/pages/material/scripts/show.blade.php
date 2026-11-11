  <script>
      document.addEventListener('DOMContentLoaded', async function() {
          const moduleId = '{{ $module->id }}'; 
          // Icon mapping
          const icons = {
              'pdf': 'fas fa-file-pdf',
              'word': 'fas fa-file-word',
              'excel': 'fas fa-file-excel',
              'ppt': 'fas fa-file-powerpoint',
              'youtube': 'fab fa-youtube',
              'other': 'fas fa-link',
              'editor': 'fas fa-edit',
              'google-drive': 'fab fa-google-drive'
          };

          // Format labels
          const formatLabels = {
              'pdf': 'PDF',
              'word': 'Word',
              'excel': 'Excel',
              'ppt': 'PowerPoint',
              'youtube': 'YouTube Video',
              'other': 'Link',
              'editor': 'Rich Text'
          };

          try {
              const response = await fetch(`/api/teacher/modules/${moduleId}`, {
                  method: 'GET',
                  headers: {
                      'Accept': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                          'content')
                  }
              });

              const result = await response.json();

              if (!response.ok || !result.data) {
                  showError('Gagal memuat data materi');
                  return;
              }

              const module = result.data;

              // Set judul di header minimal
              document.getElementById('material-title').textContent = module.title ?? 'Preview Materi';

              // ======================
              // TEXT EDITOR (QUILL)
              // ======================
              if (module.link_type === 'editor' && module.content) {
                  const cleanContent = module.content.replace(/<p><br><\/p>/g, '').trim();
                  if (cleanContent) {
                      document.getElementById('material-content').innerHTML = `
            <div class="file-preview-container h-100">
                <div class="ql-container ql-snow" style="border: none;">
                    <div class="ql-editor" style="min-height: auto; padding: 20px;">
                        ${cleanContent}
                    </div>
                </div>
            </div>
        `;
                      return;
                  }
              }

              // ======================
              // YOUTUBE / FILE / LINK
              // ======================
              if (module.link) {
                  const linkType = module.link_type || 'other';
                  let previewContent = '';

                  // ======================
                  // 1. YOUTUBE
                  // ======================
                  if (linkType === 'youtube') {
                      const videoId = extractYoutubeId(module.link);
                      if (videoId) {
                          previewContent = getYouTubePreview(videoId);
                      } else {
                          previewContent = getLinkFallback(module.link, 'YouTube');
                      }
                  }

                  // ======================
                  // 2. GOOGLE DRIVE LINKS
                  // ======================
                  else if (isGoogleDriveLink(module.link)) {
                      previewContent = getGoogleDrivePreviewAuto(module.link, linkType);
                  }

                  // ======================
                  // 3. DIRECT PDF LINKS
                  // ======================
                  else if (linkType === 'pdf' || module.link.toLowerCase().endsWith('.pdf')) {
                      previewContent = getPDFPreview(module.link);
                  }

                  // ======================
                  // 4. OFFICE FILES
                  // ======================
                  else if (['word', 'excel', 'ppt'].includes(linkType) ||
                      module.link.match(/\.(docx?|xlsx?|pptx?)$/i)) {
                      previewContent = getOfficeFilePreview(module.link, linkType);
                  }

                  // ======================
                  // 5. OTHER LINKS
                  // ======================
                  else {
                      previewContent = getLinkFallback(module.link, 'External');
                  }

                  // ======================
                  // BUILD FINAL HTML
                  // ======================
                  const html = `
                    <div class="h-100">
                        ${previewContent}
                    </div>
                `;

                  document.getElementById('material-content').innerHTML = html;

                  // Setup iframe event handlers
                  setupIframeHandlers();

                  return;
              }

              // ======================
              // NO CONTENT
              // ======================
              document.getElementById('material-content').innerHTML = `
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="text-center">
                        <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Materi Kosong</h5>
                        <p class="text-muted">Tidak ada konten yang dapat ditampilkan</p>
                    </div>
                </div>
            `;

          } catch (error) {
              console.error('Error:', error);
              showError('Terjadi kesalahan saat memuat materi.');
          }
      });

      // ======================
      // NAVIGATION FUNCTION
      // ======================
      function goBack() {
          if (document.referrer) {
              const referrerUrl = new URL(document.referrer);
              const referrerPath = referrerUrl.pathname;

              if (referrerPath.includes('/teacher/e-learning/')) {
                  const pathSegments = referrerPath.split('/');
                  const eLearningIndex = pathSegments.indexOf('e-learning');

                  if (eLearningIndex !== -1 && pathSegments.length > eLearningIndex + 2) {
                      const subjectId = pathSegments[eLearningIndex + 1];
                      const classroomId = pathSegments[eLearningIndex + 2];

                      window.location.href = `/teacher/e-learning/${subjectId}/${classroomId}`;
                      return;
                  }
              }
          }

          const lastSubjectId = sessionStorage.getItem('last_subject_id');
          const lastClassroomId = sessionStorage.getItem('last_classroom_id');

          if (lastSubjectId && lastClassroomId) {
              window.location.href = `/teacher/e-learning/${lastSubjectId}/${lastClassroomId}`;
              return;
          }

          window.location.href = '/teacher/e-learning';
      }

      // ======================
      // PREVIEW FUNCTIONS
      // ======================

      function getYouTubePreview(videoId) {
          return `
            <div class="responsive-iframe-container h-100">
                <iframe 
                    src="https://www.youtube-nocookie.com/embed/${videoId}?rel=0&modestbranding=1&autoplay=1"
                    title="YouTube video player"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    loading="lazy"
                ></iframe>
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Memuat video...</p>
                </div>
            </div>
        `;
      }

      function isGoogleDriveLink(url) {
          return url.includes('drive.google.com') ||
              url.includes('docs.google.com');
      }

      function getGoogleDrivePreviewAuto(url, linkType) {
          const fileType = getDriveFileType(url, linkType);
          const driveId = extractGoogleDriveId(url);

          if (driveId) {
              return `
                <div class="responsive-iframe-container h-100">
                    <iframe 
                        src="https://drive.google.com/file/d/${driveId}/preview"
                        frameborder="0"
                        allowfullscreen
                        loading="lazy"
                    ></iframe>
                    <div class="loading-spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Memuat file dari Google Drive...</p>
                    </div>
                </div>
                <div class="preview-alert mt-2">
                    <i class="fas fa-info-circle text-warning me-2"></i>
                    <small class="fst-italic">Jika file tidak muncul, pastikan file dapat diakses publik.</small>
                </div>
            `;
          }

          // Fallback untuk Google Docs/Sheets/Slides
          return getGoogleDriveDocsPreview(url, fileType);
      }

      function getGoogleDriveDocsPreview(url, fileType) {
          const typeConfig = {
              'word': {
                  icon: 'fas fa-file-word',
                  label: 'Google Docs'
              },
              'excel': {
                  icon: 'fas fa-file-excel',
                  label: 'Google Sheets'
              },
              'ppt': {
                  icon: 'fas fa-file-powerpoint',
                  label: 'Google Slides'
              },
              'unknown': {
                  icon: 'fab fa-google-drive',
                  label: 'Google Drive File'
              }
          };

          const config = typeConfig[fileType] || typeConfig.unknown;

          return `
            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                <div class="text-center mb-4">
                    <i class="${config.icon} fa-5x text-primary mb-3"></i>
                    <h4>${config.label}</h4>
                    <p class="text-muted">File disimpan di Google Drive</p>
                </div>
                <div class="drive-actions">
                    <a href="${url}" target="_blank" class="btn btn-primary">
                        <i class="fab fa-google-drive me-2"></i>Buka di Google Drive
                    </a>
                    <button onclick="copyToClipboard('${url}')" class="btn btn-outline-secondary">
                        <i class="fas fa-copy me-2"></i>Salin Link
                    </button>
                </div>
            </div>
        `;
      }

      function getPDFPreview(url) {
          // Gunakan Google Docs Viewer untuk PDF
          const viewerUrl = `https://docs.google.com/viewer?url=${encodeURIComponent(url)}&embedded=true&hl=id`;

          return `
            <div class="responsive-iframe-container h-100">
                <iframe 
                    src="${viewerUrl}"
                    frameborder="0"
                    allowfullscreen
                    loading="lazy"
                ></iframe>
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Memuat PDF...</p>
                </div>
            </div>
        `;
      }

      function getOfficeFilePreview(url, linkType) {
          const typeLabels = {
              'word': 'Dokumen Word',
              'excel': 'Spreadsheet Excel',
              'ppt': 'Presentasi PowerPoint'
          };

          const fileTypeLabel = typeLabels[linkType] || 'Office Document';

          // Office Online Viewer
          const officeUrl = `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(url)}`;

          return `
            <div class="responsive-iframe-container h-100">
                <iframe 
                    src="${officeUrl}"
                    frameborder="0"
                    allowfullscreen
                    loading="lazy"
                ></iframe>
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Memuat ${fileTypeLabel}...</p>
                </div>
            </div>
            ${isGoogleDriveLink(url) ? `
                                                <div class="preview-alert mt-2">
                                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                                    <small class="fst-italic">File Office di Google Drive memerlukan akses publik untuk preview.</small>
                                                </div>
                                            ` : ''}
        `;
      }

      function getLinkFallback(url, type) {
          return `
            <div class="d-flex flex-column align-items-center justify-content-center h-100" style="margin-top: 220px;">
                <div class="text-center mb-4">
                    <i class="fas fa-external-link-alt fa-5x text-muted mb-3"></i>
                    <h4>Tautan ${type}</h4>
                    <p class="text-muted">Konten hanya dapat diakses melalui tautan eksternal</p>
                    <div class="link-display mt-3">
                        <small>${truncateUrl(url, 50)}</small>
                    </div>
                </div>
                <div class="drive-actions">
                    <a href="${url}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-external-link-alt me-2"></i>Buka di Tab Baru
                    </a>
                    <button onclick="copyToClipboard('${url}')" class="btn btn-outline-secondary">
                        <i class="fas fa-copy me-2"></i>Salin Link
                    </button>
                </div>
            </div>
        `;
      }

      // ======================
      // HELPER FUNCTIONS
      // ======================

      function getDriveFileType(url, linkType) {
          if (linkType && linkType !== 'other') return linkType;

          // Deteksi dari URL atau ekstensi
          if (url.includes('.pdf') || url.toLowerCase().includes('pdf')) return 'pdf';
          if (url.includes('.doc') || url.toLowerCase().includes('doc')) return 'word';
          if (url.includes('.xls') || url.toLowerCase().includes('xls')) return 'excel';
          if (url.includes('.ppt') || url.toLowerCase().includes('ppt')) return 'ppt';

          // Deteksi dari Google Drive URL pattern
          if (url.includes('/document/')) return 'word';
          if (url.includes('/spreadsheets/')) return 'excel';
          if (url.includes('/presentation/')) return 'ppt';

          return 'unknown';
      }

      function setupIframeHandlers() {
          // Setup event handlers untuk semua iframe
          document.querySelectorAll('.responsive-iframe-container iframe').forEach(iframe => {
              iframe.onload = function() {
                  const spinner = iframe.parentElement.querySelector('.loading-spinner');
                  if (spinner) {
                      spinner.style.display = 'none';
                  }
              };

              iframe.onerror = function() {
                  const spinner = iframe.parentElement.querySelector('.loading-spinner');
                  const container = iframe.parentElement;

                  if (spinner) {
                      spinner.style.display = 'none';
                  }

                  // Show error message
                  const errorHtml = `
                    <div class="preview-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h6 class="mt-2">Preview Gagal Dimuat</h6>
                        <p class="text-muted small mb-3">File tidak dapat diakses untuk preview.</p>
                        <a href="${iframe.src}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt me-1"></i> Buka Secara Langsung
                        </a>
                    </div>
                `;

                  iframe.style.display = 'none';
                  container.insertAdjacentHTML('beforeend', errorHtml);
              };

              // Auto-hide spinner setelah 10 detik (timeout)
              setTimeout(() => {
                  const spinner = iframe.parentElement.querySelector('.loading-spinner');
                  if (spinner && iframe.contentDocument && iframe.contentDocument.readyState ===
                      'loading') {
                      spinner.style.display = 'none';
                  }
              }, 10000);
          });
      }

      function showError(message) {
          document.getElementById('material-content').innerHTML = `
            <div class="d-flex justify-content-center align-items-center h-100">
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    ${message}
                </div>
            </div>
        `;
      }

      function extractYoutubeId(url) {
          if (!url) return null;

          const patterns = [
              /(?:youtube\.com\/watch\?v=)([^&\s]+)/,
              /(?:youtube\.com\/embed\/)([^?\s]+)/,
              /(?:youtube\.com\/v\/)([^?\s]+)/,
              /(?:youtu\.be\/)([^?\s]+)/,
              /(?:youtube\.com\/live\/)([^?\s]+)/,
              /(?:youtube\.com\/shorts\/)([^?\s]+)/
          ];

          for (let pattern of patterns) {
              const match = url.match(pattern);
              if (match && match[1]) {
                  return match[1].split('?')[0].split('&')[0];
              }
          }

          if (url.includes('youtu.be/')) {
              const parts = url.split('youtu.be/');
              if (parts[1]) {
                  return parts[1].split('?')[0].split('&')[0];
              }
          }

          return null;
      }

      function extractGoogleDriveId(url) {
          const patterns = [
              /\/d\/([a-zA-Z0-9_-]+)/,
              /id=([a-zA-Z0-9_-]+)/,
              /\/file\/d\/([a-zA-Z0-9_-]+)/
          ];

          for (let pattern of patterns) {
              const match = url.match(pattern);
              if (match && match[1]) {
                  return match[1];
              }
          }

          return null;
      }

      function truncateUrl(url, maxLength) {
          if (url.length <= maxLength) return url;
          return url.substring(0, maxLength) + '...';
      }

      function copyToClipboard(text) {
          navigator.clipboard.writeText(text).then(() => {
              const btn = event.target.closest('button');
              const originalHTML = btn.innerHTML;
              btn.innerHTML = '<i class="fas fa-check me-2"></i>Tersalin!';
              btn.classList.remove('btn-outline-secondary');
              btn.classList.add('btn-outline-success');

              setTimeout(() => {
                  btn.innerHTML = originalHTML;
                  btn.classList.remove('btn-outline-success');
                  btn.classList.add('btn-outline-secondary');
              }, 2000);
          }).catch(err => {
              console.error('Gagal menyalin: ', err);
          });
      }
  </script>
