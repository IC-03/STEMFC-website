@extends('layouts.app')

@section('main-container')

<section class="content" style="background-color:white; padding:10px;">
    <div class="container-fluid">

        {{-- Filter form --}}
        <form method="GET" action="{{ route('admin.attendance.index') }}" class="mb-4">
            <div class="form-row align-items-end">
                <div class="form-group col-md-2">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All</option>
                        @foreach(['Present','Absent','Excused'] as $st)
                            <option value="{{ $st }}" {{ $statusFilter === $st ? 'selected' : '' }}>
                                {{ $st }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label>Student</label>
                    <input type="text" name="name" value="{{ $nameFilter }}" class="form-control" placeholder="Name">
                </div>

                <div class="form-group col-md-2">
                    <label>ID No.</label>
                    <input type="text"
                        name="id_no"
                        value="{{ $idNoFilter }}"
                        class="form-control"
                        placeholder="ID Number">
                </div>



                <div class="form-group col-md-2">
                    <label>Class</label>
                    <select name="group_id" class="form-control">
                        <option value="">All</option>
                        @foreach($groups as $id => $grp)
                            <option value="{{ $id }}" {{ $groupFilter == $id ? 'selected' : '' }}>
                                {{ $grp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label>Course</label>
                    <select name="course_id" class="form-control">
                        <option value="">All</option>
                        @foreach($courses as $id => $course)
                            <option value="{{ $id }}" {{ $courseFilter == $id ? 'selected' : '' }}>
                                {{ $course->course_name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group col-md-2">
                    <label>From</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control">
                </div>

                <div class="form-group col-md-2">
                    <label>To</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control">
                </div>

                <div class="form-group col-md-12 text-right mt-3">
                    <button type="submit" class="btn btn-primary mr-2">Filter</button>
                    <a href="{{ route('admin.attendance.export', request()->all()) }}" class="btn btn-success">
                        Download File
                    </a>
                </div>
            </div>
        </form>

        {{-- Attendance Table --}}
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID No.</th>
                    <th>Date</th>
                    <th>Course</th>
                    <th>Period</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Status</th>
                    <th>Teacher(s)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ $attendances->firstItem() + $loop->index }}</td>
                        <td>{{ $attendance->user->id_no ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}</td>
                        <td>
                            {{ optional($attendance->course)->course_name ?? 'N/A' }}
                        </td>
                        <td>{{ $attendance->period }}</td>
                        <td>
                            {{ $attendance->user->first_name ?? 'N/A' }}
                            {{ $attendance->user->last_name ?? '' }}
                        </td>
                        <td>{{ $attendance->group->name ?? 'N/A' }}</td>
                        <td>{{ $attendance->attendance_status }}</td>
                        <td>
                            {{ optional($attendance->teacher)->first_name ?? 'N/A' }}
                            {{ optional($attendance->teacher)->last_name ? optional($attendance->teacher)->last_name : '' }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="9">No attendance records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $attendances
                ->appends(request()->only(['status','name','id_no','group_id','course_id','date_from','date_to']))
                ->links('pagination::bootstrap-4') }}
        </div>

    </div>
</section>
@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const groups = @json($groups->toArray());
    const summary = @json(
        $summary->map(function($item) {
            return $item->keyBy('attendance_status')->map->total;
        })->toArray()
    );

    const labels = Object.keys(groups).map(id => groups[id].name);
    const presentData = labels.map((_, i) => summary[Object.keys(groups)[i]]?.Present ?? 0);
    const absentData  = labels.map((_, i) => summary[Object.keys(groups)[i]]?.Absent  ?? 0);
    const excusedData = labels.map((_, i) => summary[Object.keys(groups)[i]]?.Excused ?? 0);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: 'Present', data: presentData },
                { label: 'Absent',  data: absentData  },
                { label: 'Excused', data: excusedData }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
