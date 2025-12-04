<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        .absent  { background-color: #f4cccc; }
        .excused { background-color: #d9ead3; }
        .na      { background-color: #eaeaea; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Attendance Sheet</h2>
    <table>
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">Name</th>
                @foreach($dates as $date)
                    <th <?php if(count($periodMap)==2){ echo 'colspan="2"'; } ?>>{{ \Carbon\Carbon::parse($date)->format('M d') }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($dates as $date)
                    @foreach($periodMap as $period => $label)
                        <th>{{ $label }}</th>
                    @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach($students as $student)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $student['name'] }}</td>
                    @foreach($dates as $date)
                        @foreach($periodMap as $period => $label)
                            @php
                                $status = $student['data'][$date][$period] ?? 'NA';
                                $class = match($status) {
                                    'A' => 'absent',
                                    'EXC' => 'excused',
                                    default => 'na'
                                };
                            @endphp
                            <td class="{{ $class }}">{{ $status }}</td>
                        @endforeach
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
