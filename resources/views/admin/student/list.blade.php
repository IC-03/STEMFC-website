@extends('layouts.app')
@section('main-container')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            All Students
            <span class="badge badge-warning right">{{ $users->count() }}</span>
          </h3>
        </div>
        <div class="card-body">
          <div class="mb-3">
            @if(Auth::user()->role_id === 1)
              <a href="{{ route('admin.student.create') }}" class="btn btn-outline-primary">
                <i class="fas fa-user-graduate"></i> New Student
              </a>
             @endif
          </div>
          <table id="example1" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Student ID</th>
                <th>Telephone</th>
                <th>Gender</th>
                <th>Member Since</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Course</th>
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
                  <span class="badge badge-{{ $user->role_name === 'Student' ? 'info' : 'secondary' }}">
                    {{ ucwords($user->role_name) }}
                  </span>
                </td>
                <td>{{ $user->notes }}</td>
                <td class="text-center p-0">
                   {{ $user->course_name }}
                </td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm">
                    {{-- Always allow view --}}
                    <a
                      href="{{ route('admin.student.profile', ['uuid' => $user->uuid]) }}"
                      class="btn btn-outline-info"
                    >
                      <i class="fas fa-eye"></i>
                    </a>

                    {{-- Edit: only for admins or the student themself --}}
                    @if(Auth::user()->role_id === 1 || Auth::user()->uuid === $user->uuid)
                      <a
                        href="{{ route('admin.student.edit', ['uuid' => $user->uuid]) }}"
                        class="btn btn-outline-warning"
                      >
                        <i class="fas fa-edit"></i>
                      </a>
                    @endif

                    {{-- Delete: only for admins --}}
                    @if(Auth::user()->role_id === 1)
                      <button
                        type="button"
                        class="btn btn-outline-danger"
                        onclick="confirmDelete('{{ $user->uuid }}', '{{ $user->full_name }}')"
                      >
                        <i class="fas fa-trash"></i>
                      </button>
                      <form
                        id="delete-form-{{ $user->uuid }}"
                        action="{{ route('admin.student.delete', ['uuid' => $user->uuid]) }}"
                        method="POST"
                        style="display: none;"
                      >
                        @csrf
                        @method('DELETE')
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Student ID</th>
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
