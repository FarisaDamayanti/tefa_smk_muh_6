@props([
    'nama' => 'Sayudi Oke',
    'tahun' => '2023–2024',
    'mapel' => [],
    'avatar' => null,
])

<div class="card wali-card border-0">
    <div class="card-body p-4">
        <h4 class="fw-bold ">Wali Kelasmu</h4>

        <div class="d-flex align-items-center gap-4">
            <img
                src="{{ $avatar ?? asset('assets/images/default-user.jpeg') }}"
                class="rounded-circle"
                width="70"
                height="70"
                style="object-fit: cover;"
            >

            <div>
                <h5 class="fw-bold mb-1">{{ $nama }}</h5>
                <p class="fw-bold mb-3">Tahun Ajaran {{ $tahun }}</p>

                <div class="d-flex gap-2 flex-wrap">
                    @foreach ($mapel as $item)
                        <span class="badge bg-primary-subtle text-primary px-3 py-2">
                            {{ $item }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
