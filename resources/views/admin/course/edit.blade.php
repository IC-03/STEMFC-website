@extends('layouts.app')
@section('main-container')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-edit"></i> Edit Course</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.course.update', $course->uuid) }}" method="POST" id="upload-image" novalidate enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Course Name:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="course_name" required placeholder="Course Name" value="{{ old('course_name', $course->course_name) }}">
                                    </div>
                                    <div class="text-danger">{{ $errors->first('course_name') }}</div>
                                </div>
                            </div>


                        <div class="form-row">
                            
                            <div class="form-group col-md-6">
                                <label>Group:</label>
                                <div class="input-group d-flex align-items-center">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-chalkboard" style='height:25px;'></i></span>
                                    </div>
                                    <select class="form-control select2" name="groupname[]" multiple>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}"
                                                {{ (in_array($group->id, old('groupname', $course->groups->pluck('id')->toArray()))) ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>


                                </div>
                                <div class="text-danger">{{ $errors->first('groupname') }}</div>
                            </div>
                        </div>

                        <div class="form-row float-right">
                            <button type="submit" class="btn btn-outline-warning"><i class="fas fa-save"></i> Update</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.card -->
        </div><!-- /.container-fluid -->
    </div>
</div>


</section>
@endsection

<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

<script type="text/javascript">
$(document).ready(function () {
    $('.select2').select2();
});
</script>
