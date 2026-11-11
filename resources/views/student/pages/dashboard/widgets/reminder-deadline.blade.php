@php
    $urgentTasks = collect($incomingDeadlineTasks ?? []);
@endphp

<div class="card deadline-card mb-4">
    <div class="deadline-card__header">
        Tugas Mendekati Deadline
    </div>

    @if ($urgentTasks->isEmpty())
        <div class="deadline-card__body">
            <div class="deadline-empty">
                <img src="{{ asset('admin_assets/dist/images/empty/no-task.png') }}" class="deadline-empty__img"
                    alt="empty">

                <div class="deadline-empty__title">Belum Ada Tugas Mendesak</div>
                <p class="deadline-empty__sub">
                    Saat ini tidak ada tugas yang mendekati batas pengumpulan.
                </p>
            </div>
        </div>
    @else
        <div class="p-3">
            @foreach ($urgentTasks->take(3) as $task)
                @php
                    $taskId = data_get($task, 'id');
                    $title = data_get($task, 'title', '-');
                    $deadlineRaw = data_get($task, 'deadline') ?? data_get($task, 'due_date');
                    $deadline = $deadlineRaw ? \Carbon\Carbon::parse($deadlineRaw) : null;
                @endphp

                <div class="d-flex align-items-center gap-3 p-3 rounded mb-3" style="background:#F5F7FB;">
                    <div class="bg-light-primary text-primary p-2 rounded">📄</div>

                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $title }}</div>
                        <small class="text-muted">
                            Deadline: {{ $deadline ? $deadline->translatedFormat('d F Y') : '-' }}
                        </small>
                    </div>

                    @if ($taskId)
                        <a href="{{ route('student.tasks.show', $taskId) }}" class="btn btn-primary btn-sm">
                            Detail
                        </a>
                    @else
                        <button class="btn btn-primary btn-sm" type="button" disabled>Detail</button>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
