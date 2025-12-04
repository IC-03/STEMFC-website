@extends('layouts.app')
@section('main-container')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa fa-notes-medical fa-lg"></i> Create New Registration</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.registration.add') }}" method="POST" id="upload-image" novalidate enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Registration Date:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                        </div>
                                        <input type="date" class="form-control" name="regdate" value="{{ old('regdate') }}" required>
                                    </div>
                                    <div class="text-danger">{{ $errors->first('regdate') }}</div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Class (Group):</label>
                                    <div class="input-group d-flex align-items-center">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-chalkboard" style='height:25px;'></i></span>
                                        </div>
                                        <select class="form-control select2" name="group_id" required>
                                        <option disabled selected value="">Select Class</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}"
                                            {{ old('groupname') == $group->id ? 'selected' : '' }}>
                                            {{ $group->name }}
                                            </option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="text-danger">{{ $errors->first('groupname') }}</div>
                                </div>

                            </div>


                        <div class="form-row">
                            <div class="form-group col-md-6" data-select2-id="40">
                                <label>Student Name:</label>
                                <div class="input-group">
                                    <select class="form-control select2 select2-hidden-accessible" name="stdname" required style="width: 100%;">
                                        <option disabled selected value="">Select Student</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ old('stdname') == $user->id ? 'selected' : '' }}>
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-danger">{{ $errors->first('stdname') }}</div>
                            </div>
                            <div class="form-group col-md-6" data-select2-id="42">
                                <label>Course Name:</label>
                                <div class="input-group">
                                    <select class="form-control select2 select2-hidden-accessible" name="coursename" required style="width: 100%;">
                                        <option disabled selected value="">Select Course</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('coursename') == $course->id ? 'selected' : '' }}>
                                                {{ $course->course_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-danger">{{ $errors->first('coursename') }}</div>
                            </div>
                        </div>
                        <div class="form-row float-right">
                            <button type="submit" class="btn btn-outline-warning"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.card -->
        </div><!-- /.container-fluid -->
    </div>
</div>


</section>
@endsection

{{-- Original image preview script remains unchanged below --}}

<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

<script type="text/javascript">
$(document).ready(function () {
    $('#image').change(function() {
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#image-preview').attr('src', e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
    });
});
</script>
