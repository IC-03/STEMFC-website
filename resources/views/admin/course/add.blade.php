@extends('layouts.app')
@section('main-container')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-danger">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa fa-book-medical"></i> Create New Course</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.course.add') }}" method="POST" id="upload-image" novalidate enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Course Name:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa fa-book"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="course_name" required placeholder="Course Name" value="{{ old('course_name') }}">
                                    </div>
                                    <div class="text-danger">{{ $errors->first('course_name') }}</div>
                                </div>
                                
                            </div>

                            <div class="form-row">
                                
                                <div class="form-group col-md-6" data-select2-id="42">
                                    <label>Group <small>(Select all that apply)</small>:</label>
                                    <div class="input-group">
                                        <select class="form-control select2" name="groupname[]" multiple>
                                            <option disabled value="">Select Group</option>
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->id }}"
                                                    {{ (collect(old('groupname'))->contains($group->id)) ? 'selected' : '' }}>
                                                    {{ $group->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="text-danger">{{ $errors->first('groupname') }}</div>
                                </div>
                            </div>
                            <div class="form-row float-right">
                                <button type="submit" class="btn btn-outline-danger"><i class="fas fa-save"></i> Save</button>
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

$(document).ready(function (e) {

    $('.select2').select2(); // Ensure this runs on add blade
    $('#image').change(function(){
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#image-preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    });
});



</script>
