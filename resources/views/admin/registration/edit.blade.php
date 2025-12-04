@extends('layouts.app')
@section('main-container')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-edit"></i> Edit Registration
            </h3>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.registration.update', $registration->uuid) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Student:</label>
                  <select name="user_id" class="form-control select2" required>
                    <option value="">Select Student</option>
                    @foreach($students as $student)
                      <option value="{{ $student->id }}" {{ old('user_id', $registration->user_id) == $student->id ? 'selected' : '' }}>
                        {{ $student->full_name }}
                      </option>
                    @endforeach
                  </select>
                  <div class="text-danger">{{ $errors->first('user_id') }}</div>
                </div>

                <div class="form-group col-md-6">
                  <label>Class:</label>
                  <select name="group_id" class="form-control select2" required>
                    <option value="">Select Class</option>
                    @foreach($groups as $group)
                      <option value="{{ $group->id }}" {{ old('group_id', $registration->group_id) == $group->id ? 'selected' : '' }}>
                        {{ $group->name }}
                      </option>
                    @endforeach
                  </select>
                  <div class="text-danger">{{ $errors->first('group_id') }}</div>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Course:</label>
                  <select name="course_id" class="form-control select2" required>
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                      <option value="{{ $course->id }}" {{ old('course_id', $registration->course_id) == $course->id ? 'selected' : '' }}>
                        {{ $course->course_name }}
                      </option>
                    @endforeach
                  </select>
                  <div class="text-danger">{{ $errors->first('course_id') }}</div>
                </div>

                <div class="form-group col-md-6">
                    <label>Registration Date:</label>
                    <input
                      type="date"
                      class="form-control"
                      name="reg_date"
                      value="{{
                        old(
                          'reg_date',
                          // if registration has a real date, format it; otherwise use today
                          $registration->reg_date
                            ? \Carbon\Carbon::parse($registration->reg_date)->format('Y-m-d')
                            : now()->format('Y-m-d')
                        )
                      }}"
                      required
                    >
                    <div class="text-danger">{{ $errors->first('reg_date') }}</div>
                  </div>


              </div>

              <div class="form-row float-right">
                <button type="submit" class="btn btn-outline-warning float-right">
                  <i class="fas fa-save"></i> Update
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
