<h4 class="mb-3">Jam Pelajaran</h4>
<div class="table-responsive rounded-2">
    <table class="table border text-nowrap customize-table mb-0 align-middle">
        <thead>
            <tr>
                <th class="text-white bg-primary">No</th>
                <th class="text-white bg-primary">Jam</th>
                <th class="text-white bg-primary">Penempatan</th>
                <th class="text-white bg-primary">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lessonHours['friday'] ?? [] as $index => $lessonHour)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <span class="badge {{ $lessonHour->name == 'Istirahat' ? 'bg-light-warning text-warning' : 'bg-light-primary text-primary' }}">
                        {{ date('H:i', strtotime($lessonHour->start)) }} - {{ date('H:i', strtotime($lessonHour->end)) }}
                    </span>
                </td>
                <td>{{ $lessonHour->name }}</td>
                <td>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light-primary text-primary btn-sm btn-edit"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-update-lesson-hour"
                            data-id="{{ $lessonHour->id }}"
                            data-start="{{ date('H:i', strtotime($lessonHour->start)) }}"
                            data-end="{{ date('H:i', strtotime($lessonHour->end)) }}"
                            data-name="{{ $lessonHour->name }}">
                            Edit
                        </button>
                        <button class="btn btn-light-danger text-danger btn-sm btn-delete"
                            data-id="{{ $lessonHour->id }}">
                            Hapus
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center align-middle">
                    <div class="d-flex flex-column justify-content-center align-items-center">
                        <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt="" width="300px">
                        <p class="fs-5 text-dark text-center mt-2">Jam Pelajaran belum ditambahkan</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>