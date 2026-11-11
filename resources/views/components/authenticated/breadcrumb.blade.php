<div class="card header-gradient shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-8 col-md-10">
                <h3 class="fw-bold text-white mb-8 fs-5 fs-md-3"><b>{{ $title ?? 'Halaman' }}</b></h3>
                <h5 class="fw-bold text-white mb-8 fs-6 fs-md-5">
                    <b>{{ $subtitle ?? '' }}</b>
                </h5>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            {!! $breadcrumb ?? '' !!}
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-4 col-md-2">
                <div class="text-center mb-n3">
                    <img src="{{ asset('admin_assets/dist/images/breadcrumb/ChatBc.png') }}" class="img-fluid mb-n4 breadcrumb-trail-img">
                </div>
            </div>
        </div>
    </div>
</div>
