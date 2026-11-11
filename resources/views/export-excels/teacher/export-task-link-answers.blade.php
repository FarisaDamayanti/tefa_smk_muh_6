<table>
    <tr><td>Kelas: {{ $data['classroom']->name }}</td></tr>
    <tr><td>Tugas: {{ $data['task']->title }}</td></tr>

    <tr></tr>

    <tr>
        <td>No</td>
        <td>Nama Murid</td>
        <td>Link Pengumpulan</td>
    </tr>

    @foreach ($data['submissions'] as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->student->user->name }}</td>
            <td>{{ $item->answer }}</td>
        </tr>
    @endforeach
</table>
