@props([
    'nama' => 'Sayudi Oke',
    'tahun' => '2023–2024',
    'mapel' => [],
    'avatar' => null,
])

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <h5 class="fw-semibold mb-3">Wali Kelas</h5>

        <div class="d-flex align-items-center gap-3">
            <img src="{{ $avatar ?? asset('assets/images/default-user.jpeg') }}" alt="wali" class="rounded-circle"
                width="60" height="60" style="object-fit: cover;"
                onerror="this.src='{{ asset('assets/images/default-user.jpeg') }}'">

            <div>
                <div class="fw-semibold">{{ $nama }}</div>
                <div class="text-muted small">Tahun Ajaran {{ $tahun }}</div>

                <div class="mt-2 d-flex gap-2 flex-wrap">
                    @forelse ($mapel as $item)
                        <span class="badge bg-primary-subtle text-primary">
                            {{ $item }}
                        </span>
                    @empty
                        <span class="badge bg-secondary-subtle text-secondary">
                            Belum ada mapel
                        </span>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
