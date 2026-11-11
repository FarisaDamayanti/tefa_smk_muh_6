@props([
    'title' => 'Hi, Guru',
    'subtitle' => 'Selamat datang di dashboard',
    'avatar' => null,
])

<div class="card border-0 shadow-sm breadcrumb-card"
    style="background: linear-gradient(135deg, #4facfe, #5b7cfa); border-radius: 14px;">
    <div class="card-body d-flex align-items-center gap-3 text-white p-3 p-md-4">

        {{-- Avatar --}}
        <div class="flex-shrink-0">
            <img id="breadcrumbProfileImage" src="{{ $avatar ?? asset('assets/images/default-user.jpeg') }}"
                class="rounded-circle border breadcrumb-avatar" style="object-fit: cover;">
        </div>

        {{-- Text --}}
        <div class="flex-grow-1">
            <h4 class="fw-semibold mb-1 text-white fs-5 fs-md-4">{{ $title }}</h4>
            <p class="mb-0 opacity-90 breadcrumb-subtitle">{{ $subtitle }}</p>
        </div>

    </div>
</div>
