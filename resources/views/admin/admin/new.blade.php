@extends('layouts.app')
@section('main-container')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title"><i class="far fa-user-plus"></i> Create New Admin</h3>
          </div>
          <div class="card-body">
            {{-- POINT THIS AT admin.admin.add (your store method) --}}
            <form action="{{ route('admin.admin.add') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>First Name:</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-user"></i></span>
                    </div>
                    <input
                      type="text"
                      class="form-control"
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
                      class="form-control"
                      name="lastname"
                      placeholder="Last Name"
                      value="{{ old('lastname') }}"
                    >
                  </div>
                  <div class="text-danger">{{ $errors->first('lastname') }}</div>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>ID Number:</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-at"></i></span>
                    </div>
                    <input
                      type="number"
                      class="form-control"
                      name="id_no"
                      placeholder="ID Number(National)"
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
                      class="form-control"
                      name="password"
                      required
                      placeholder="**********"
                    >
                    <div class="input-group-append togglePassword" data-target="#password" style="cursor:pointer;">
                        <div class="input-group-text"><i class="far fa-eye-slash"></i></div>
                    </div>
                  </div>
                  <div class="text-danger">{{ $errors->first('password') }}</div>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Confirm Password:</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-key"></i></span>
                    </div>
                    <input
                      type="password"
                      class="form-control"
                      name="password_confirmation"
                      required
                      placeholder="**********"
                    >
                    <div class="input-group-append togglePassword" data-target="#password" style="cursor:pointer;">
                        <div class="input-group-text"><i class="far fa-eye-slash"></i></div>
                    </div>
                  </div>
                  <div class="text-danger">{{ $errors->first('password_confirmation') }}</div>
                </div>
                <div class="form-group col-md-6">
                  <label>Upload Image:</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-image"></i></span>
                    </div>
                    <input
                      type="file"
                      class="form-control"
                      name="image"
                      id="image"
                    >
                  </div>
                  <div class="text-danger">{{ $errors->first('image') }}</div>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <img
                    id="image-preview"
                    src="https://cdn.dribbble.com/users/4438388/screenshots/15854247/media/0cd6be830e32f80192d496e50cfa9dbc.jpg"
                    style="width:100px; height:100px; border-radius:50%; box-shadow:0 0 5px #333;"
                    alt="Preview"
                  >
                </div>
                <div class="form-group col-md-6 text-right">
                  <button type="submit" class="btn btn-outline-primary">
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

{{-- Image preview script --}}
@push('scripts')
<script>
  document.getElementById('image').addEventListener('change', function(e) {
    const [file] = e.target.files;
    if (file) {
      document.getElementById('image-preview').src = URL.createObjectURL(file);
    }
  });
</script>
@endpush
@endsection
