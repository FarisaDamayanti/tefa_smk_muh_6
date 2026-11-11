@php
    $topics = collect($data ?? []);

    // ===== helper normalize =====
    $norm = function ($sub) {
        $src = $sub;
        if (is_array($sub) && isset($sub["\x00*\x00attributes"])) {
            $src = $sub["\x00*\x00attributes"];
        } elseif (is_array($sub) && isset($sub["\x00*\x00items"])) {
            $src = $sub["\x00*\x00items"];
        }

        return [
            'task_no' => (int) (data_get($src, 'task_no') ?? 0),
            'grade' => data_get($src, 'grade'),
            'status' => data_get($src, 'status'),
        ];
    };

    $topicMetas = [];
    $studentMap = collect();
    $grades = [];
    $statuses = [];

    foreach ($topics as $tIndex => $topic) {
        $title =
            data_get($topic, 'title') ??
            (data_get($topic, 'topic.title') ?? (data_get($topic, 'topic_name') ?? 'Topik ' . ($tIndex + 1)));

        $topicStudents = collect(data_get($topic, 'studentsSubmission', []));

        $taskCount = (int) collect(data_get($topic, 'tasks', []))->count();
        if ($taskCount === 0) {
            $taskCount = (int) $topicStudents
                ->flatMap(fn($ss) => collect(data_get($ss, 'task_submissions', []))->map($norm))
                ->max('task_no');
        }
        $taskCount = max($taskCount, 1);

        $topicMetas[] = [
            'semester' => $topic->semester,
            'title' => $title,
            'taskCount' => $taskCount,
        ];

        foreach ($topicStudents as $ss) {
            $studentId = data_get($ss, 'student.id') ?? data_get($ss, 'student_id');
            if (!$studentId) {
                continue;
            }

            $studentMap->put($studentId, data_get($ss, 'student'));

            $subs = collect(data_get($ss, 'task_submissions', []))->map($norm)->filter(fn($x) => $x['task_no'] > 0);

            foreach ($subs as $sub) {
                $no = $sub['task_no'];
                $grades[$studentId][$tIndex][$no] = $sub['grade'];
                $statuses[$studentId][$tIndex][$no] = $sub['status'];
            }
        }
    }

    $students = $studentMap->values();

    $firstSS = null;
    foreach ($topics as $tp) {
        $firstSS = data_get($tp, 'studentsSubmission.0');
        if ($firstSS) {
            break;
        }
    }

    $classroomInfo = '-';
    $subjectInfo = '-';
    $schoolYear = '-';

    if ($firstSS) {
        $classroomInfo = strtoupper(
            (data_get($firstSS, 'classroom.name') ?? '-') . ' ' . (data_get($firstSS, 'school.user.name') ?? '-'),
        );
        $subjectInfo = 'MAPEL ' . (data_get($firstSS, 'subject.name') ?? '-');
        $schoolYear = data_get($firstSS, 'school_year.school_year') ?? '-';
    }

    // total columns = No + Nama + sum( (taskCount * 2) ) per topik
    $totalColspan = 2 + collect($topicMetas)->sum(fn($m) => $m['taskCount'] * 2);
@endphp

<table border="0" cellpadding="5" cellspacing="0"
    style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif;">

    <tr>
        <td colspan="{{ $totalColspan }}"
            style="color: black; font-size: 16px; font-weight: bold; text-align: center; padding: 15px;">
            REKAP NILAI SISWA KELAS {{ $classroomInfo }} {{ $subjectInfo }}
        </td>
    </tr>

    <tr>
        <td colspan="{{ $totalColspan }}"
            style="color: black; font-size: 14px; font-weight: bold; text-align: center; padding: 40px;">
            TAHUN PELAJARAN {{ $schoolYear }}
        </td>
    </tr>

    <tr>
        <td colspan="{{ $totalColspan }}" style="background-color: white; height: 10px;"></td>
    </tr>

    <tr>
        <th rowspan="4"
            style="background-color:#4472C4;color:#fff;border:1px solid #8EA9DB;padding:8px;text-align:center;vertical-align:middle;">
            No
        </th>
        <th rowspan="4"
            style="background-color:#4472C4;color:#fff;border:1px solid #8EA9DB;padding:8px;text-align:center;vertical-align:middle;">
            Nama Lengkap
        </th>

        @foreach ($topicMetas as $meta)
            <th colspan="{{ $meta['taskCount'] * 2 }}"
                style="background-color:#4A6FA5;color:#fff;padding:4px;text-align:center; font-size:14pt; font-weight:normal;">
                {{ 'Semester '.ucfirst($meta['semester']) }}
            </th>
        @endforeach
    </tr>

    {{-- Header Row 2: TITLE row --}}
    <tr>
        @foreach ($topicMetas as $meta)
            <th colspan="{{ $meta['taskCount'] * 2 }}"
                style="background-color:#4A6FA5;color:#fff;padding:8px;text-align:center; font-size:14pt; font-weight:bold;">
                {{ $meta['title'] }}
            </th>
        @endforeach
    </tr>

    <tr>
        @foreach ($topicMetas as $meta)
            @for ($i = 1; $i <= $meta['taskCount']; $i++)
                <th colspan="2"
                    style="background-color:#D9E1F2;color:#000;border:1px solid #8EA9DB;padding:6px;text-align:center;white-space:nowrap;">
                    Tugas {{ $i }}
                </th>
            @endfor
        @endforeach
    </tr>
    <tr>
        @foreach ($topicMetas as $meta)
            @for ($i = 1; $i <= $meta['taskCount']; $i++)
                <th style="background-color:#D9E1F2;color:#000;border:1px solid #8EA9DB;padding:6px;text-align:center;">
                    Nilai
                </th>
                <th style="background-color:#D9E1F2;color:#000;border:1px solid #8EA9DB;padding:6px;text-align:center;">
                    Status
                </th>
            @endfor
        @endforeach
    </tr>

    {{-- Data rows: 1 siswa 1 baris --}}
    @php $rowColor = true; @endphp
    @foreach ($students as $idx => $student)
        @php
            $bgColor = $rowColor ? '#FFFFFF' : '#F2F2F2';
            $rowColor = !$rowColor;
            $studentId = data_get($student, 'id');
            $name = data_get($student, 'user.name', '-');
        @endphp

        <tr>
            <td style="border:1px solid #BFBFBF;padding:6px;text-align:center;background-color:{{ $bgColor }};">
                {{ $idx + 1 }}
            </td>
            <td style="border:1px solid #BFBFBF;padding:6px;background-color:{{ $bgColor }};">
                {{ $name }}
            </td>

            @foreach ($topicMetas as $tIndex => $meta)
                @for ($i = 1; $i <= $meta['taskCount']; $i++)
                    @php
                        $grade = $grades[$studentId][$tIndex][$i] ?? '-';
                        $statusObj = $statuses[$studentId][$tIndex][$i] ?? null;

                        $statusLabel = '-';
                        if ($statusObj) {
                            $statusLabel = method_exists($statusObj, 'label')
                                ? $statusObj->label()
                                : (string) $statusObj;
                        }
                    @endphp

                    <td
                        style="border:1px solid #BFBFBF;padding:6px;text-align:center;background-color:{{ $bgColor }};">
                        <strong>{{ $grade }}</strong>
                    </td>

                    <td
                        style="border:1px solid #BFBFBF;padding:6px;text-align:center;background-color:{{ $bgColor }};">
                        {{ $statusLabel }}
                    </td>
                @endfor
            @endforeach
        </tr>
    @endforeach
</table>
