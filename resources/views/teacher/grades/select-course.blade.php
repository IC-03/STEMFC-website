@extends('layouts.app')

@php $title = 'Select a Course to Grade'; @endphp

@section('main-container')
<div class="container-fluid mt-4">
  <div class="card">
    {{-- Card Header --}}
    <div class="card-header">
      <h3 class="card-title mb-0">
        Select a Course to Grade
      </h3>
    </div>

    {{-- Card Body --}}
    <div class="card-body">
      @if($courses->isEmpty())
        <div class="alert alert-info mb-0">
          You have not been assigned any courses yet.
        </div>
      @else
        <div class="row">
          @foreach($courses as $course)
            <div class="col-md-4 mb-3">
              <a
                href="{{ route('grades.bulk.form', $course->id) }}"
                class="card h-100 course-link"
                style="background-color: rgb(227, 226, 226); text-color: black; font-weight: bold; border: 1px solid rgb(112, 110, 110); border-radius: 5px;"
              >
                <div class="card-body d-flex align-items-center justify-content-center">
                  <span class="h5 mb-0 text-secondary course-name">{{ $course->course_name }}</span>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      @endif
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>

{{-- Custom Hover Effects --}}
<style>
  .course-link {
    transition: background-color 0.3s ease, transform 0.3s ease;
  }

  .course-link:hover {
    background-color: #e3f2fd; /* Light blue */
    transform: scale(1.03);
    text-decoration: none;
  }

  .course-link:hover .course-name {
    color: #007bff; /* Bootstrap primary blue */
    font-size: 1.25rem; /* Increase font size */
  }
</style>
@endsection
