@extends('layouts.app')
@section('main-container')
<style>
        table { border-collapse: collapse; font-size: 12px; width: 100%; }
        th, td { border: 1px solid #999; padding: 4px 6px; text-align: center; }
        th.date-header { background-color: #eee; }
        .absent { background-color: #f4cccc; }
        .excused { background-color: #b6d7a8; }
        .present { background-color: #ffffff; }
        .na { background-color: #f0f0f0; }
    </style>
        <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
            <div class="col-lg-12">
                <!-- small box -->
               <h2>Attendance Calendar</h2>
<table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                @foreach ($dates as $date)
                    <th>{{ \Carbon\Carbon::parse($date)->format('M d') }}</th>
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
                        @php
                            $status = $student['data'][$date] ?? 'NA';
                            $class = match($status) {
                                'A' => 'absent',
                                'EXC' => 'excused',
                                'P' => 'present',
                                default => 'na'
                            };
                        @endphp
                        <td class="{{ $class }}">{{ $status }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
            </div>
      
</div>

    </div>

    @include('_message')

           
            <!-- /.row -->
            <!-- Main row -->
</section>


 @endsection