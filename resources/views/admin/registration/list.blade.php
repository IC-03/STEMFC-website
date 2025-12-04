@extends('layouts.app')
@section('main-container')

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            All Registrations
            <span class="badge badge-warning right">{{ $regs->count() }}</span>
          </h3>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <a href="{{ route('admin.registration.create') }}" class="btn btn-outline-warning">
              <i class="fas fa-plus-square"></i> New Registration
            </a>
          </div>
          <table id="example1" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Registration Date</th>
                <th>Student</th>
                <th>Class</th>
                <th>Course</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($regs as $reg)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($reg->reg_date)->format('M d Y') }}</td>
                <td>{{ $reg->user->full_name }}</td>
                <td>{{ $reg->group->name ?? 'N/A' }}</td>
                <td>{{ $reg->course->course_name }}</td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.registration.edit', $reg->uuid) }}" class="btn btn-outline-primary" title="Edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    <button
                      type="button"
                      class="btn btn-outline-danger"
                      onclick="confirmDelete('{{ $reg->uuid }}', '{{ $reg->user->full_name }} - {{ $reg->group->name ?? '' }} - {{ $reg->course->course_name }}')"
                      title="Delete"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                    <form
                      id="delete-form-{{ $reg->uuid }}"
                      action="{{ route('admin.registration.delete', $reg->uuid) }}"
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
                <th>Registration Date</th>
                <th>Student</th>
                <th>Class</th>
                <th>Course</th>
                <th>Actions</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- SweetAlert --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(uuid, name) {
  Swal.fire({
    title: `Delete registration for ${name}?`,
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
