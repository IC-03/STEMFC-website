@extends('layouts.app')

@section('main-container')

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            All Classes
            <span class="badge badge-info ml-2">{{ $groups->count() }}</span>
          </h3>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <a href="{{ route('admin.class.create') }}" class="btn btn-outline-primary">
              <i class="fas fa-plus-circle"></i> New Class
            </a>
          </div>
          <table id="example1" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Class Name</th>
                <th>Professors</th>
                <th>Ability</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Created At</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($groups as $group)
                <tr>
                  <td>{{ $group->id }}</td>
                  <td>{{ $group->name }}</td>
                  <td>
                    @forelse($group->teachers as $teacher)
                      <span class="badge badge-secondary">{{ $teacher->first_name }} {{ $teacher->last_name }}</span>
                    @empty
                      <span class="text-muted">N/A</span>
                    @endforelse
                  </td>
                  <td>{{ $group->ability }}</td>
                  <td>{{ optional($group->start_date)->format('Y-m-d') ?? 'N/A' }}</td>
                  <td>{{ optional($group->end_date)->format('Y-m-d') ?? 'N/A' }}</td>
                  <td>
                    @if($group->status == 1)
                      <span class="badge badge-success">Active</span>
                    @else
                      <span class="badge badge-secondary">Inactive</span>
                    @endif
                  </td>
                  <td>{{ optional($group->admin)->full_name ?? 'System' }}</td>
                  <td>{{ $group->created_at->format('M d, Y') }}</td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm">
                      <a href="{{ route('admin.class.edit', $group->uuid) }}" class="btn btn-outline-warning" title="Edit">
                        <i class="fas fa-edit"></i>
                      </a>
                      <button type="button" class="btn btn-outline-danger" onclick="confirmDelete('{{ $group->uuid }}', '{{ $group->name }}')" title="Delete">
                        <i class="fas fa-trash"></i>
                      </button>
                      <form id="delete-form-{{ $group->uuid }}" action="{{ route('admin.class.delete', $group->uuid) }}" method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="d-flex justify-content-center mt-3">
            {!! $groups->links('pagination::bootstrap-4') !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- SweetAlert2 -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  function confirmDelete(uuid, name) {
    Swal.fire({
      title: `Delete "${name}"?`,
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
