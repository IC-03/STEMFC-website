@extends('layouts.app')
@section('main-container')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            All Guardians
            <span class="badge badge-warning right">{{ $users->count() }}</span>

          </h3>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <a href="{{ route('admin.guardian.create') }}" class="btn btn-outline-warning">
              <i class="fas fa-user-plus"></i> New Guardian
            </a>
          </div>
          <table id="example1" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>ID Number(National)</th>
                <th>Telephone</th>
                <th>Gender</th>
                <th>Member Since</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Picture</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->full_name }}</td>
                <td>{{ $user->id_no }}</td>
                <td>{{ $user->telephone }}</td>
                <td>{{ ucfirst($user->gender ?? '') }}</td>
                <td>{{ $user->created_at->format('M d Y') }}</td>
                <td class="text-center">
                  <span class="badge badge-{{ $user->role->role_name === 'Parent' ? 'info' : 'secondary' }}">
                    {{ ucwords($user->role->role_name) }}
                  </span>
                </td>
                <td>{{ $user->notes }}</td>
                <td class="text-center p-0">
                  <img
                    src="{{ $user->picture ? asset('storage/'.$user->picture) : 'https://via.placeholder.com/50' }}"
                    alt="{{ $user->full_name }}"
                    style="width:50px; height:50px; border-radius:50%; object-fit:cover;"
                  >
                </td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <!-- View -->
                        <a
                        href="{{ route('admin.guardian.profile', ['uuid' => $user->uuid]) }}"
                        class="btn btn-outline-warning"
                        >
                        <i class="fas fa-eye"></i>
                        </a>

                        <!-- Edit -->
                        <a
                        href="{{ route('admin.guardian.edit', ['uuid' => $user->uuid]) }}"
                        class="btn btn-outline-info"
                        >
                        <i class="fas fa-edit"></i>
                        </a>

                        <!-- Delete -->
                        <button
                        type="button"
                        class="btn btn-outline-danger"
                        onclick="confirmDelete('{{ $user->uuid }}', '{{ $user->full_name }}')"
                        >
                        <i class="fas fa-trash"></i>
                        </button>
                        <form
                        id="delete-form-{{ $user->uuid }}"
                        action="{{ route('admin.guardian.delete', ['uuid' => $user->uuid]) }}"
                        method="POST"
                        style="display: none;"
                        >
                        @csrf
                        @method('DELETE')
                        </form>
                    </div>
                    </td>

              </tr>
            @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>ID Number(National)</th>
                <th>Telephone</th>
                <th>Gender</th>
                <th>Member Since</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Picture</th>
                <th>Actions</th>
              </tr>
            </tfoot>
          </table>
        </div><!-- /.card-body -->
      </div><!-- /.card -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function confirmDelete(uuid, name) {
    Swal.fire({
      title: `Delete ${name}?`,
      text: "This action cannot be undone.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel'
    }).then(result => {
      if (result.isConfirmed) {
        document.getElementById(`delete-form-${uuid}`).submit();
      }
    });
  }
</script>
@endsection
