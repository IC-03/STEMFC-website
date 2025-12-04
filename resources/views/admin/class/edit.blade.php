@extends('layouts.app')
@section('main-container')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-edit"></i> Edit Class</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.class.update', $class->uuid) }}" method="POST">
                            @csrf
                            @method('PUT')

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Class Name:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-chalkboard"></i></span>
                                    </div>
                                    <input
                                      type="text"
                                      class="form-control @error('name') is-invalid @enderror"
                                      name="name"
                                      required
                                      placeholder="Class Name"
                                      value="{{ old('name', $class->name) }}"
                                    >
                                </div>
                                <div class="text-danger">{{ $errors->first('name') }}</div>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Professors:</label>
                                <div class="input-group">
                                    <select
                                      class="form-control select2 @error('teacher_ids') is-invalid @enderror"
                                      name="teacher_ids[]"
                                      multiple
                                      style="width: 100%;"
                                    >
                                      @foreach ($teachers as $teacher)
                                        <option
                                          value="{{ $teacher->id }}"
                                          {{ in_array($teacher->id, old('teacher_ids', $class->teachers->pluck('id')->toArray())) ? 'selected' : '' }}
                                        >
                                          {{ $teacher->first_name }} {{ $teacher->last_name }}
                                        </option>
                                      @endforeach
                                    </select>
                                </div>
                                <small class="form-text text-muted">Hold Ctrl (Cmd) to select multiple</small>
                                <div class="text-danger">{{ $errors->first('teacher_ids') }}</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Ability:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lightbulb"></i></span>
                                    </div>
                                    <input
                                      type="number"
                                      min="0"
                                      class="form-control @error('ability') is-invalid @enderror"
                                      name="ability"
                                      value="{{ old('ability', $class->ability) }}"
                                      placeholder="Enter any positive number"
                                    >
                                </div>
                                <div class="text-danger">{{ $errors->first('ability') }}</div>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Status:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-check-square"></i></span>
                                    </div>
                                    <select
                                      name="status"
                                      class="form-control @error('status') is-invalid @enderror"
                                      required
                                    >
                                        <option value="1" {{ old('status', $class->status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $class->status) == 0 ? 'selected' : '' }}>Inactive</option>
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
                                    <input
                                      type="date"
                                      class="form-control @error('startdate') is-invalid @enderror"
                                      name="startdate"
                                      value="{{ old('startdate', optional($class->start_date)->format('Y-m-d')) }}"
                                      required
                                    >
                                </div>
                                <div class="text-danger">{{ $errors->first('startdate') }}</div>
                            </div>

                            <div class="form-group col-md-6">
                                <label>End Date:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input
                                      type="date"
                                      class="form-control @error('enddate') is-invalid @enderror"
                                      name="enddate"
                                      value="{{ old('enddate', optional($class->end_date)->format('Y-m-d')) }}"
                                      required
                                    >
                                </div>
                                <div class="text-danger">{{ $errors->first('enddate') }}</div>
                            </div>
                        </div>

                        <div class="form-row mt-3">
                            <div class="col text-right">
                                <button type="submit" class="btn btn-outline-warning"><i class="fas fa-save"></i> Update</button>
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

<script>
$(document).ready(function () {
    $('.select2').select2({
        placeholder: 'Select Professors',
        allowClear: true
    });
});
</script>

@endpush
