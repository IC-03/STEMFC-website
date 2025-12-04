@extends('layouts.app')

@section('main-container')
<section class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">Edit Admin</h3>
            </div>

            <form action="{{ route('admin.admin.update', $admin->uuid) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    {{-- First / Last Name --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>First Name:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                                <input
                                    type="text"
                                    name="firstname"
                                    class="form-control @error('firstname') is-invalid @enderror"
                                    placeholder="First Name"
                                    value="{{ old('firstname', $admin->first_name) }}"
                                    required
                                >
                                @error('firstname')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Last Name:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                                <input
                                    type="text"
                                    name="lastname"
                                    class="form-control @error('lastname') is-invalid @enderror"
                                    placeholder="Last Name"
                                    value="{{ old('lastname', $admin->last_name) }}"
                                    required
                                >
                                @error('lastname')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ID Number / Telephone --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>ID Number:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                    </span>
                                </div>
                                <input
                                    type="text"
                                    name="id_no"
                                    class="form-control @error('id_no') is-invalid @enderror"
                                    placeholder="ID Number (National)"
                                    value="{{ old('id_no', $admin->id_no) }}"
                                    required
                                >
                                @error('id_no')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Telephone:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                </div>
                                <input
                                    type="text"
                                    name="telephone"
                                    class="form-control @error('telephone') is-invalid @enderror"
                                    placeholder="Telephone number"
                                    value="{{ old('telephone', $admin->telephone) }}"
                                >
                                @error('telephone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Password (optional) --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>
                                New Password:
                                <small class="text-muted">(leave blank to keep current password)</small>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                </div>
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="New password"
                                >
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Confirm Password:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                </div>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    placeholder="Confirm new password"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>
@endsection
