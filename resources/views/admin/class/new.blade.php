@extends('layouts.app')
@section('main-container')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chalkboard"></i> Create New Class</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.class.add') }}" method="POST" id="upload-image" novalidate enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Class Name:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-chalkboard"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="name" required placeholder="Class Name" value="{{ old('name') }}">
                                    </div>
                                    <div class="text-danger">{{ $errors->first('name') }}</div>
                                </div>


                           
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Ability:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lightbulb"></i></span>
                                    </div>
                                    <input type="number" min="0" class="form-control" name="ability" value="{{ old('ability') }}" placeholder="Enter any positive Number">
                                </div>
                                <div class="text-danger">{{ $errors->first('ability') }}</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Status:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-check-square"></i></span>
                                    </div>
                                    <select name="status" class="form-control" required>
                                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="text-danger">{{ $errors->first('status') }}</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Start Date:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="date" class="form-control" name="startdate" value="{{ old('startdate') }}" required>
                                </div>
                                <div class="text-danger">{{ $errors->first('startdate') }}</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>End Date:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="date" class="form-control" name="enddate" value="{{ old('enddate') }}" required>
                                </div>
                                <div class="text-danger">{{ $errors->first('enddate') }}</div>
                            </div>
                        </div>

                        <div class="form-row mt-3">
                            <div class="col text-right">
                                <button type="submit" class="btn btn-outline-info"><i class="fas fa-save"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.card -->
        </div>
    </div>
</div>


</section>
@endsection

@push('scripts')

<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

<script>
$(document).ready(function () {
    // Initialize Select2 for multiple selection
    $('.select2').select2({
        placeholder: 'Select Professors',
        allowClear: true
    });
});
</script>

@endpush
