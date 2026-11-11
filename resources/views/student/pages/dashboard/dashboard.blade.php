@extends('student.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/dashboard.css') }}">
@endsection

@section('content')
    <div class="row g-4">
        @php
            $studentName =
                data_get($studentClasses, 'student.user.name') ??
                (data_get($studentClasses, 'student.name') ?? 'Siswa');

            $className = data_get($studentClasses, 'classroom.name', '-');
            $classLevel = data_get($studentClasses, 'classroom.levelClass.name',''); 

            $studentImage =
                data_get($studentClasses, 'student.user.image') ?? data_get($studentClasses, 'student.image');

            $avatar = $studentImage ? asset('storage/' . $studentImage) : asset('assets/images/default-user.jpeg');

            $urgentTasks = collect($incomingDeadlineTasks ?? []);

            $homeroomTeacherName = data_get($studentClasses, 'classroom.employee.user.name', '-');
            $schoolYear = data_get($studentClasses, 'classroom.schoolYear.school_year', '-');

            $subjects = collect(data_get($studentClasses, 'classroom.employee.teacherSubjects', []))
                ->map(fn($subject) => data_get($subject, 'subject.name'))
                ->filter()
                ->values();

            $teacherAvatar = data_get($studentClasses, 'classroom.employee.user.image')
                ? asset('storage/' . data_get($studentClasses, 'classroom.employee.user.image'))
                : asset('assets/images/default-user.jpeg');

            $classNameRight = data_get($studentClasses, 'classroom.name', '-');
            $classLevelRight = data_get($studentClasses, 'classroom.levelClass.name', '');
            $totalStudentsRight = (int) data_get($studentClasses, 'classroom.classroomStudents', collect())->count();

            $latestItems = $latestItems ?? [];
        @endphp

        {{-- TOP --}}
        <div class="col-12 stack-gap">
            @include('student.pages.dashboard.widgets.breadcrumb-header', [
                'title' => 'Halo, ' . $studentName . ' - ' . $classLevel . ' ' . $className,
                'subtitle' => 'Selamat Datang di Dashboard Pembelajaran ' .$classLevel . ' ' . $className,
                'avatar' => $avatar,
            ])

            <div class="owl-carousel stat-carousel mt-4">
                <div class="item">
                    <div class="stat-card stat-blue h-100">
                        <div class="stat-body">
                            <div>
                                <div class="stat-label">Total Kehadiran</div>
                                <div class="stat-value">{{ $chartAttendance['present'] ?? 0 }}</div>
                            </div>
                            <div class="stat-ico">
                                <svg width="118" height="86" viewBox="0 0 118 86" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M0 86V70.95C0 67.9042 0.783091 65.1056 2.34927 62.5543C3.91545 60.003 5.99297 58.0536 8.58182 56.7063C14.1242 53.9292 19.7561 51.8473 25.4773 50.4606C31.1985 49.0738 37.0091 48.3787 42.9091 48.3751C48.8091 48.3715 54.6197 49.0666 60.3409 50.4606C66.0621 51.8545 71.6939 53.9364 77.2364 56.7063C79.8288 58.0501 81.9081 59.9994 83.4743 62.5543C85.0405 65.1092 85.8218 67.9078 85.8182 70.95V86H0ZM96.5455 86V69.875C96.5455 65.9334 95.4513 62.1476 93.2629 58.5177C91.0745 54.8878 87.9672 51.7756 83.9409 49.1813C88.5 49.7188 92.7909 50.6379 96.8136 51.9387C100.836 53.2394 104.591 54.8286 108.077 56.7063C111.295 58.498 113.754 60.4903 115.452 62.6833C117.151 64.8763 118 67.2735 118 69.875V86H96.5455ZM27.7568 36.6845C23.5553 32.474 21.4545 27.4126 21.4545 21.5001C21.4545 15.5876 23.5553 10.5262 27.7568 6.31575C31.9583 2.10534 37.0091 0.000137818 42.9091 0.000137818C48.8091 0.000137818 53.8598 2.10534 58.0614 6.31575C62.2629 10.5262 64.3636 15.5876 64.3636 21.5001C64.3636 27.4126 62.2629 32.474 58.0614 36.6845C53.8598 40.8949 48.8091 43.0001 42.9091 43.0001C37.0091 43.0001 31.9583 40.8949 27.7568 36.6845ZM90.2432 36.6845C86.0417 40.8949 80.9909 43.0001 75.0909 43.0001C74.1076 43.0001 72.8561 42.889 71.3364 42.6668C69.8167 42.4447 68.5652 42.1974 67.5818 41.9251C69.9955 39.0584 71.8513 35.8782 73.1493 32.3845C74.4473 28.8907 75.0945 25.2626 75.0909 21.5001C75.0873 17.7376 74.4401 14.1095 73.1493 10.6157C71.8584 7.122 70.0026 3.9418 67.5818 1.07514C68.8333 0.627219 70.0849 0.33518 71.3364 0.199013C72.5879 0.062847 73.8394 -0.00344551 75.0909 0.000137818C80.9909 0.000137818 86.0417 2.10534 90.2432 6.31575C94.4447 10.5262 96.5455 15.5876 96.5455 21.5001C96.5455 27.4126 94.4447 32.474 90.2432 36.6845Z"
                                        fill="white" fill-opacity="0.3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="stat-card stat-red h-100">
                        <div class="stat-body">
                            <div>
                                <div class="stat-label">Total Alpha</div>
                                <div class="stat-value">{{ $chartAttendance['alpha'] ?? 0 }}</div>
                            </div>
                            <div class="stat-ico">
                                <svg width="101" height="101" viewBox="0 0 101 101" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M80.8 56.1111C84.335 56.1111 87.7185 56.8406 90.9 58.075V33.6667L60.6 0H10.1C4.4945 0 0 4.99389 0 11.2222V89.7778C0 92.7541 1.0641 95.6085 2.95822 97.7131C4.85234 99.8177 7.42131 101 10.1 101H52.2675C51.1565 97.465 50.5 93.7056 50.5 89.7778C50.5 71.205 64.0845 56.1111 80.8 56.1111ZM55.55 8.41667L83.325 39.2778H55.55V8.41667ZM101 84.1667V95.3889H60.6V84.1667H101Z"
                                        fill="white" fill-opacity="0.3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="stat-card stat-orange h-100">
                        <div class="stat-body">
                            <div>
                                <div class="stat-label">Total Izin</div>
                                <div class="stat-value">{{ $chartAttendance['permit'] ?? 0 }}</div>
                            </div>
                            <div class="stat-ico">
                                <svg width="91" height="94" viewBox="0 0 91 94" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M58.0904 51.4933L79.4531 72.8611L58.0853 94.2289C57.1411 95.1734 55.8604 95.7042 54.5249 95.7045H40.2881C38.9524 95.7045 37.6715 95.1739 36.7271 94.2295C35.7826 93.285 35.2521 92.0041 35.2521 90.6685V76.4216C35.2524 75.086 35.7831 73.8053 36.7276 72.8611L58.0904 51.4933ZM70.5041 5.04952e-07C73.0452 -0.000803644 75.4926 0.95889 77.3559 2.6867C79.2191 4.41451 80.3604 6.78273 80.551 9.31663L80.5762 10.072V30.3168C76.7716 29.5967 72.8522 29.7636 69.1226 30.8047C65.3931 31.8459 61.9539 33.7331 59.0724 36.3198L58.0853 37.2514L29.6017 65.7402C27.0127 68.3295 25.4522 71.7709 25.2103 75.4244L25.175 76.4216V90.6685C25.1754 92.037 25.3618 93.3991 25.729 94.7174L26.0311 95.6843H10.072C7.53097 95.6851 5.08352 94.7254 3.22029 92.9976C1.35706 91.2698 0.21576 88.9016 0.0251811 86.3677L5.04951e-07 85.6123V10.072C-0.000803643 7.53098 0.958889 5.08353 2.6867 3.22029C4.4145 1.35706 6.78272 0.21576 9.31662 0.0251812L10.072 5.04952e-07H70.5041ZM86.5741 44.3724C87.9772 45.7753 89.0903 47.4409 89.8497 49.2741C90.6091 51.1072 91 53.072 91 55.0563C91 57.0405 90.6091 59.0053 89.8497 60.8385C89.0903 62.6716 87.9772 64.3372 86.5741 65.7402L65.2163 44.3673C68.0495 41.535 71.8916 39.9439 75.8977 39.9439C79.9038 39.9439 83.7459 41.535 86.5791 44.3673L86.5741 44.3724ZM40.2881 20.1441H20.144C18.8084 20.1441 17.5275 20.6747 16.583 21.6191C15.6386 22.5635 15.108 23.8445 15.108 25.1801C15.108 26.5157 15.6386 27.7967 16.583 28.7411C17.5275 29.6855 18.8084 30.2161 20.144 30.2161H40.2881C41.6237 30.2161 42.9046 29.6855 43.8491 28.7411C44.7935 27.7967 45.3241 26.5157 45.3241 25.1801C45.3241 23.8445 44.7935 22.5635 43.8491 21.6191C42.9046 20.6747 41.6237 20.1441 40.2881 20.1441Z"
                                        fill="white" fill-opacity="0.3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="stat-card stat-green h-100">
                        <div class="stat-body">
                            <div>
                                <div class="stat-label">Tugas Selesai</div>
                                <div class="stat-value">{{ $chartTask['submitted'] ?? 0 }}</div>
                            </div>
                            <div class="stat-ico">
                                <svg width="79" height="99" viewBox="0 0 79 99" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M54.3125 0C56.0459 4.41463e-05 57.7487 0.457508 59.2499 1.32641C60.751 2.19532 61.9976 3.44505 62.8643 4.95H69.125C71.744 4.95 74.2558 5.99303 76.1077 7.84964C77.9596 9.70625 79 12.2244 79 14.85V74.25C79 80.8141 76.399 87.1094 71.7692 91.7509C67.1394 96.3924 60.86 99 54.3125 99H9.875C7.25599 99 4.74424 97.957 2.89232 96.1003C1.0404 94.2437 0 91.7256 0 89.1V14.85C0 12.2244 1.0404 9.70625 2.89232 7.84964C4.74424 5.99303 7.25599 4.95 9.875 4.95H16.1357C17.0024 3.44505 18.249 2.19532 19.7501 1.32641C21.2513 0.457508 22.9541 4.41463e-05 24.6875 0H54.3125ZM53.4435 36.526L32.4986 57.5289L25.5121 50.5246C24.5808 49.623 23.3336 49.124 22.039 49.1353C20.7444 49.1466 19.506 49.6672 18.5906 50.5849C17.6751 51.5027 17.1559 52.7442 17.1446 54.0421C17.1334 55.34 17.631 56.5904 18.5304 57.5239L28.6523 67.6764C29.1567 68.1823 29.7555 68.5836 30.4146 68.8574C31.0738 69.1312 31.7802 69.2721 32.4937 69.2721C33.2071 69.2721 33.9136 69.1312 34.5727 68.8574C35.2318 68.5836 35.8307 68.1823 36.3351 67.6764L60.4301 43.5253C60.8885 43.0654 61.2521 42.5195 61.5 41.9187C61.748 41.318 61.8755 40.6741 61.8753 40.0239C61.875 39.3738 61.7471 38.73 61.4987 38.1294C61.2503 37.5288 60.8863 36.9832 60.4276 36.5236C59.9689 36.064 59.4243 35.6995 58.8251 35.4509C58.2258 35.2023 57.5836 35.0745 56.935 35.0747C56.2865 35.0749 55.6444 35.2032 55.0453 35.4522C54.4462 35.7013 53.9019 36.0661 53.4435 36.526ZM51.8438 9.9H27.1562C26.5784 9.89981 26.0189 10.1028 25.575 10.4737C25.1311 10.8445 24.831 11.3597 24.727 11.9295L24.6875 12.375V17.325C24.6873 17.9043 24.8898 18.4653 25.2597 18.9103C25.6296 19.3553 26.1435 19.6561 26.7119 19.7604L27.1562 19.8H51.8438C52.4216 19.8002 52.9811 19.5972 53.425 19.2263C53.8689 18.8555 54.169 18.3403 54.273 17.7705L54.3125 17.325V12.375C54.3127 11.7957 54.1102 11.2347 53.7403 10.7897C53.3704 10.3447 52.8565 10.0439 52.2881 9.9396L51.8438 9.9Z"
                                        fill="white" fill-opacity="0.3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="stat-card stat-yellow h-100">
                        <div class="stat-body">
                            <div>
                                <div class="stat-label">Tugas Belum Selesai</div>
                                <div class="stat-value">{{ $chartTask['not_submit'] ?? 0 }}</div>
                            </div>
                            <div class="stat-ico">
                                <svg width="84" height="84" viewBox="0 0 84 84" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M44.2105 84V71.085L68.6368 47.985C69.3 47.355 70.0368 46.9 70.8473 46.62C71.6579 46.34 72.4684 46.2 73.2789 46.2C74.1631 46.2 75.0105 46.3582 75.821 46.6746C76.6315 46.991 77.3684 47.4628 78.0315 48.09L82.121 51.975C82.7105 52.605 83.1717 53.305 83.5048 54.075C83.8379 54.845 84.0029 55.615 84 56.385C83.997 57.155 83.8496 57.9432 83.5579 58.7496C83.2661 59.556 82.7871 60.2728 82.121 60.9L57.8052 84H44.2105ZM13.2632 29.4H53.0526V12.6H13.2632V29.4ZM73.2789 60.48L77.3684 56.385L73.2789 52.5L69.0789 56.49L73.2789 60.48ZM35.3684 75.6H8.8421C6.41052 75.6 4.32968 74.7782 2.59958 73.1346C0.869474 71.491 0.00294737 69.5128 0 67.2V8.4C0 6.09 0.866526 4.1132 2.59958 2.4696C4.33263 0.826 6.41347 0.0028 8.8421 0H61.8947L79.5789 16.8V39.06C78.1789 38.5 76.7244 38.1332 75.2153 37.9596C73.7063 37.786 72.2134 37.8028 70.7368 38.01C69.2602 38.2172 67.8233 38.6554 66.4263 39.3246C65.0292 39.9938 63.7398 40.8506 62.5579 41.895L53.0526 50.925V50.4C53.0526 46.9 51.7631 43.925 49.1842 41.475C46.6052 39.025 43.4737 37.8 39.7895 37.8C36.1052 37.8 32.9737 39.025 30.3947 41.475C27.8158 43.925 26.5263 46.9 26.5263 50.4C26.5263 53.9 27.8158 56.875 30.3947 59.325C32.9737 61.775 36.1052 63 39.7895 63H40.2316L35.3684 67.62V75.6Z"
                                        fill="white" fill-opacity="0.3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('student.pages.dashboard.widgets.reminder-exam', [
                'incomingExams' => $incomingExams,
            ])
        </div>

        {{-- MAIN GRID --}}
        <div class="col-12">
            <div class="row g-4">
                {{-- LEFT --}}
                <div class="col-12 col-lg-8 order-2 order-lg-1">
                    {{-- Tugas Mendekati Deadline --}}
                    <div class="dash-card order-2 mb-4">
                        <div class="dash-section-title">Tugas Mendekati Deadline</div>

                        @if ($urgentTasks->isEmpty())
                            <div class="dash-section-body">
                                <div class="d-flex flex-column justify-content-center align-items-center py-4">
                                    <img src="{{ asset('admin_assets/dist/images/empty/noodata.png') }}" alt="empty">
                                    <h4 class="mt-3 mb-2 fw-semibold">Belum Ada Tugas Mendesak</h4>
                                    <p class="text-muted mb-0 text-center">
                                        Saat ini tidak ada tugas yang mendekati batas pengumpulan.
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="dash-section-body">
                                <div class="d-flex flex-column gap-3">
                                    @foreach ($urgentTasks->take(3) as $task)
                                        @php
                                            $title = data_get($task, 'title', '-');
                                            $deadlineRaw = data_get($task, 'deadline') ?? data_get($task, 'due_date');
                                            $deadline = $deadlineRaw ? \Carbon\Carbon::parse($deadlineRaw) : null;
                                        @endphp

                                        <div class="d-flex align-items-center gap-3 task-item">
                                            <div class="task-icon">
                                                <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M3.75 7.8125C3.75 6.73506 4.17801 5.70175 4.93988 4.93988C5.70175 4.17801 6.73506 3.75 7.8125 3.75H22.1875C23.2649 3.75 24.2983 4.17801 25.0601 4.93988C25.822 5.70175 26.25 6.73506 26.25 7.8125V15.0275C24.69 14.0298 22.8359 13.5949 20.9949 13.7948C19.1539 13.9948 17.4365 14.8176 16.1271 16.1271C14.8176 17.4365 13.9948 19.1539 13.7948 20.9949C13.5949 22.8359 14.0298 24.69 15.0275 26.25H7.8125C6.73506 26.25 5.70175 25.822 4.93988 25.0601C4.17801 24.2983 3.75 23.2649 3.75 22.1875V7.8125ZM15.625 11.5625C15.625 12.08 16.045 12.5 16.5625 12.5H20.9375C21.1861 12.5 21.4246 12.4012 21.6004 12.2254C21.7762 12.0496 21.875 11.8111 21.875 11.5625C21.875 11.3139 21.7762 11.0754 21.6004 10.8996C21.4246 10.7238 21.1861 10.625 20.9375 10.625H16.5625C16.3139 10.625 16.0754 10.7238 15.8996 10.8996C15.7238 11.0754 15.625 11.3139 15.625 11.5625ZM13.475 10.975C13.5671 10.8892 13.641 10.7857 13.6922 10.6707C13.7435 10.5557 13.771 10.4315 13.7732 10.3057C13.7755 10.1798 13.7523 10.0547 13.7052 9.938C13.658 9.82127 13.5878 9.71523 13.4988 9.6262C13.4098 9.53718 13.3037 9.467 13.187 9.41985C13.0703 9.3727 12.9452 9.34954 12.8193 9.35176C12.6935 9.35398 12.5693 9.38153 12.4543 9.43277C12.3393 9.48401 12.2358 9.55789 12.15 9.65L10.3125 11.4875L9.725 10.9C9.54728 10.7344 9.31222 10.6442 9.06935 10.6485C8.82647 10.6528 8.59474 10.7512 8.42297 10.923C8.25121 11.0947 8.15282 11.3265 8.14853 11.5693C8.14425 11.8122 8.2344 12.0473 8.4 12.225L9.65 13.475C9.82578 13.6506 10.0641 13.7492 10.3125 13.7492C10.5609 13.7492 10.7992 13.6506 10.975 13.475L13.475 10.975ZM13.475 16.525C13.2992 16.3494 13.0609 16.2508 12.8125 16.2508C12.5641 16.2508 12.3258 16.3494 12.15 16.525L10.3125 18.3625L9.725 17.775C9.54728 17.6094 9.31222 17.5192 9.06935 17.5235C8.82647 17.5278 8.59474 17.6262 8.42297 17.798C8.25121 17.9697 8.15282 18.2015 8.14853 18.4443C8.14425 18.6872 8.2344 18.9223 8.4 19.1L9.65 20.35C9.82578 20.5256 10.0641 20.6242 10.3125 20.6242C10.5609 20.6242 10.7992 20.5256 10.975 20.35L13.475 17.85C13.6506 17.6742 13.7492 17.4359 13.7492 17.1875C13.7492 16.9391 13.6506 16.7008 13.475 16.525ZM28.75 21.875C28.75 20.0516 28.0257 18.303 26.7364 17.0136C25.447 15.7243 23.6984 15 21.875 15C20.0516 15 18.303 15.7243 17.0136 17.0136C15.7243 18.303 15 20.0516 15 21.875C15 23.6984 15.7243 25.447 17.0136 26.7364C18.303 28.0257 20.0516 28.75 21.875 28.75C23.6984 28.75 25.447 28.0257 26.7364 26.7364C28.0257 25.447 28.75 23.6984 28.75 21.875ZM22.5 22.5L22.5012 25.6287C22.5012 25.7945 22.4354 25.9535 22.3182 26.0707C22.201 26.1879 22.042 26.2537 21.8762 26.2537C21.7105 26.2537 21.5515 26.1879 21.4343 26.0707C21.3171 25.9535 21.2512 25.7945 21.2512 25.6287V22.5H18.12C17.9542 22.5 17.7953 22.4342 17.6781 22.3169C17.5608 22.1997 17.495 22.0408 17.495 21.875C17.495 21.7092 17.5608 21.5503 17.6781 21.4331C17.7953 21.3158 17.9542 21.25 18.12 21.25H21.25V18.125C21.25 17.9592 21.3158 17.8003 21.4331 17.6831C21.5503 17.5658 21.7092 17.5 21.875 17.5C22.0408 17.5 22.1997 17.5658 22.3169 17.6831C22.4342 17.8003 22.5 17.9592 22.5 18.125V21.25H25.6213C25.787 21.25 25.946 21.3158 26.0632 21.4331C26.1804 21.5503 26.2463 21.7092 26.2463 21.875C26.2463 22.0408 26.1804 22.1997 26.0632 22.3169C25.946 22.4342 25.787 22.5 25.6213 22.5H22.5Z"
                                                        fill="white" />
                                                </svg>
                                            </div>

                                            <div class="flex-grow-1">
                                                <div class="fw-semibold">{{ $title }}</div>
                                                <small class="text-muted">
                                                    Deadline: {{ $deadline ? $deadline->translatedFormat('d F Y') : '-' }}
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- ROW: Riwayat Absensi + Statistik Absensi (SEJAJAR) --}}
                    <div class="row g-4 order-4">
                        {{-- Tugas Terbaru --}}
                        <div class="col-12 col-lg-5">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden order-2 order-lg-3">

                                <div class="px-4 py-3 text-white fw-semibold" style="background:#5D87FF;">
                                    Tugas Terbaru
                                </div>

                                <div class="card-body p-3 p-md-4">

                                    @if ($incomingDeadlineTasks->isEmpty())
                                        <div class="d-flex flex-column justify-content-center align-items-center py-3">
                                            <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}"
                                                style="max-width: 220px;">
                                            <div class="text-muted mt-2">Belum ada data terbaru</div>
                                        </div>
                                    @else
                                        <div class="d-flex flex-column gap-3">
                                            @foreach ($incomingDeadlineTasks->take(3) as $task)
                                                <div class="d-flex align-items-start gap-3 p-3 rounded"
                                                    style="background:#F5F7FB;">

                                                    <div class="task-icon" style="background:#5D87FF;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                            viewBox="0 0 24 24">
                                                            <path fill="currentColor"
                                                                d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zm0 2l6 6h-6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="flex-grow-1">
                                                        <div class="fw-semibold">{{ $task->title }}</div>

                                                        <small class="text-muted">
                                                            Tugas
                                                            @if ($task->deadline)
                                                                • {{ \Carbon\Carbon::parse($task->deadline)->translatedFormat('d F Y') }}
                                                            @endif
                                                        </small>
                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                        {{-- Riwayat Absensi --}}
                        <div class="col-12 col-lg-7">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 dash-ui-card">
                                <div class="px-4 py-3 text-white fw-semibold" style="background:#5D87FF;">
                                    Riwayat Absensi
                                </div>

                                <div class="attendance-table-wrap">
                                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                        <table class="table text-nowrap mb-0 align-middle attendance-table">
                                            <thead>
                                                <tr>
                                                    <th>Hari</th>
                                                    <th>Tanggal</th>
                                                    <th>Masuk</th>
                                                    <th>Keluar</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse ($history_attendance as $data)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('l') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y') }}
                                                        </td>
                                                        <td>{{ $data->checkin == null ? '-' : \Carbon\Carbon::parse($data->checkin)->format('H:i') }}
                                                        </td>
                                                        <td>{{ $data->checkout == null ? '-' : \Carbon\Carbon::parse($data->checkout)->format('H:i') }}
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="mb-1 badge font-medium {{ $data->status->color() }}">
                                                                {{ $data->status->label() }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5">
                                                            <div
                                                                class="d-flex flex-column justify-content-center align-items-center py-4">
                                                                <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}"
                                                                    alt="" width="220px">
                                                                <p class="text-muted text-center mt-2 mb-0">Tidak ada riwayat absen</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden d-lg-none d-block">
                                <div class="px-4 py-3 text-white fw-semibold" style="background:#5D87FF;">
                                    Statistik Absensi
                                </div>
    
                                <div class="card-body p-3 p-md-4">
                                    <div class="text-muted mb-3 fs-4">Tahun ini</div>
                                    <div id="chart-attendance-1" class="d-flex justify-content-center"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Statistik Absensi --}}
                        {{-- <div class="col-12 col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                                <div class="px-4 py-3 text-white fw-semibold" style="background:#5D87FF;">
                                    Statistik Absensi
                                </div>

                                <div class="card-body p-3 p-md-4">
                                    <div class="text-muted mb-3 fs-4">Tahun ini</div>
                                    <div id="chart-attendance" class="d-flex justify-content-center"></div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="col-12 col-lg-4 order-1 order-lg-2">
                    {{-- Informasi Kelas --}}
                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 text-white fw-semibold" style="background:#5D87FF;">
                            Informasi Kelas
                        </div>

                        <div class="card-body p-3 p-md-4 shadow-md">
                            <div class="position-relative rounded-4 p-3 p-md-4 bg-light-primary shadow-sm overflow-hidden">
                                <div class="d-flex align-items-center gap-3 mb-3 bg-white rounded-2 py-2">
                                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width:58px;height:58px;">
                                        <img src="{{ asset('assets/images/Topi.png') }}" alt=""
                                            style="width:36px;height:auto;">
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="fw-bold" style="color:#111827;">{{ $classLevelRight  }} {{ $classNameRight }}</div>
                                        <span class="badge bg-light-primary text-primary mt-1">
                                            {{ $totalStudentsRight }} Total Siswa
                                        </span>
                                    </div>
                                </div>

                                <div class="rounded-4 p-3 bg-white">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $teacherAvatar }}" alt="avatar"
                                            class="rounded-circle flex-shrink-0"
                                            style="width:48px;height:48px;object-fit:cover;">

                                        <div class="flex-grow-1">
                                            <div class="fw-semibold" style="color:#111827;">
                                                {{ $homeroomTeacherName }}
                                            </div>
                                            <div class="small text-muted">Tahun Ajaran {{ $schoolYear }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Statistik Absensi --}}
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden d-none d-lg-block">
                        <div class="px-4 py-3 text-white fw-semibold" style="background:#5D87FF;">
                            Statistik Absensi
                        </div>

                        <div class="card-body p-3 p-md-4">
                            <div class="text-muted mb-3 fs-4">Tahun ini</div>
                            <div id="chart-attendance-2" class="d-flex justify-content-center"></div>
                        </div>
                    </div>

                    {{-- Tugas Terbaru --}}
                    {{-- <div class="card border-0 shadow-sm rounded-4 overflow-hidden order-3 order-lg-2">

                        <div class="px-4 py-3 text-white fw-semibold" style="background:#5D87FF;">
                          Tugas Terbaru
                        </div>

                        <div class="card-body p-3 p-md-4">

                            @if ($incomingDeadlineTasks->isEmpty())
                                <div class="d-flex flex-column justify-content-center align-items-center py-3">
                                    <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}"
                                        style="max-width: 220px;">
                                    <div class="text-muted mt-2">Belum ada data terbaru</div>
                                </div>
                            @else
                                <div class="d-flex flex-column gap-3">
                                    @foreach ($incomingDeadlineTasks->take(3) as $task)
                                        <div class="d-flex align-items-start gap-3 p-3 rounded"
                                            style="background:#F5F7FB;">

                                            <div class="task-icon" style="background:#5D87FF;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zm0 2l6 6h-6z" />
                                                </svg>
                                            </div>

                                            <div class="flex-grow-1">
                                                <div class="fw-semibold">{{ $task->title }}</div>

                                                <small class="text-muted">
                                                    Tugas
                                                    @if ($task->deadline)
                                                        •
                                                        {{ \Carbon\Carbon::parse($task->deadline)->translatedFormat('d F Y') }}
                                                    @endif
                                                </small>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div> --}}

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        const toggleButton = document.getElementById('toggle-subjects');
        const additionalSubjects = document.getElementById('additional-subjects');
        const closeButton = document.getElementById('close-subjects');

        toggleButton?.addEventListener('click', function() {
            additionalSubjects.style.display = 'block';
            toggleButton.style.display = 'none';
        });

        closeButton?.addEventListener('click', function() {
            additionalSubjects.style.display = 'none';
            toggleButton.style.display = 'inline-block';
        });
    </script>

    @include('student.pages.dashboard.scripts.chart-attendance')

    <script>
        $(document).ready(function(){
            $(".stat-carousel").owlCarousel({
                loop: true,
                autoplay: true,
                autoplayTimeout: 2500,
                autoplayHoverPause: true,
                margin: 16,
                nav: false,
                dots: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    576: {
                        items: 2
                    },
                    992: {
                        items: 3
                    },
                    1200: {
                        items: 5
                    }
                }
            });
        });
    </script>
@endsection
