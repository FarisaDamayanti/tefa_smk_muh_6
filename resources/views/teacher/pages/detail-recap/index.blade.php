@extends('teacher.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/teacher-detailrecap.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection

@section('content')
    <div class="rekap-hero mb-4">
        <div class="rekap-hero-content">
            <div class="rekap-hero-text">
                <h2>
                    {{ $topic->classroom->levelClass->name ?? '' }}
                    {{ $topic->classroom->name ?? '' }}
                </h2>
                <h5>
                    {{ $topic->subject->name ?? '' }}
                </h5>
                <p>
                    Lihat Nilai siswa lebih lengkap dengan fitur ini
                </p>
            </div>

            <div class="rekap-hero-ornament">
                <img src="{{ asset('admin_assets/dist/images/breadcrumb/ChatBc.png') }}" class="img-fluid mb-n4">
            </div>
        </div>
    </div>
    <meta name="api-token" content="{{ session('api_bearer') ?? '' }}">
    <meta name="task-show-url" content="{{ route('api.teacher.task.show', ['task' => '__TASK__']) }}">

    @php
        $topics = collect($tasks ?? [])->values();
        $studentMap = collect();

        foreach ($topics as $topic) {
            foreach (collect(data_get($topic, 'studentsSubmission', [])) as $ss) {
                $sid = data_get($ss, 'student.id');
                if (!$sid) {
                    continue;
                }
                $studentMap->put($sid, data_get($ss, 'student'));
            }
        }

        $students = $studentMap->values();
        $grades = [];

        foreach ($topics as $topic) {
            $topicId = data_get($topic, 'id');
            if (!$topicId) {
                continue;
            }

            foreach (collect(data_get($topic, 'studentsSubmission', [])) as $ss) {
                $sid = data_get($ss, 'student.id');
                if (!$sid) {
                    continue;
                }

                foreach (collect(data_get($ss, 'task_submissions', [])) as $sub) {
                    $no = (int) data_get($sub, 'task_no', 0);
                    if ($no <= 0) {
                        continue;
                    }

                    $g = data_get($sub, 'grade');
                    $grades[$sid][$topicId][$no] = is_numeric($g) ? $g : '-';
                }
            }
        }

        $topicTaskCounts = [];
        $topicColspans = [];

        foreach ($topics as $topic) {
            $topicId = data_get($topic, 'id');
            $count = (int) collect(data_get($topic, 'tasks', []))->count();

            $topicTaskCounts[$topicId] = $count;
            $topicColspans[$topicId] = max($count, 1);
        }

        $totalTaskCols = max((int) array_sum($topicColspans), 1);

        $isEmpty = $students->isEmpty() || $topics->isEmpty();
        $colspan = 2 + $totalTaskCols;
        $taskVisible = $totalTaskCols;

        $badgeClass = fn($val) => is_numeric($val) ? 'badge-score' : 'badge-empty';
        $badgeLabel = fn($val) => is_numeric($val) ? $val : '-';

        $firstSS = null;
        foreach ($topics as $topic) {
            $firstSS = collect(data_get($topic, 'studentsSubmission', []))->first();
            if ($firstSS) {
                break;
            }
        }

        $exportClassroom = data_get($firstSS, 'classroom.id') ?? data_get($firstSS, 'classroom.classroom_id');
        $exportSubject = data_get($firstSS, 'subject.id') ?? data_get($firstSS, 'subject.subject_id');
        $exportHasData = $topics->isNotEmpty() && $students->isNotEmpty();
    @endphp

    <meta name="export-has-data" content="{{ $exportHasData ? '1' : '0' }}">

    <div class="rekap-filter mt-4 mb-3">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <form class="d-flex align-items-center flex-wrap gap-3 flex-grow-1" id="rekapFilterForm" method="GET"
                action="{{ url()->current() }}">
                <div class="search-wrap">
                    <input type="text" class="form-control search-input" name="search" value="{{ request('search') }}"
                        placeholder="Cari...." autocomplete="off">
                </div>

                <select name="semester" id="semester" class="form-select" style="width: 200px; height: 40px;">
                    <option value="">Semua Semester</option>
                    <option value="ganjil" {{ request('semester') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="genap" {{ request('semester') == 'genap' ? 'selected' : '' }}>Genap</option>
                </select>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary rekap-btn-filter" id="btnApplyFilter">
                        <i class="ti ti-filter me-2"></i>Filter
                    </button>

                    <a href="{{ url()->current() }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-2"
                        id="btnResetFilter">
                        <i class="ti ti-refresh"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>

            <div class="d-flex gap-2">
                <a href="javascript:void(0)" class="btn btn-success rekap-btn-export" id="btnExportExcel"
                    data-classroom="{{ $exportClassroom ?? '' }}" data-subject="{{ $exportSubject ?? '' }}"
                    aria-disabled="{{ !$exportHasData || empty($exportClassroom) || empty($exportSubject) ? 'true' : 'false' }}">
                    <span class="rekap-btn-export__icon" aria-hidden="true">
                        {{-- icon --}}
                        <svg width="17" height="19" viewBox="0 0 17 19" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.66699 5.58333V1.83333C2.66699 1.61232 2.75479 1.40036 2.91107 1.24408C3.06735 1.0878 3.27931 1 3.50033 1H15.167C15.388 1 15.6 1.0878 15.7562 1.24408C15.9125 1.40036 16.0003 1.61232 16.0003 1.83333V16.8333C16.0003 17.0543 15.9125 17.2663 15.7562 17.4226C15.6 17.5789 15.388 17.6667 15.167 17.6667H3.50033C3.27931 17.6667 3.06735 17.5789 2.91107 17.4226C2.75479 17.2663 2.66699 17.0543 2.66699 16.8333V13.0833"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.25 5.58301H13.5M11 8.91634H13.5M11 12.2497H13.5" stroke="white" stroke-width="2"
                                stroke-linecap="round" />
                            <path d="M3.5 8.08301L6 10.583M6 8.08301L3.5 10.583M1 5.58301H8.5V13.083H1V5.58301Z"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="btn-text">Export Excel</span>
                </a>

                <a href="{{ route('teacher.e-learning.grade-summary') }}"
                    class="btn btn-warning rekap-btn-back">Kembali</a>
            </div>
        </div>
    </div>

    <div id="rekapTableWrap">
        <div class="rekap-table card border-0" style="--task-visible: {{ $taskVisible }};">
            <div class="card-body p-0">
                <div class="rekap-table__viewport">
                    <div class="rekap-table__clip">
                        <div class="rekap-table__wrap table-responsive {{ $isEmpty ? 'is-empty' : '' }}">
                            <table class="table mb-0 text-nowrap rekap-table__table mb-0">
                                @if (!$isEmpty)
                                    <thead>
                                        <tr class="rekap-head-semester">
                                            <th class="col-no" rowspan="3" style="padding-top: 6px;">No</th>
                                            <th class="col-name text-center" rowspan="3">Nama</th>
                                            @foreach ($topics as $topic)
                                                <th class="col-semester text-center fw-normal" style="padding-top: 12px;"
                                                    colspan="{{ $topicColspans[data_get($topic, 'id')] ?? 1 }}">
                                                    {{ data_get($topic, 'semester') ? 'Semester ' . data_get($topic, 'semester') : '-' }}
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr class="rekap-head-topic">
                                            @foreach ($topics as $topic)
                                                <th class="col-topic text-center"
                                                    colspan="{{ $topicColspans[data_get($topic, 'id')] ?? 1 }}">
                                                    {{ data_get($topic, 'title', 'Topik') }}
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr class="rekap-head-task">
                                            @foreach ($topics as $topic)
                                                @forelse (collect(data_get($topic, 'tasks', []))->values() as $task)
                                                    <th class="col-task text-center"
                                                        style="border-top: 2px solid rgba(255, 255, 255, 0.85) !important;">
                                                        <a href="javascript:void(0)" class="task-link"
                                                            data-bs-toggle="modal" data-bs-target="#taskModal"
                                                            data-task-no="{{ $loop->iteration }}"
                                                            data-task-id="{{ data_get($task, 'id') }}"
                                                            data-task-title="{{ e(data_get($task, 'title', 'Tugas ' . $loop->iteration)) }}"
                                                            data-task-desc="{{ e(data_get($task, 'description', '')) }}">
                                                            Tugas {{ $loop->iteration }}
                                                        </a>
                                                    </th>
                                                @empty
                                                    <th class="col-task text-center"
                                                        style="border-top: 2px solid rgba(255, 255, 255, 0.85) !important;">
                                                        <span class="text-muted">-</span>
                                                    </th>
                                                @endforelse
                                            @endforeach
                                        </tr>
                                    </thead>
                                @endif

                                <tbody>
                                    @forelse ($students as $idx => $student)
                                        @php
                                            $studentId = data_get($student, 'id');
                                            $studentName = data_get($student, 'user.name', '-');
                                        @endphp

                                        <tr class="data-row">
                                            <td class="col-no text-center">{{ $idx + 1 }}</td>
                                            <td class="col-name fw-semibold text-center">{{ $studentName }}</td>

                                            @foreach ($topics as $topic)
                                                @php
                                                    $topicId = data_get($topic, 'id');
                                                    $taskCount = (int) ($topicTaskCounts[$topicId] ?? 0);
                                                @endphp

                                                @if ($taskCount === 0)
                                                    <td class="col-task text-center">
                                                        <span class="rekap-badge badge-empty">-</span>
                                                    </td>
                                                @else
                                                    @for ($no = 1; $no <= $taskCount; $no++)
                                                        @php $grade = $grades[$studentId][$topicId][$no] ?? '-'; @endphp
                                                        <td class="col-task text-center">
                                                            <span class="rekap-badge {{ $badgeClass($grade) }}">
                                                                {{ $badgeLabel($grade) }}
                                                            </span>
                                                        </td>
                                                    @endfor
                                                @endif
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr class="server-empty">
                                            <td colspan="{{ $colspan }}" class="text-center align-middle">
                                                <div class="d-flex flex-column justify-content-center align-items-center">
                                                    <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}"
                                                        alt="" width="300px">
                                                    <p class="fs-5 text-dark text-center mt-2">Belum ada data</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse

                                    <tr id="clientEmptyRow" class="d-none">
                                        <td colspan="{{ $colspan }}" class="text-center align-middle">
                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}"
                                                    alt="" width="300px">
                                                <p class="fs-5 text-dark text-center mt-2">Data Tidak Ditemukan</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('teacher.pages.detail-recap.widgets.modal-task')
    @include('teacher.pages.detail-recap.scripts.scripts-detail')
@endsection
