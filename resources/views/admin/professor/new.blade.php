@extends('layouts.app')
@section('main-container')
@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="far fa fa-user-plus"></i> Create New Professor</h3>
                    </div>
                    <div class="card-body">

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                          <div class="alert alert-danger">
                            <ul class="mb-0">
                              @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                              @endforeach
                            </ul>
                          </div>
                        @endif

                        <form action="{{ route('admin.professor.add') }}" method="POST" id="upload-image" novalidate enctype="multipart/form-data">
                            @csrf

                            <!-- Name Row -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>First Name:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-user"></i></span>
                                        </div>
                                        <input
                                          type="text"
                                          class="form-control @error('first_name') is-invalid @enderror"
                                          name="first_name"
                                          required
                                          placeholder="First Name"
                                          value="{{ old('first_name') }}"
                                        >
                                    </div>
                                    <div class="text-danger">{{ $errors->first('first_name') }}</div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Last Name:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-user"></i></span>
                                        </div>
                                        <input
                                          type="text"
                                          class="form-control @error('last_name') is-invalid @enderror"
                                          name="last_name"
                                          placeholder="Last Name"
                                          value="{{ old('last_name') }}"
                                        >
                                    </div>
                                    <div class="text-danger">{{ $errors->first('last_name') }}</div>
                                </div>
                            </div>

                            <!-- Contact & Credentials Row -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Phone:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone-volume"></i></span>
                                        </div>
                                        <input
                                          type="tel"
                                          class="form-control @error('telephone') is-invalid @enderror"
                                          name="telephone"
                                          value="{{ old('telephone') }}"
                                          inputmode="text"
                                        >
                                    </div>
                                    <div class="text-danger">{{ $errors->first('telephone') }}</div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>ID Number (National):</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-at"></i></span>
                                        </div>
                                        <input
                                          type="number"
                                          class="form-control @error('id_no') is-invalid @enderror"
                                          name="id_no"
                                          placeholder="ID Number"
                                          value="{{ old('id_no') }}"
                                          required
                                        >
                                    </div>
                                    <div class="text-danger">{{ $errors->first('id_no') }}</div>
                                </div>
                            </div>

                            <!-- Gender & Password Row -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Gender:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        </div>
                                        <select
                                          name="gender"
                                          class="form-control @error('gender') is-invalid @enderror"
                                          required
                                        >
                                            <option value="" disabled selected>Select Gender</option>
                                            <option value="male"   {{ old('gender')=='male'   ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender')=='female' ? 'selected' : '' }}>Female</option>
                                            <option value="other"  {{ old('gender')=='other'  ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="text-danger">{{ $errors->first('gender') }}</div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Password <small>(Password must be at least 8 characters)</small>:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-key"></i></span>
                                        </div>
                                        <input
                                          type="password"
                                          id="password"
                                          class="form-control @error('password') is-invalid @enderror"
                                          name="password"
                                          placeholder="**************"
                                          required
                                        >
                                        <div class="input-group-append togglePassword" data-target="#password" style="cursor:pointer">
                                            <div class="input-group-text"><i class="far fa-eye-slash"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-danger">{{ $errors->first('password') }}</div>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Confirm Password:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-key"></i></span>
                                        </div>
                                        <input
                                          type="password"
                                          id="password_confirmation"
                                          class="form-control @error('password_confirmation') is-invalid @enderror"
                                          name="password_confirmation"
                                          placeholder="**************"
                                          required
                                        >
                                        <div class="input-group-append togglePassword" data-target="#password_confirmation" style="cursor:pointer">
                                            <div class="input-group-text"><i class="far fa-eye-slash"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-danger">{{ $errors->first('password_confirmation') }}</div>
                                </div>
                            </div>

                            <!-- Courses Taught -->
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Courses Taught <small class="text-muted">(multiple)</small>:</label>
                                    <select name="courses[]" class="form-control select2 @error('courses') is-invalid @enderror" multiple required>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}"
                                                {{ in_array($course->id, old('courses', [])) ? 'selected' : '' }}>
                                                {{ $course->course_name }}
                                                ({{ $course->groups->pluck('name')->join(', ') ?: 'No Group' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Hold Ctrl (Cmd) to select multiple</small>
                                    <div class="invalid-feedback">{{ $errors->first('courses') }}</div>
                                </div>
                            </div>

                            <!-- Classes (Groups) -->
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Assign to Groups <small class="text-muted">(multiple)</small>:</label>
                                    <select name="groups[]" class="form-control select2 @error('groups') is-invalid @enderror" multiple required>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}"
                                                {{ in_array($group->id, old('groups', [])) ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Hold Ctrl (Cmd) to select multiple</small>
                                    <div class="invalid-feedback">{{ $errors->first('groups') }}</div>
                                </div>
                            </div>

                            <!-- Upload Image -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Upload Image:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-image"></i></span>
                                        </div>
                                        <input type="file"
                                               class="form-control @error('image') is-invalid @enderror"
                                               name="image"
                                               id="image">
                                    </div>
                                    <div class="text-danger">{{ $errors->first('image') }}</div>
                                </div>
                            </div>

                            <!-- Preview & Submit -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <img id="image-preview"
                                         src="https://via.placeholder.com/100"
                                         alt="Preview"
                                         style="width:100px;height:100px;padding:2px;box-shadow:0 0 5px #333;border:1px solid #000;border-radius:50%;">
                                </div>
                                <div class="form-group col-md-6 text-right">
                                    <button type="submit" class="btn btn-outline-success">
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


<script>
$(document).ready(function () {
    $('.select2').select2();

    // Image preview
    $('#image').change(function(){
        let reader = new FileReader();
        reader.onload = e => $('#image-preview').attr('src', e.target.result);
        reader.readAsDataURL(this.files[0]);
    });

    // Password toggle
    
});
</script>
@endpush
