<div class="classroom-card card shadow-sm rounded-4 p-4 h-100 d-flex flex-column">
    <h5 class="fw-bold mb-1">
        {{ $classroom['name'] ?? 'Kelas' }}
    </h5>
    <p class="fw-semibold mb-3">
        {{ $subject['name'] ?? 'Mata Pelajaran' }}
    </p>
    @isset($teacher['name'])
    <p class="fw-semibold mb-3">
        Pengajar: {{ $teacher ?? 'Guru' }}
    </p>
    @endisset
    <div class="d-flex text-muted mb-4 align-items-center gap-3">
        <span class="classroom-card__year">
            Tahun {{ $schoolyear ?? '-' }}
        </span>
        @isset($totalstudent)
        <span class="classroom-card__students d-flex align-items-center gap-2">
            <i class="bi bi-people"></i>
            {{ $totalstudent ?? 0 }} Siswa
        </span>
        @endisset
    </div>
    <div class="mt-auto">
        <a href="{{ auth()->user()->hasRole('student')
        ? route('student.e-learning.index', [$classroom['id'], $subject['id']])
        : route('teacher.meeting.index', [$classroom['id'], $subject['id']])
        }}" class="btn btn-primary w-100">
            Masuk Kelas
        </a>

    </div>

</div>
