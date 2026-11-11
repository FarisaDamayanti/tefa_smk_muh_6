<table>
    <tr>
        <td colspan="6">
            Dibuat pada {{ now()->translatedFormat('d F Y H:i') }}
        </td>
    </tr>

    @if (!empty($filtering))
        <tr>
            <td colspan="6">
                Tahun ajaran&nbsp;:&nbsp;{{ $filtering['school_year'] }}&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                Semester&nbsp;:&nbsp;{{ $filtering['semester'] }}&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                Tingkat Kelas&nbsp;:&nbsp;{{ $filtering['level_class'] }}&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                Mata Pelajaran&nbsp;:&nbsp;{{ $filtering['subject'] }}
            </td>
        </tr>
    @endif

    <tr>
        <td colspan="6">
            NILAI UJIAN - {{ $title }}
        </td>
    </tr>

    <tr>
        <td>No</td>
        <td>NISN</td>
        <td>Nama</td>
        <td>Kelas</td>
        <td>Nilai</td>
        <td>Status</td>
    </tr>

    @forelse ($rows as $row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>'{{ $row['nisn'] }}</td>
            <td>{{ $row['name'] }}</td>
            <td>{{ $row['class'] }}</td>
            <td>{{ $row['score'] }}</td>
            <td>{{ $row['status'] }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="6">Tidak ada siswa</td>
        </tr>
    @endforelse

    <tr>
        <td colspan="6"></td>
    </tr>

    <tr>
        <td colspan="6">
            Total Siswa&nbsp;:&nbsp;<span>{{ count($rows) }}</span>
        </td>
    </tr>
</table>