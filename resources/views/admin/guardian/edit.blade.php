@extends('layouts.app')
@section('main-container')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title"><i class="far fa-user-edit"></i> Edit Guardian</h3>
          </div>
          <div class="card-body">
          <form action="{{ route('admin.guardian.put', $guardian->uuid) }}" method="POST" enctype="multipart/form-data" novalidate id="upload-image">
              @csrf
              @method('PUT')

              <!-- Name Row -->
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="firstname">First Name</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-user"></i></span></div>
                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $guardian->first_name) }}" required>
                  </div>
                  @error('firstname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group col-md-6">
                  <label for="lastname">Last Name</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-user"></i></span></div>
                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $guardian->last_name) }}" required>
                  </div>
                  @error('lastname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <!-- Contact Row -->
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="telephone">Phone</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                    <input type="tel" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone', $guardian->telephone) }}" inputmode="text" required>
                  </div>
                  @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group col-md-6">
                  <label for="id_no">ID Number(National)</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-at"></i></span></div>
                    <input type="number" placeholder='ID Number(National)' class="form-control @error('id_no') is-invalid @enderror" id="id_no" name="id_no" value="{{ old('id_no', $guardian->id_no) }}" required>
                  </div>
                  @error('id_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <!-- Gender -->
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="gender">Gender</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-venus-mars"></i></span></div>
                    <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                      <option value="" disabled>Select Gender</option>
                      <option value="male"   {{ old('gender', $guardian->gender) == 'male' ? 'selected' : '' }}>Male</option>
                      <option value="female" {{ old('gender', $guardian->gender) == 'female' ? 'selected' : '' }}>Female</option>
                      <option value="other"  {{ old('gender', $guardian->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                  </div>
                  @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <!-- Image & Notes -->
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="image">Upload New Image</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-image"></i></span></div>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                  </div>
                  @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group col-md-6 d-flex align-items-center justify-content-center">
                  <img
                    src="{{ $guardian->image ? asset('storage/' . $guardian->image) : 'https://cdn.dribbble.com/users/4438388/screenshots/15854247/media/0cd6be830e32f80192d496e50cfa9dbc.jpg' }}"
                    id="image-preview"
                    alt="Preview"
                    style="width:100px; height:100px; padding:2px; box-shadow:0 0 5px #333; border:1px solid #000; border-radius:50%;"
                  >
                </div>
              </div>

              <!-- Notes -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="notes">Notes</label>
                  <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4">{{ old('notes', $guardian->notes) }}</textarea>
                  @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <!-- Submit -->
              <div class="form-row">
                <div class="form-group col-md-12 text-right">
                  <button type="submit" class="btn btn-outline-primary"><i class="fas fa-save"></i> Update</button>
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
  $(function() {
    $('[data-mask]').inputmask();

    $('.togglePassword').on('click', function() {
      const $input = $($(this).data('target'));
      if (!$input.length) return;
      const type = $input.attr('type') === 'password' ? 'text' : 'password';
      $input.attr('type', type);
      $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    $('#image').on('change', function(e) {
      const [file] = e.target.files;
      if (!file) return;
      const reader = new FileReader();
      reader.onload = ev => $('#image-preview').attr('src', ev.target.result);
      reader.readAsDataURL(file);
    });
  });
</script>
@endpush
