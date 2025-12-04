@extends('layouts.app')
@section('main-container')

@if($subjects->isEmpty())
    <div class="alert alert-info">
      You are not assigned to any subject yet.
    </div>
@else
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Subject Name</th>
            <th>Class(es)</th>
            <th>Code</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          @foreach($subjects as $subject)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $subject->course_name }}</td>

              {{-- Only show those groups of this course for which the professor_id matches the current user --}}
              <td>
                @php
                  $myGroups = $subject->groups;
                @endphp

                @if($myGroups->isNotEmpty())
                @foreach($myGroups as $g)
                    <span class="badge badge-primary">{{ $g->name }}</span>
                @endforeach
                @else
                <span class="text-muted">N/A</span>
                @endif

              </td>

              <td>{{ $subject->course_code }}</td>
              <td>{{ $subject->created_at->format('M d, Y') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
@endif

@endsection
