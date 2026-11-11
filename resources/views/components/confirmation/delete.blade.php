<div class="modal fade" id="{{ $id }}-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 430px;">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-body p-4">
                <form id="{{ $id }}-form" class="d-flex flex-column align-items-center text-center">
                    <div class="mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="73" height="73" viewBox="0 0 73 73" fill="none">
                            <path d="M36.1562 0C16.1869 0 0 16.1869 0 36.1562C0 56.1256 16.1869 72.3125 36.1562 72.3125C56.1256 72.3125 72.3125 56.1256 72.3125 36.1562C72.3125 16.1869 56.1256 0 36.1562 0ZM36.1562 17.1585C36.8939 17.1585 37.6013 17.4515 38.1229 17.9731C38.6445 18.4947 38.9375 19.2021 38.9375 19.9397V40.3355C38.9375 41.0732 38.6445 41.7806 38.1229 42.3022C37.6013 42.8238 36.8939 43.1168 36.1562 43.1168C35.4186 43.1168 34.7112 42.8238 34.1896 42.3022C33.668 41.7806 33.375 41.0732 33.375 40.3355V19.9397C33.375 19.2021 33.668 18.4947 34.1896 17.9731C34.7112 17.4515 35.4186 17.1585 36.1562 17.1585ZM38.9375 50.5186V52.3728C38.9375 53.1104 38.6445 53.8178 38.1229 54.3394C37.6013 54.861 36.8939 55.154 36.1562 55.154C35.4186 55.154 34.7112 54.861 34.1896 54.3394C33.668 53.8178 33.375 53.1104 33.375 52.3728V50.5186C33.375 49.781 33.668 49.0736 34.1896 48.552C34.7112 48.0304 35.4186 47.7374 36.1562 47.7374C36.8939 47.7374 37.6013 48.0304 38.1229 48.552C38.6445 49.0736 38.9375 49.781 38.9375 50.5186Z" fill="#FF4848"/>
                        </svg>
                    </div>

                    <h4>{{ $title }}</h4>
                    <p>{{ $description }}</p>

                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <button type="button" class="btn btn-outline-danger"
                            data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger" style="background-color: #FF4848;">
                            Hapus
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
