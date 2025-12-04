@extends('layouts.app')

@php
  $title = "Edit Grade for {$grade->student->full_name}";
@endphp

@section('main-container')
<section class="content">
  <div class="container-fluid">
    <!-- Edit Grade Card -->
    <div class="card card-info mt-4">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-edit"></i>
          Edit Grade for {{ $grade->student->full_name }}
          <small class="text-muted">(Course: {{ $grade->course->course_name }})</small>
        </h3>
      </div>
      <!-- /.card-header -->

      <div class="card-body">
        @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
        @endif

        <form action="{{ route('grades.update', $grade->id) }}" method="POST">
          @csrf
          @method('PUT')

          {{-- Assessment Type --}}
          <div class="form-group">
            <label for="assessment_type" class="font-weight-bold">Assessment Type</label>
            <select id="assessment_type"
                    name="assessment_type"
                    class="form-control @error('assessment_type') is-invalid @enderror">
              <option value="">— Select —</option>
              <option value="test"
                {{ old('assessment_type', $grade->assessment_type) === 'test' ? 'selected' : '' }}>
                Test
              </option>
              <option value="classwork"
                {{ old('assessment_type', $grade->assessment_type) === 'classwork' ? 'selected' : '' }}>
                Classwork
              </option>
              <option value="homework"
                {{ old('assessment_type', $grade->assessment_type) === 'homework' ? 'selected' : '' }}>
                Homework
              </option>
            </select>
            @error('assessment_type')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          {{-- Percentage --}}
          <div class="form-group">
            <label for="percentage" class="font-weight-bold">Grade (0–5)</label>
            <input type="number"
				   step="any"
                   id="percentage"
                   name="percentage"
            
                   value="{{ old('percentage', $grade->grade) }}"
                   class="form-control @error('percentage') is-invalid @enderror">
            @error('percentage')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          {{-- Current Grade (readonly) --}}
          <div class="form-group" style="display: none;">
            <label for="current_grade" class="font-weight-bold">Current Grade</label>
            <input type="text"
                   id="current_grade"
                   class="form-control"
                   value="{{ number_format($grade->grade, 1) }}"
                   readonly>
          </div>

          {{-- Pass/Fail Status --}}
          <div class="form-group">
            <label class="font-weight-bold">Status</label>
            @if($grade->grade >= 3.5)
              <span class="badge badge-success">Pass</span>
            @else
              <span class="badge badge-danger">Fail</span>
            @endif
          </div>

          {{-- Date --}}
          <div class="form-group">
            <label for="date" class="font-weight-bold">Date</label>
            <input type="date"
                   id="date"
                   name="date"
                   value="{{ old('date', $grade->date) }}"
                   class="form-control @error('date') is-invalid @enderror">
            @error('date')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          {{-- Form Footer --}}
          <div class="card-footer text-right">
            <a href="{{ route('grades.view') }}" class="btn btn-secondary mr-2">
              <i class="fas fa-arrow-left"></i> Back to Grades
            </a>
            <button type="submit" class="btn btn-info">
              <i class="fas fa-save"></i> Update Grade
            </button>
          </div>
        </form>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.container-fluid -->
</section>
@endsection
