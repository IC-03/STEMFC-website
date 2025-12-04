<br><h4>Calendar</h4>  <hr>
<div class="card-body table-responsive p-0">
<table class="table table-bordered table-head-fixed text-nowrap">
    <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Name</th>
            @foreach ($dates as $date)
                <th colspan="2" class="date-header">
                    {{ \Carbon\Carbon::parse($date)->format('M d') }}
                </th>
            @endforeach
        </tr>
        <tr>
            @foreach ($dates as $date)
                @foreach ($periodMap as $period => $label)
                    <th>{{ $label }}</th>
                @endforeach
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp
        @foreach ($students as $student)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $student['name'] }}</td>
                @foreach ($dates as $date)
                    @foreach ($periodMap as $period => $label)
                        @php
                            $status = $student['data'][$date][$period] ?? 'NA';
                            $class = match($status) {
                                'P' => 'present',
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
</div>