@extends('teacher.layouts.app')

@section('content')

    {{-- HEADER --}}
    <div class="card bg-primary shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h3 class="fw-semibold text-white">E-Learning</h3>
                    <h6 class="fw-semibold text-white mb-2">
                        Manajemen kegiatan belajar mengajar dengan fitur ini
                    </h6>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('admin_assets/dist/images/breadcrumb/ChatBc.png') }}" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="mt-4 mb-4">
        <div class="row">
            <div class="col-lg-7 col-12">
                <form class="d-flex align-items-center gap-2 flex-nowrap" style="height: 40px;">
                    <div class="search-wrap bg-white rounded-3 w-100">
                        <span class="search-icon text-muted">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M11 18C14.866 18 18 14.866 18 11C18 7.13401 14.866 4 11 4C7.13401 4 4 7.13401 4 11C4 14.866 7.13401 18 11 18Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <input type="text" class="form-control search-input" name="search" id="search"
                            placeholder="Cari..." value="{{ request('search') }}">
                    </div>
        
                    <select name="school_year_id" class="form-select d-none d-md-block h-100">
                        @forelse ($schoolYears as $year)
                            <option value="{{ $year->id }}" @if (request()->filled('school_year_id') && request('school_year_id') == $year->id) selected
                                @elseif (!request()->filled('school_year_id') && $year->active == 1)
                                selected @endif>
                                {{ $year->school_year }}
                            </option>
                        @empty
                        <option value="" disabled>Tidak ada tahun ajaran</option>
                        @endforelse
                    </select>
        
                    <div class="d-md-block d-none h-100">
                        <button type="submit" class="btn btn-primary h-100 d-flex align-items-center">
                            <i class="ti ti-filter"></i>
                            <span class="d-none ms-2 d-md-block">Filter</span>
                        </button>
                    </div>
        
                    <div class="d-md-none d-block h-100">
                        <button type="button" class="btn btn-primary rounded-2 h-100" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="ti ti-filter"></i>
                        </button>
                    </div>
        
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary h-100 d-flex align-items-center" id="reset-btn">
                        <i class="ti ti-refresh fs-4"></i>
                        <span class="d-none ms-2 d-md-block">Reset</span>
                    </a>
                </form>
            </div>
        </div>
    </div>

    {{-- CARD LIST --}}
    @if ($subjects->count())
        <div class="row g-4">
            @php $rendered = 0; @endphp

            @foreach ($subjects as $classroom)
                @php
                    $subjectId = data_get($classroom, 'subject_id');
                    $classroomId = data_get($classroom, 'classroom_id');
                @endphp

                @if ($subjectId && $classroomId)
                    @php $rendered++; @endphp
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <x-authenticated.classroom-subject-card
                            :classroom="$classroom->level_class . ' ' . $classroom->classroom_name"
                            :subject="$classroom->subject_name"
                            :school-year="$classroom->school_year"
                            :total-students="$classroom->total_students"
                            :target-url="route('teacher.e-learning.detail', [
                                'classroom' => $classroomId,
                                'subject' => $subjectId,
                            ])" />
                    </div>
                @endif
            @endforeach

            @if ($rendered === 0)
                <div class="d-flex flex-column justify-content-center align-items-center py-5 w-100">
                    <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt="No Data" width="300" class="mb-3">
                    <p class="fs-5 text-dark text-center mb-0">Belum ada data</p>
                </div>
            @endif
        </div>
    @else
        <div class="d-flex flex-column justify-content-center align-items-center py-5">
            <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt="No Data" width="300" class="mb-3">
            <p class="fs-5 text-dark text-center mb-0">Belum ada data</p>
        </div>
    @endif

    <div class="d-flex justify-content-end mt-5">
        <x-paginate-component :paginator="$subjects" />
    </div>

    {{-- MODAL FILTER (Mobile) --}}
    <x-dynamic-modal-filter 
        :title="'Filter Mapel dan Kelas'"
        :filters="[
            'school_year_id' => [
                'type' => 'select',
                'id' => 'school_year_id-modal',
                'options' => $schoolYears->pluck('id', 'school_year')->toArray(),
                'label' => 'Tahun ajaran',
                'value' => $schoolYears->where('active', 1)->first()?->id
            ],
        ]"
        :hiddens="[
            'search' => 'search-modal',
        ]"
    />

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#search').on('input', function() {
                let search = $(this).val();
                $('#search-modal').val(search);
            })
        })
    </script>
@endsection