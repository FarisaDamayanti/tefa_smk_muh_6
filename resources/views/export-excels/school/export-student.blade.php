<table>
    <tr>
        <td colspan="4">
            Dibuat pada {{ now()->translatedFormat('d F Y H:i') }}
        </td>
    </tr>

    <tr>
        <td colspan="4">
            DATA SISWA - {{ $title }}
        </td>
    </tr>

    <tr>
        <td>No</td>
        <td>NISN</td>
        <td>Nama</td>
        <td>Email</td>
    </tr>

    @forelse ($rows as $row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>'{{ $row['nisn'] }}</td>
            <td>{{ $row['name'] }}</td>
            <td>{{ $row['email'] }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4">Tidak ada siswa</td>
        </tr>
    @endforelse

    <tr>
        <td colspan="4"></td>
    </tr>

    <tr>
        <td colspan="4">
            Total Siswa&nbsp;:&nbsp;<span>{{ count($rows) }}</span>
        </td>
    </tr>
</table>