@extends('layouts.app')

@section('main-container')

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title"><i class="far fa-user-plus"></i> Create New Guardian</h3>
          </div>
          <div class="card-body">
            {{-- Display Validation Errors --}}
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

        {{-- Display Session Messages --}}
        @if (session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.guardian.add') }}" method="POST" enctype="multipart/form-data" novalidate id="upload-image">
          @csrf

          <!-- Name Row -->
          <div class="form-row">
            <!-- First Name -->
            <div class="form-group col-md-6">
              <label for="firstname">First Name</label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-user"></i></span></div>
                <input type="text" id="firstname" name="first_name" class="form-control @error('firstname') is-invalid @enderror" value="{{ old('firstname') }}" placeholder="First Name" required>
              </div>
              @error('firstname')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Last Name -->
            <div class="form-group col-md-6">
              <label for="lastname">Last Name</label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-user"></i></span></div>
                <input type="text" id="lastname" name="last_name" class="form-control @error('lastname') is-invalid @enderror" value="{{ old('lastname') }}" placeholder="Last Name" required>
              </div>
              @error('lastname')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <!-- Contact & ID Row -->
          <div class="form-row">
            <!-- Phone -->
            <div class="form-group col-md-6">
              <label for="telephone">Phone</label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                <input type="tel" id="telephone" name="telephone" class="form-control @error('telephone') is-invalid @enderror" value="{{ old('telephone') }}" placeholder="Phone Number" required>
              </div>
              @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- ID Number -->
            <div class="form-group col-md-6">
              <label for="id_no">ID Number(National)</label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-at"></i></span></div>
                <input type="number" id="id_no" name="id_no" class="form-control @error('id_no') is-invalid @enderror" value="{{ old('id_no') }}" placeholder="ID Number(National)" required>
              </div>
              @error('id_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <!-- Gender Row -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="gender">Gender</label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-venus-mars"></i></span></div>
                <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                  <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                  <option value="male" {{ old('gender')=='male' ? 'selected' : '' }}>Male</option>
                  <option value="female" {{ old('gender')=='female' ? 'selected' : '' }}>Female</option>
                  <option value="other" {{ old('gender')=='other' ? 'selected' : '' }}>Other</option>
                </select>
              </div>
              @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <!-- Image & Notes Row -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="image">Upload Image</label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-image"></i></span></div>
                <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror">
              </div>
              @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group col-md-6 d-flex align-items-center justify-content-center">
              <img id="image-preview" src="https://cdn.dribbble.com/users/4438388/screenshots/15854247/media/0cd6be830e32f80192d496e50cfa9dbc.jpg" alt="Preview" style="width:100px;height:100px;padding:2px;box-shadow:0 0 5px #333;border:1px solid #000;border-radius:50%;">
            </div>
          </div>

          <!-- Notes & Submit Row -->
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="notes">Notes</label>
              <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" placeholder="Write your notes here">{{ old('notes') }}</textarea>
              @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12 text-right">
              <button type="submit" class="btn btn-outline-primary"><i class="fas fa-save"></i> Save</button>
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

@push('scripts')

<script>
  document.addEventListener('DOMContentLoaded', function() {

    document.getElementById('image').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = ev => document.getElementById('image-preview').src = ev.target.result;
      reader.readAsDataURL(file);
    });
  });
</script>

@endpush
