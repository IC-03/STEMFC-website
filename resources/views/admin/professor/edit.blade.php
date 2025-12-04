@extends('layouts.app')

@section('main-container')

@push('styles')
    <!-- Select2 CSS for multi-select dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="far fa-user-edit"></i> Edit Professor
                        </h3>
                    </div>
                    <div class="card-body">
                        {{-- Display validation errors, if any --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.professor.update', $professor->uuid) }}" 
                              method="POST" 
                              id="upload-image" 
                              novalidate 
                              enctype="multipart/form-data"
                        >
                            @csrf
                            @method('PUT')

                            <!-- First Row: First Name & Last Name -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>First Name:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-user"></i>
                                            </span>
                                        </div>
                                        <input 
                                            type="text"
                                            name="first_name"
                                            required
                                            placeholder="First Name"
                                            class="form-control @error('first_name') is-invalid @enderror"
                                            value="{{ old('first_name', $professor->first_name) }}"
                                        >
                                    </div>
                                    <div class="text-danger">
                                        {{ $errors->first('first_name') }}
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Last Name:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-user"></i>
                                            </span>
                                        </div>
                                        <input 
                                            type="text"
                                            name="last_name"
                                            placeholder="Last Name"
                                            class="form-control @error('last_name') is-invalid @enderror"
                                            value="{{ old('last_name', $professor->last_name) }}"
                                        >
                                    </div>
                                    <div class="text-danger">
                                        {{ $errors->first('last_name') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Second Row: Telephone & ID Number -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Phone:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-phone-volume"></i>
                                            </span>
                                        </div>
                                        <input 
                                            type="tel"
                                            name="telephone"
                                            inputmode="text"
                                            class="form-control @error('telephone') is-invalid @enderror"
                                            value="{{ old('telephone', $professor->telephone) }}"
                                        >
                                    </div>
                                    <div class="text-danger">
                                        {{ $errors->first('telephone') }}
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>ID Number (National):</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-at"></i>
                                            </span>
                                        </div>
                                        <input 
                                            type="number"
                                            name="id_no"
                                            placeholder="ID Number"
                                            required
                                            class="form-control @error('id_no') is-invalid @enderror"
                                            value="{{ old('id_no', $professor->id_no) }}"
                                        >
                                    </div>
                                    <div class="text-danger">
                                        {{ $errors->first('id_no') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Third Row: Gender & Password -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Gender:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-venus-mars"></i>
                                            </span>
                                        </div>
                                        <select 
                                            name="gender"
                                            required
                                            class="form-control @error('gender') is-invalid @enderror"
                                        >
                                            <option value="" disabled>Select Gender</option>
                                            <option value="male"   {{ old('gender', $professor->gender)=='male'   ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $professor->gender)=='female' ? 'selected' : '' }}>Female</option>
                                            <option value="other"  {{ old('gender', $professor->gender)=='other'  ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="text-danger">
                                        {{ $errors->first('gender') }}
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Password: <small>(Leave blank to keep current)</small></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-key"></i>
                                            </span>
                                        </div>
                                        <input 
                                            type="password"
                                            id="password"
                                            name="password"
                                            placeholder="**************"
                                            class="form-control @error('password') is-invalid @enderror"
                                        >
                                        <div class="input-group-append togglePassword" data-target="#password" style="cursor:pointer">
                                            <div class="input-group-text">
                                                <i class="far fa-eye-slash"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-danger">
                                        {{ $errors->first('password') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Fourth Row: Confirm Password & Upload Image -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Confirm Password:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-key"></i>
                                            </span>
                                        </div>
                                        <input 
                                            type="password"
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            placeholder="**************"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                        >
                                        <div class="input-group-append togglePassword" data-target="#password_confirmation" style="cursor:pointer">
                                            <div class="input-group-text">
                                                <i class="far fa-eye-slash"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-danger">
                                        {{ $errors->first('password_confirmation') }}
                                    </div>
                                </div>

                                <div class="form-group col-md-6 text-center mt-4">
                                    <img 
                                        src="{{ $professor->picture ? asset('storage/'.$professor->picture) : 'https://via.placeholder.com/100' }}" 
                                        id="image-preview"
                                        style="width:100px; height:100px; padding:2px; box-shadow:0 0 5px #333; border:1px solid #000; border-radius:50%;"
                                        alt="Profile Picture"
                                    >
                                </div>
                            </div>

                            <!-- Fifth Row: Upload New Image -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Upload Image:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-image"></i>
                                            </span>
                                        </div>
                                        <input 
                                            type="file"
                                            id="image"
                                            name="image"
                                            class="form-control @error('image') is-invalid @enderror"
                                        >
                                    </div>
                                    <div class="text-danger">
                                        {{ $errors->first('image') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Courses Taught Multi-select -->
                            <div class="form-group">
                                <label>Courses Taught:</label>
                                <select 
                                    name="courses[]" 
                                    class="form-control select2 @error('courses') is-invalid @enderror" 
                                    multiple 
                                    required
                                >
                                    @foreach($allCourses as $course)
                                        <option value="{{ $course->id }}"
                                            {{ in_array($course->id, old('courses', $teaches)) ? 'selected' : '' }}
                                        >
                                            {{ $course->course_name }}
                                            ({{ $course->groups->pluck('name')->join(', ') ?: 'No Group' }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Hold Ctrl (Cmd) to select multiple</small>
                                <div class="text-danger">
                                    {{ $errors->first('courses') }}
                                </div>
                            </div>

                            <!-- Groups (Classes) Multi-select -->
                            <div class="form-group">
                                <label>Assign to Groups:</label>
                                <select 
                                    name="groups[]" 
                                    class="form-control select2 @error('groups') is-invalid @enderror" 
                                    multiple 
                                    required
                                >
                                    @foreach($allGroups as $group)
                                        <option value="{{ $group->id }}"
                                            {{ in_array($group->id, old('groups', $classes)) ? 'selected' : '' }}
                                        >
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Hold Ctrl (Cmd) to select multiple</small>
                                <div class="text-danger">
                                    {{ $errors->first('groups') }}
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-row">
                                <div class="form-group col-md-12 text-right">
                                    <button type="submit" class="btn btn-outline-success">
                                        <i class="fas fa-save"></i> Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.col-md-12 -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

@endsection

@push('scripts')
    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize all .select2 fields
            $('.select2').select2({
                placeholder: 'Choose one or more...',
                width: '100%'
            });

            // Image preview logic
            $('#image').change(function(){
                let reader = new FileReader();
                reader.onload = (e) => $('#image-preview').attr('src', e.target.result);
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endpush
