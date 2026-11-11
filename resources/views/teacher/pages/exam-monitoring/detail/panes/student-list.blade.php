   <div class="my-4">
       <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
           <form method="GET" class="d-flex align-items-center gap-3 flex-wrap flex-grow-1">

               <div class="search-wrap bg-white rounded-3">
                   <span class="search-icon text-muted">
                       <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                           xmlns="http://www.w3.org/2000/svg">
                           <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" />
                           <path
                               d="M11 18C14.866 18 18 14.866 18 11C18 7.13401 14.866 4 11 4
                                 C7.13401 4 4 7.13401 4 11C4 14.866 7.13401 18 11 18Z"
                               stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                       </svg>
                   </span>
                   <input type="text" class="form-control search-input" name="search" placeholder="Cari..."
                       value="{{ request('search') }}">
               </div>

               <select class="form-select filter-select bg-white w-100" name="sortBy">
                   <option value="">Urutkan berdasarkan</option>
                   <option value="highest" {{ request('sortBy') == 'highest' ? 'selected' : '' }}>
                       Nilai Tertinggi
                   </option>
                   <option value="lowest" {{ request('sortBy') == 'lowest' ? 'selected' : '' }}>
                       Nilai Terendah
                   </option>
               </select>

                <button type="button"
                    class="btn btn-primary" id="btn-set-pagination"
                    data-type="{{ $studentExams instanceof \Illuminate\Pagination\LengthAwarePaginator ? 'pagination' : 'all' }}"
                    >
                    @if ($studentExams instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <span>Tampilkan Semua</span>
                    @else
                        <span>Tampilkan Paginasi</span>
                    @endif
                </button>

               <button type="submit" class="btn btn-primary ">
                   <svg width="13" height="14" viewBox="0 0 13 14" fill="none"
                       xmlns="http://www.w3.org/2000/svg">
                       <path
                           d="M0.75 0.75H11.75V2.28318C11.7499 2.65757 11.605 3.0166 11.3471 3.28129
                            L8.3125 6.39706V11.3382L4.1875 12.75V6.75L1.1075 3.27141
                            C0.877495 3.0116 0.750037 2.6731 0.75 2.322V0.75Z"
                           stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                   </svg>
                   <span>Filter</span>
               </button>

               <a href="{{ url()->current() . (request()->has('per_page') ? '?per_page=' . request('per_page') : '') }}" class="btn btn-outline-primary">
                   <i class="ti ti-refresh me-1"></i> Reset
               </a>
           </form>

           <div class="d-flex align-items-center gap-2">
               <button type="button" class="btn btn-success gap-1 d-flex align-items-center" id="export-button">
                   <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 48 48">
                       <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="4">
                           <path stroke-linejoin="round"
                               d="M8 15V6a2 2 0 0 1 2-2h28a2 2 0 0 1 2 2v36a2 2 0 0 1-2 2H10a2 2 0 0 1-2-2v-9" />
                           <path d="M31 15h3m-6 8h6m-6 8h6" />
                           <path stroke-linejoin="round" d="M4 15h18v18H4zm6 6l6 6m0-6l-6 6" />
                       </g>
                   </svg>
                   Ekspor Ujian
               </button>
           </div>

       </div>
   </div>
   <h4 class="mb-3">Daftar Siswa Mengikuti Ujian</h4>
   <div class="card shadow-md border border-1 mb-4">
       <div class="card-body p-4">
           <div class="table-responsive rounded-2 mb-4">
               <table class="table border text-nowrap customize-table mb-0 align-middle">
                   <thead class="text-dark fs-4">
                       <tr class="">
                           <th class="text-white bg-primary">No</th>
                           <th class="text-white bg-primary">Nama</th>
                           <th class="text-white bg-primary text-center">Keterangan</th>
                           <th class="text-white bg-primary text-center">Nilai</th>
                           <th class="text-white bg-primary text-center">Aksi</th>
                       </tr>
                   </thead>
                   <tbody>
                       @forelse ($studentExams as $studentExam)
                           <tr>
                               @if ($studentExams instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    <td>{{ ($studentExams->firstItem() ?? 0) + $loop->index }}</td>
                                @else
                                    <td>{{ $loop->iteration }}</td>
                                @endif
                               <td>{{ $studentExam['name'] ?? '-' }}</td>
                               <td class="text-center">

                                   @if (strtolower($studentExam['status'] ?? '') === 'lulus')
                                       <div class="px-3 badge bg-light-success text-success text-capitalize">
                                           {{ $studentExam['status'] }}
                                       </div>
                                   @elseif (strtolower($studentExam['status'] ?? '') === 'tidak lulus')
                                       <div class="px-3 badge bg-light-danger text-danger text-capitalize">
                                           {{ $studentExam['status'] }}
                                       </div>
                                   @elseif (strtolower($studentExam['status'] ?? '') === 'sedang mengerjakan')
                                       <div class="px-3 badge bg-light-warning text-warning text-capitalize">
                                           {{ $studentExam['status'] }}
                                       </div>
                                   @elseif (strtolower($studentExam['status'] ?? '') === 'tidak mengerjakan')
                                       <div class="px-3 badge bg-light text-muted text-capitalize">
                                           {{ $studentExam['status'] }}
                                       </div>
                                   @else
                                       <div class="px-3 badge bg-light-secondary text-secondary text-capitalize">
                                           {{ $studentExam['status'] ?? '-' }}
                                       </div>
                                   @endif

                               </td>

                               <td class="text-center">
                                   <div class="text-primary">
                                        @php
                                            $status = strtolower($studentExam['status'] ?? '');
                                            $score = '-';

                                            if ($status !== 'belum dinilai'
                                                && !in_array($status, ['belum mengerjakan', 'tidak mengerjakan'])
                                                && isset($studentExam['score'])) {
                                                $score = $studentExam['score'];
                                            }
                                        @endphp

                                        {{ $score }}
                                    </div>
                               </td>
                               <td class="text-center">

                                   @if (!in_array(strtolower($studentExam['status'] ?? ''), ['belum mengerjakan', 'tidak mengerjakan']) &&
                                           !empty($studentExam['student_exam_id']))
                                       <a href="{{ route('teacher.exam-monitoring.student-detail', ['studentExam' => $studentExam['student_exam_id']]) }}"
                                           class="btn btn-primary btn-sm px-4">
                                           Detail
                                       </a>
                                   @else
                                       -
                                   @endif
                               </td>
                           </tr>
                       @empty
                           <tr>
                               <td colspan="6" class="text-center align-middle">
                                   <div class="d-flex flex-column justify-content-center align-items-center py-4">
                                       <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}"
                                           alt="No Data" width="250">
                                       <p class="fs-5 text-dark text-center mt-3 mb-0">
                                           Data siswa tidak tersedia
                                       </p>
                                   </div>
                               </td>
                           </tr>
                       @endforelse
                   </tbody>
               </table>
               <div class="pagination justify-content-end mb-0">
                    @if ($studentExams instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <x-paginate-component :paginator="$studentExams
                            ->withPath(request()->url())
                            ->appends(array_merge(request()->query(), ['tab' => 'student-answer']))" />
                    @endif
               </div>
           </div>
       </div>
   </div>
