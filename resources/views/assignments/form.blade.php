@extends('layouts.app')

@section('main-container')
<div class="container mt-5 col-md-12">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ $assignment ? 'Edit Assignment' : 'Add New Assignment' }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ $assignment ? route('assignments.update', $assignment) : route('assignments.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if($assignment) @method('PUT') @endif

                <div class="form-group">
                    <label for="name">Assignment Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ old('name', $assignment->name ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="course_id">Select Course</label>
                    <select class="form-control select2bs4" id="course_id" name="course_id" required>
                        <option value="">-- Choose Course --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}"
                                {{ (old('course_id', $assignment->course_id ?? '') == $course->id) ? 'selected' : '' }}>
                                {{ $course->course_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="file">Upload File</label>
                    <input type="file" class="form-control-file" id="file" name="file" {{ $assignment ? '' : 'required' }}>
                    @if($assignment && $assignment->file_path)
                        <small class="form-text text-muted">
                            Current file: <a href="{{ asset('public/assignments/'. $assignment->file_path) }}" target="_blank">View</a>
                        </small>
                    @endif
                </div>

                <div class="text-right">
                    <a href="{{ route('assignments.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">
                        {{ $assignment ? 'Update Assignment' : 'Upload Assignment' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
