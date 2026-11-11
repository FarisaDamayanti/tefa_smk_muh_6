<div class="modal fade" id="reading-the-rules-confirmation-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 430px;">
        <div class="modal-content rounded-4 border-0">
            <form id="form-start-exam">
                <div class="modal-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="mb-3">
    
                            <svg width="81" height="81" viewBox="0 0 81 81" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="40.155" cy="40.155" r="36.155" fill="#FFAE1F" />
                                <circle cx="40.155" cy="40.155" r="38.155" stroke="#FFAE00" stroke-opacity="0.5"
                                    stroke-width="4" />
                                <path
                                    d="M45.1513 30.3392C45.1513 28.8305 44.6512 27.6199 43.6511 26.7077C42.651 25.7954 41.325 25.3392 39.6731 25.3392C38.6547 25.3392 37.7333 25.5589 36.9087 25.9982C36.0842 26.4375 35.3727 27.0775 34.7744 27.9182C34.2125 28.7252 33.4483 29.1905 32.4819 29.314C31.5155 29.4375 30.6819 29.1827 29.9809 28.5498C29.4893 28.0936 29.2175 27.5322 29.1655 26.8655C29.1136 26.1989 29.28 25.5673 29.6649 24.9708C30.7886 23.2866 32.22 21.9799 33.9589 21.0508C35.6979 20.1217 37.6026 19.6564 39.6731 19.655C43.0794 19.655 45.8452 20.6199 47.9705 22.5498C50.0957 24.4796 51.1577 27.0059 51.1562 30.1287C51.1562 31.7076 50.8226 33.1287 50.1554 34.3919C49.4882 35.655 48.2591 37.1462 46.4682 38.8655C45.1689 40.0936 44.291 41.0501 43.8344 41.735C43.3779 42.4199 43.0794 43.2003 42.939 44.076C42.7985 44.9182 42.4382 45.6199 41.8581 46.1813C41.2779 46.7427 40.5847 47.0234 39.7785 47.0234C38.9722 47.0234 38.279 46.7518 37.6989 46.2087C37.1187 45.6655 36.8287 44.9897 36.8287 44.1813C36.8287 42.8129 37.1272 41.5589 37.7241 40.4192C38.3211 39.2796 39.322 38.0775 40.7266 36.8129C42.5176 35.234 43.7031 34.015 44.2832 33.1561C44.8634 32.2971 45.1527 31.3582 45.1513 30.3392ZM39.6731 59.655C38.5143 59.655 37.5226 59.243 36.698 58.4192C35.8735 57.5953 35.4605 56.6037 35.4591 55.4445C35.4577 54.2852 35.8707 53.2943 36.698 52.4718C37.5254 51.6494 38.5171 51.2367 39.6731 51.2339C40.8292 51.2311 41.8216 51.6438 42.6503 52.4718C43.4791 53.2999 43.8913 54.2908 43.8871 55.4445C43.8829 56.5981 43.4706 57.5897 42.6503 58.4192C41.83 59.2487 40.8376 59.6606 39.6731 59.655Z"
                                    fill="white" />
                            </svg>
    
                        </div>
    
                        <h4 class="mb-3"><b>Apakah kamu sudah membaca <br>
                            peraturan ujian?</b></h4>
    
                        <p class="mb-2">Jika sudah membaca peraturan, silahkan masukkan token untuk memulai ujian</p>

                        <div class="text-start form-group mb-3" style="width: 100%">
                            <label for="token" class="fw-semibold">Token <span class="text-danger">*</span></label>
                            <input type="text" class="form-control my-2" id="token" name="token" placeholder="Masukkan token">
                            <span id="error-token" class="text-danger"></span>
                        </div>
    
                        <div class="d-flex justify-content-end gap-2 pt-2">
                            <button type="button" class="btn btn-outline-muted" data-bs-dismiss="modal">
                                Baca Peraturan
                            </button>
                            <button id="start-button" type="submit" class="btn btn-warning px-4">
                                Mulai Ujian
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
