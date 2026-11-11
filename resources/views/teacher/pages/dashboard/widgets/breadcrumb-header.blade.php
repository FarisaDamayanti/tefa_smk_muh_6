@props([
    'title' => 'Hi, Guru',
    'subtitle' => 'Selamat datang di dashboard',
    'avatar' => null,
])

<div class="card border-0 shadow-sm"
    style="background: linear-gradient(135deg, #4facfe, #5b7cfa); border-radius: 14px; height: 173px;">
    <div class="card-body d-flex align-items-center gap-3 text-white">

        {{-- Avatar --}}
        <div class="flex-shrink-0">
            <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('assets/images/default-user.jpeg') }}"
            class="rounded-circle border" width="120" height="120" style="object-fit: cover;">
        </div>

        {{-- Text --}}
        <div class="flex-grow-1">
            <h4 class="fw-semibold mb-1 text-white">{{ $title }}</h4>
            <p class="mb-0 opacity-90">{{ $subtitle }}</p>
        </div>

    </div>
</div>