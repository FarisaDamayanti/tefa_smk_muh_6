@extends('student.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/student-subjects.css') }}">
@endsection

@section('content')
<div class="student-subjects-page">
    <div class="card bg-primary shadow-none position-relative overflow-hidden text-light mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-8 col-md-9">
                    <h4 class="fw-semibold mb-2 text-light">E - Learning</h4>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item text-light">
                            Manajemen kegiatan belajar mengajar dengan fitur ini
                        </li>
                    </ol>
                </div>
                <div class="col-4 col-md-3 text-center mb-n5">
                    <img src="{{ asset('admin_assets/dist/images/breadcrumb/ChatBc.png') }}" class="img-fluid mb-n4">
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 mb-4">
        <div class="row">
            <div class="col-lg-7 col-md-10 col-12">
                <form class="d-flex align-items-center gap-2 flex-nowrap" style="height: 40px;">
                    <input type="text" name="search" id="search" class="form-control fs-3 ps-3 h-100" placeholder="Cari..." value="{{ request('search') }}">
    
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
    
                    <button type="button" class="btn btn-outline-secondary h-100 d-flex align-items-center" id="reset-btn">
                        <i class="ti ti-refresh fs-4"></i>
                        <span class="d-none ms-2 d-md-block">Reset</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($subjects as $subject)
            <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                <x-authenticated.classroom-subject-card :classroom="$subject->level_class .' '. $subject->classroom_name" :subject="$subject->name" :school-year="$subject->school_year" :teacher="$subject->teacher_name" :target-url="route('student.e-learning.detail', [$subject->classroom_id, $subject->id])" />
            </div>
        @empty
            <div class="col-12">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt="" width="300px">
                    <p class="fs-5 text-dark text-center mt-2">
                        Belum ada data
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    @if (!empty($subjects) && $subjects->count())
    <div class="pagination justify-content-end mt-2 mb-0">
        <x-paginate-component :paginator="$subjects" />
    </div>
    @endif

</div>

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
    @include('student.pages.subjects.script.index')
@endsection
