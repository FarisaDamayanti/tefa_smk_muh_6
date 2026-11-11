<div class="modal fade" id="flagged-warning-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
data-bs-keyboard="false">
    <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-body p-4">
                <div class="d-flex flex-column align-items-center text-center">
                    <div class="mb-3">
                        <svg width="73" height="73" viewBox="0 0 73 73" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="4" width="64.5523" height="64.5523" rx="32.2761" fill="#FFAE1F" />
                            <rect x="2" y="2" width="68.5523" height="68.5523" rx="34.2761" stroke="#FFAE1F"
                                stroke-opacity="0.5" stroke-width="4" />
                            <g clip-path="url(#clip0_8905_9032)">
                                <path
                                    d="M40.5625 45.7307V50.8216C40.5625 51.2155 40.4212 51.5564 40.1384 51.8443C39.8557 52.1322 39.5209 52.2761 39.134 52.2761H33.4197C33.0328 52.2761 32.6979 52.1322 32.4152 51.8443C32.1325 51.5564 31.9911 51.2155 31.9911 50.8216V45.7307C31.9911 45.3367 32.1325 44.9958 32.4152 44.7079C32.6979 44.4201 33.0328 44.2761 33.4197 44.2761H39.134C39.5209 44.2761 39.8557 44.4201 40.1384 44.7079C40.4212 44.9958 40.5625 45.3367 40.5625 45.7307ZM41.2322 21.7307L40.6072 39.1852C40.5923 39.5792 40.4398 39.9201 40.1496 40.2079C39.8594 40.4958 39.5209 40.6398 39.134 40.6398H33.4197C33.0328 40.6398 32.6942 40.4958 32.404 40.2079C32.1139 39.9201 31.9613 39.5792 31.9465 39.1852L31.3215 21.7307C31.3066 21.3367 31.4368 20.9958 31.7121 20.7079C31.9874 20.4201 32.3185 20.2761 32.7054 20.2761H39.8482C40.2351 20.2761 40.5662 20.4201 40.8415 20.7079C41.1168 20.9958 41.2471 21.3367 41.2322 21.7307Z"
                                    fill="white" />
                            </g>
                            <defs>
                                <clipPath id="clip0_8905_9032">
                                    <rect width="10" height="32" fill="white"
                                        transform="translate(31.2773 20.2761)" />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>

                    <h4><b>Apakah kamu yakin ingin <br> mengakhiri ujian saat ini?</b></h4>
                    <p class="fs-5">Kamu masih memiliki soal yang ditandai ragu-ragu. <br> Periksa kembali sebelum mengirim ujian.</p>

                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <button type="button" class="btn btn-outline-muted" data-bs-dismiss="modal">
                            Periksa Kembali
                        </button>
                        <button type="button" class="btn btn-warning" id="submit-flagged-exam">
                            Ya, Saya yakin
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
