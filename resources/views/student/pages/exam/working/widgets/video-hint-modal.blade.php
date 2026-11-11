 <div class="modal fade" id="modalVideoHint" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-lg">
         <div class="modal-content bg-white border-0 shadow-lg p-2">

             <div class="border border-3 border-white rounded-4 overflow-hidden">

                 <div class="bg-light">
                     <div class="ratio ratio-16x9">
                         <video class="w-100 h-100" id="video-hint" autoplay loop muted disablepictureinpicture preload="auto">
                             <source src="{{ asset('file/media/hint-matching.mp4') }}" type="video/mp4">
                             Browser does not support video.
                         </video>
                     </div>
                 </div>

                 <div
                     class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 p-3 p-lg-4 bg-white">
                     <div>
                         <h4 class="text-primary fw-bold mb-1">Soal Penjodohan</h4>
                         <p class="text-primary mb-0">Petunjuk Pengerjaan Soal Menjodohkan</p>
                     </div>

                     <button type="button" class="btn btn-primary rounded-4 px-4 py-3 fw-semibold"
                         data-bs-dismiss="modal">
                         Kembali ke Halaman Ujian
                     </button>
                 </div>

             </div>

         </div>
     </div>
 </div>
