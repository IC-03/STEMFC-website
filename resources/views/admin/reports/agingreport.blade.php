@extends('layouts.app')

@section('main-container')
<div class="container-fluid">
<table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Student</th>
                <th>Total</th>
                <th>Current</th>
                <th>Over 30 Days</th>
                <th>Over 60 Days</th>
                <th>Over 90 Days</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $row)
            <tr>
                <td>{{ $row->student_name }}</td>
                <td>{{ number_format($row->total_balance, 2) }}</td>
                <td>{{ number_format($row->current, 2) }}</td>
                <td>{{ number_format($row->over_30, 2) }}</td>
                <td>{{ number_format($row->over_60, 2) }}</td>
                <td>{{ number_format($row->over_90, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection

@push('scripts')
<script>

</script>

@push('styles')
<style>
  div.dataTables_wrapper .dt-buttons,
  div.dataTables_wrapper .dataTables_filter {
    padding-top: 13px; /* adjust this value as needed */
    padding-left: 5px;
    padding-right: 5px;
  }
</style>
@endpush

@endpush

