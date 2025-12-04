@extends('layouts.app')
@section('main-container')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="far fa-user-plus"></i> Create New Student</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.student.add') }}" method="POST" id="upload-image" novalidate enctype="multipart/form-data">
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
                                          class="form-control @error('firstname') is-invalid @enderror" 
                                          name="firstname" 
                                          required 
                                          placeholder="First Name" 
                                          value="{{ old('firstname') }}"
                                        >
                                    </div>
                                    <div class="text-danger">{{ $errors->first('firstname') }}</div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Last Name:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-user"></i></span>
                                        </div>
                                        <input 
                                          type="text" 
                                          class="form-control @error('lastname') is-invalid @enderror" 
                                          name="lastname" 
                                          placeholder="Last Name" 
                                          value="{{ old('lastname') }}"
                                        >
                                    </div>
                                    <div class="text-danger">{{ $errors->first('lastname') }}</div>
                                </div>
                            </div>

                            <!-- Age & Phone Row -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Age:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-hourglass-end"></i></span>
                                        </div>
                                        <input 
                                          type="number" 
                                          min="0" 
                                          class="form-control @error('age') is-invalid @enderror" 
                                          name="age" 
                                          value="{{ old('age') }}" 
                                          placeholder="Age"
                                        >
                                    </div>
                                    <div class="text-danger">{{ $errors->first('age') }}</div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Phone:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone-volume"></i></span>
                                        </div>
                                        <input 
                                          type="tel" 
                                          class="form-control @error('phone') is-invalid @enderror" 
                                          name="phone" 
                                          value="{{ old('phone') }}" 
                                          data-inputmask='"mask": "+34 999 999 999"' 
                                          data-mask 
                                          inputmode="text"
                                        >
                                    </div>
                                    <div class="text-danger">{{ $errors->first('phone') }}</div>
                                </div>
                            </div>

                            <!-- Gender Row -->
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
                                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                                            <option value="male"   {{ old('gender')=='male'   ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender')=='female' ? 'selected' : '' }}>Female</option>
                                            <option value="other"  {{ old('gender')=='other'  ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="text-danger">{{ $errors->first('gender') }}</div>
                                </div>
                            </div>

                            <!-- Address & City Row -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Address:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marked"></i></span>
                                        </div>
                                        <input 
                                          type="text" 
                                          class="form-control @error('address') is-invalid @enderror" 
                                          name="address" 
                                          placeholder="Address" 
                                          value="{{ old('address') }}" 
                                          required
                                        >
                                    </div>
                                    <div class="text-danger">{{ $errors->first('address') }}</div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>City:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-city"></i></span>
                                        </div>
                                        <input 
                                          type="text" 
                                          class="form-control @error('city') is-invalid @enderror" 
                                          name="city" 
                                          value="{{ old('city') }}" 
                                          required 
                                          placeholder="City"
                                        >
                                    </div>
                                    <div class="text-danger">{{ $errors->first('city') }}</div>
                                </div>
                            </div>

                            <!-- ID & Password Row -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Student ID:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-at"></i></span>
                                        </div>
                                        <input 
                                          type="number" 
                                          class="form-control @error('id_no') is-invalid @enderror" 
                                          name="id_no" 
                                          placeholder="Student ID" 
                                          value="{{ old('id_no') }}" 
                                          required
                                        >
                                    </div>
                                    <div class="text-danger">{{ $errors->first('id_no') }}</div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Password <small>(Password must be at least 8 characters)</small>:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-key"></i></span>
                                        </div>
                                        <input 
                                          type="password" 
                                          class="form-control @error('password') is-invalid @enderror" 
                                          name="password" 
                                          placeholder="**************" 
                                          required
                                        >
                                       <div class="input-group-append togglePassword" data-target="#password" style="cursor:pointer;">
                                            <div class="input-group-text"><i class="far fa-eye-slash"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-danger">{{ $errors->first('password') }}</div>
                                </div>
                            </div>

                            <!-- Confirm, Stratum & Guardian Row -->
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Confirm Password:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input 
                                          type="password" 
                                          class="form-control @error('password_confirmation') is-invalid @enderror" 
                                          name="password_confirmation" 
                                          placeholder="**************" 
                                          required
                                        >
                                        <div class="input-group-append togglePassword" data-target="#password" style="cursor:pointer;">
                                            <div class="input-group-text"><i class="far fa-eye-slash"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-danger">{{ $errors->first('password_confirmation') }}</div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Stratum:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                        </div>
                                        <input 
                                          type="text" 
                                          class="form-control @error('stratum') is-invalid @enderror" 
                                          name="stratum" 
                                          value="{{ old('stratum') }}" 
                                          required 
                                          placeholder="Stratum"
                                        >
                                    </div>
                                    <div class="text-danger">{{ $errors->first('stratum') }}</div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Guardian Name: <span style='font-weight:normal; font-size: small;'>(Leave blank if no guardian)</span></label>
                                    <div class="input-group">
                                        <select 
                                          class="form-control select2 @error('guardname') is-invalid @enderror" 
                                          name="guardname" 
                                          style="width: 100%;"
                                        >
                                            <option value="" selected>Select Guardian</option>
                                            @foreach ($parents as $user)
                                                <option 
                                                  value="{{ $user->id }}" 
                                                  {{ old('guardname') == $user->id ? 'selected' : '' }}
                                                >
                                                  {{ $user->first_name }} {{ $user->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="text-danger">{{ $errors->first('guardname') }}</div>
                                </div>
                            </div>

                            <!-- Image & Comments Row -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Upload Image:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-image"></i></span>
                                        </div>
                                        <input 
                                          type="file" 
                                          class="form-control @error('image') is-invalid @enderror" 
                                          name="image" 
                                          id="image"
                                        >
                                    </div>
                                    <div class="text-danger">{{ $errors->first('image') }}</div>
                                </div>
                                <div class="form-group col-md-6 mt-4 text-center">
                                    <img 
                                      id="image-preview" 
                                      src="https://via.placeholder.com/100" 
                                      alt="Preview" 
                                      style="width:100px; height:100px; padding:2px; box-shadow:0 0 5px #333; border:1px solid #000; border-radius:50%;"
                                    >
                                </div>
                            </div>

                            <!-- Comments & Submit -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Comments:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-comment"></i></span>
                                        </div>
                                        <textarea 
                                          class="form-control @error('comments') is-invalid @enderror" 
                                          name="comments" 
                                          rows="4" 
                                          placeholder="Enter your comments here" 
                                          required
                                        >{{ old('comments') }}</textarea>
                                    </div>
                                    <div class="text-danger">{{ $errors->first('comments') }}</div>
                                </div>
                                <div class="form-group col-md-6 d-flex align-items-end justify-content-end">
                                    <button 
                                      type="submit" 
                                      class="btn btn-outline-secondary"
                                    >
                                      <i class="fas fa-save"></i> Save
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div><!-- /.card -->
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script>
  $(function(){
    // Image preview
    $('#image').change(function(){
      let reader = new FileReader();
      reader.onload = e => $('#image-preview').attr('src', e.target.result);
      reader.readAsDataURL(this.files[0]);
    });

  
  });
</script>
@endpush
@endsection
