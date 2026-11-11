<div class="classroom-card card shadow-sm rounded-4 p-4 h-100 d-flex flex-column"
    style="border: 1px solid #e5e7eb; border-radius: 16px; transition: all 0.3s ease;">
    <h5 class="fw-semibold fs-4">
        {{ $classroom ?? 'Kelas' }}
    </h5>
    <h5 class="fw-semibold fs-4">
        {{ $subject ?? 'Mata Pelajaran' }}
    </h5>
    <div class="d-flex text-muted align-items-center gap-3 flex-wrap mt-2 justify-content-between m-1">
        @if ($attributes->has('teacher') && filled($attributes->get('teacher')))
            <span class="classroom-card__teacher">
                {{ $attributes->get('teacher') }}
            </span>
            <span class="classroom-card__sep">|</span>
        @endif
            <span class="classroom-card__year">
                {{ $schoolYear ?? '-' }}
            </span>
            @if ($attributes->has('total-students'))
                <span class="classroom-card__sep">|</span>
                <span class="classroom-card__students">
                    {{ $attributes->get('total-students') }} <span>Siswa</span>
                </span>
            @endif
    </div>

    <div class="mt-auto">
        <a href="{{ $targetUrl ?? '#' }}" class="btn btn-primary w-100">
            Masuk Kelas
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.classroom-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
                this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.1)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    });
</script>
