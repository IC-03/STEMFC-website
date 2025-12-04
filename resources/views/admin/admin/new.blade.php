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
                      <input type="text" class="form-control" name="firstname" required placeholder="First Name"
                        value="{{ old('firstname') }}">
                    </div>
                    <div class="text-danger">{{ $errors->first('firstname') }}</div>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Last Name:</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-user"></i></span>
                      </div>
                      <input type="text" class="form-control" name="lastname" placeholder="Last Name"
                        value="{{ old('lastname') }}">
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
                      <input type="number" class="form-control" name="id_no" placeholder="ID Number(National)"
                        value="{{ old('id_no') }}" required>
                    </div>
                    <div class="text-danger">{{ $errors->first('id_no') }}</div>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Telephone:</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-mobile"></i></span>
                      </div>
                      <input type="number" class="form-control" name="telephone" placeholder="Telephone number"
                        value="{{ old('telephone') }}" required>
                    </div>
                    <div class="text-danger">{{ $errors->first('telephone') }}</div>
                  </div>
                  
                </div>

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>Password <small>(Password must be at least 8 characters)</small>:</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-key"></i></span>
                      </div>
                      <input type="password" class="form-control" name="password" required placeholder="**********">
                      <div class="input-group-append togglePassword" data-target="#password" style="cursor:pointer;">
                        <div class="input-group-text"><i class="far fa-eye-slash"></i></div>
                      </div>
                    </div>
                    <div class="text-danger">{{ $errors->first('password') }}</div>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Confirm Password:</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-key"></i></span>
                      </div>
                      <input type="password" class="form-control" name="password_confirmation" required
                        placeholder="**********">
                      <div class="input-group-append togglePassword" data-target="#password" style="cursor:pointer;">
                        <div class="input-group-text"><i class="far fa-eye-slash"></i></div>
                      </div>
                    </div>
                    <div class="text-danger">{{ $errors->first('password_confirmation') }}</div>
                  </div>
                </div>
                <div class="form-group col-md-6 text-left">
                  <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-save"></i> Save
                  </button>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection