@php
    $maxTasks = 0;
    foreach ($data as $topic) {
        $taskCount = count($topic['tasks'] ?? []);
        if ($taskCount > $maxTasks) {
            $maxTasks = $taskCount;
        }
    }
@endphp

<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
    <!-- Header Row - will be styled blue by PHPExcel -->
    <tr>
        @foreach($data as $topic)
            <th colspan="2" style="text-align: center;">
                {{ $topic['topic']['title'] }}
            </th>
        @endforeach
    </tr>

    <!-- Subheader Row - will also be styled blue by PHPExcel -->
    <tr>
        @foreach($data as $topic)
            <th>Tugas No</th>
            <th>Nama Tugas</th>
        @endforeach
    </tr>

    @for($i = 0; $i < $maxTasks; $i++)
        <tr>
            @foreach($data as $topic)
                @if(isset($topic['tasks'][$i]))
                    <td>{{ $topic['tasks'][$i]['task_no'] }}</td>
                    <td>{{ $topic['tasks'][$i]['title'] }}</td>
                @else
                    <td> - </td>
                    <td> - </td>
                @endif
            @endforeach
        </tr>
    @endfor
</table>
