@extends('teacher.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/teacher-recapvalue.css') }}">
@endsection

@section('content')
    {{-- HEADER --}}
    <x-authenticated.breadcrumb title="Daftar Mapel" breadcrumb="Daftar Mapel dan Kelas yang anda didik" />

    {{-- FILTER BAR --}}
    <div class="rekap-filter mt-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <form id="rekapFilterForm" class="d-flex align-items-center flex-wrap gap-3 flex-grow-1" method="GET"
                action="{{ url()->current() }}">
                {{-- Filter Tahun --}}
                <select class="form-select filter-select" id="school_year" name="school_year_id">
                    @foreach ($filterSchoolYears as $year)
                        <option value="{{ $year->id }}"
                            {{ request()->school_year_id ? (request()->school_year_id == $year->id ? 'selected' : '') : ($year->active ? 'selected' : '') }}>
                            {{ $year->school_year }}
                        </option>
                    @endforeach
                </select>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary rekap-btn-filter">
                        <i class="ti ti-filter me-2"></i>Filter
                    </button>

                    <button type="button" class="btn btn-outline-secondary rekap-btn-reset">
                        <i class="ti ti-refresh me-2"></i>Reset
                    </button>
                </div>
            </form>

            <div class="d-flex gap-2">
                @if (count($subjects) > 0)
                    <a href="javascript:void(0)" class="btn btn-success rekap-btn-export" id="btnExport">
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
                        <span class="btn-text">Export Excel</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="rekap-card">
        <div class="rekap-card__body">
            <div class="table-responsive">
                <table class="table rekap-table mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th class="text-center" style="width: 120px;">Kelas</th>
                            <th class="text-center" style="width: 190px;">Mata Pelajaran</th>
                            <th class="text-center" style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="rekapTbody">
                        @forelse ($subjects as $subject)
                            <tr>
                                <td class="text-center">
                                    @if (is_object($subjects) && method_exists($subjects, 'firstItem'))
                                        {{ $subjects->firstItem() + $loop->index }}
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </td>
                                <td class="text-center">{{ $subject['level_class'] }} {{ $subject['classroom_name'] }}</td>
                                <td class="text-center">{{ $subject['subject_name'] }}</td>
                                <td class="text-center">
                                    <a href="{{ url('teacher/evaluations/' . $subject['classroom_id'] . '/' . $subject['subject_id']) }}"
                                        class="rekap-btn-detail">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center align-middle">
                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                        <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt=""
                                            width="300px">
                                        <p class="fs-5 text-dark text-center mt-2">
                                            Belum ada data
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- PAGINATION --}}
    <div class="d-flex justify-content-end mt-3">
        <nav aria-label="Pagination">
            <ul class="pagination pagination-sm mb-0" id="rekapPagination"></ul>
        </nav>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            function toast(type, msg) {
                if (window.toastr && toastr[type]) return toastr[type](msg);
                alert(msg);
            }

            function setExportLoading(isLoading) {
                const $btn = $('#btnExport');
                const $text = $btn.find('.btn-text');
                if (isLoading) {
                    $btn.addClass('disabled')
                        .attr('aria-disabled', 'true')
                        .css('pointer-events', 'none');
                    if ($text.length) $text.text('Exporting...');
                } else {
                    $btn.removeClass('disabled')
                        .attr('aria-disabled', 'false')
                        .css('pointer-events', '');
                    if ($text.length) $text.text('Export Excel');
                }
            }

            function getFilenameFromDisposition(cd) {
                if (!cd) return null;
                let m = cd.match(/filename\*\s*=\s*UTF-8''([^;]+)/i);
                if (m && m[1]) return decodeURIComponent(m[1].replace(/["']/g, '').trim());
                m = cd.match(/filename\s*=\s*("?)([^";]+)\1/i);
                if (m && m[2]) return m[2].replace(/["']/g, '').trim();

                return null;
            }
            $(document).off('click', '#btnExport').on('click', '#btnExport', async function(e) {
                e.preventDefault();

                const $btn = $(this);
                if ($btn.hasClass('disabled') || $btn.attr('aria-disabled') === 'true') return;

                setExportLoading(true);

                try {
                    const url = new URL("{{ url('/api/teacher/task-recap-export-all') }}");
                    let school_year_id = $('#school_year').val();
                    url.searchParams.append('school_year_id', school_year_id);

                    const res = await fetch(url, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const contentType = res.headers.get('content-type');

                    if (contentType && contentType.includes('application/json')) {
                        // Handle JSON response (error case)
                        const json = await res.json();

                        if (json.meta.code === 404) {
                            throw new Error(json.meta.message);
                        }
                    }

                    if (!res.ok) {
                        const txt = await res.json();
                        throw new Error(txt?.message || `HTTP ${res.status}`);
                    }

                    const blob = await res.blob();

                    let filename = getFilenameFromDisposition(res.headers.get('content-disposition')) ||
                        'rekap-tugas.zip';

                    const blobUrl = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    URL.revokeObjectURL(blobUrl);

                    toast('success', 'Export semua mapel berhasil.');
                } catch (err) {
                    toast('error', 'Export gagal: ' + (err.message || err));
                } finally {
                    setExportLoading(false);
                }
            });
            const $form = $('#rekapFilterForm');
            const $reset = $form.find('.rekap-btn-reset'); 
            const $year = $('#school_year');
            const defaultYear = $year.find('option[selected]').val() || $year.val();

            $reset.off('click').on('click', function(e) {
                e.preventDefault();
                if (defaultYear) $year.val(defaultYear).trigger('change');
                const cleanUrl = $form.attr('action') || window.location.pathname;
                window.location.href = cleanUrl;
            });
            const $tbody = $('#rekapTbody');
            const $pager = $('#rekapPagination');
            const perPage = 10;
            let currentPage = 1;

            function getRows() {
                const $rows = $tbody.find('tr');
                if ($rows.length === 1 && $rows.first().find('td[colspan]').length) return $();

                return $rows;
            }

            function renderPager(total, current) {
                $pager.empty();

                const totalPages = Math.ceil(total / perPage);
                if (totalPages <= 1) return;

                function item(label, page, disabled = false, active = false) {
                    const cls = `page-item${disabled ? ' disabled' : ''}${active ? ' active' : ''}`;
                    return $(`
        <li class="${cls}">
          <a class="page-link" href="#" data-page="${page}">${label}</a>
        </li>
      `);
                }

                $pager.append(item('Previous', current - 1, current === 1));

                const windowSize = 5;
                let start = Math.max(1, current - Math.floor(windowSize / 2));
                let end = Math.min(totalPages, start + windowSize - 1);
                start = Math.max(1, end - windowSize + 1);

                for (let p = start; p <= end; p++) {
                    $pager.append(item(String(p), p, false, p === current));
                }

                $pager.append(item('Next', current + 1, current === totalPages));
            }

            function goTo(page) {
                const $rows = getRows();
                const total = $rows.length;

                if (total === 0) {
                    $pager.empty();
                    return;
                }

                const totalPages = Math.max(1, Math.ceil(total / perPage));
                page = Math.max(1, Math.min(page, totalPages));
                currentPage = page;

                $rows.hide();
                $rows.slice((page - 1) * perPage, page * perPage).show();

                renderPager(total, page);
            }

            $(document).off('click', '#rekapPagination a.page-link')
                .on('click', '#rekapPagination a.page-link', function(e) {
                    e.preventDefault();

                    const $li = $(this).closest('.page-item');
                    if ($li.hasClass('disabled') || $li.hasClass('active')) return;

                    const page = parseInt($(this).data('page'), 10);
                    if (!Number.isFinite(page)) return;

                    goTo(page);
                });
            goTo(1);
        });
    </script>
@endsection
