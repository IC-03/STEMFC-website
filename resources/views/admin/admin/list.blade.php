@extends('layouts.app')

@section('main-container')
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
            <h3 class="card-title">
                All Admins  
                <span class="badge badge-primary">{{ $admins->count() }}</span>
            </h3>
       </div>
        <div class="mt-3 ml-4">
                <a href="{{ route('admin.admin.create') }}" class="btn btn-outline-primary">
                    <i class="fas fa-user-plus"></i> New Admin
                </a>
        </div>

      <div class="card-body">
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>ID Number(National)</th>
              <th>Member Since</th>
              <th>Status</th>
              <th class="text-center">Picture</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($admins as $admin)
              @continue($admin->uuid === auth()->user()->uuid)
              <tr class="{{ $loop->even ? 'even' : 'odd' }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $admin->first_name }} {{ $admin->last_name }}</td>
                <td>{{ $admin->id_no }}</td>
                <td>{{ $admin->created_at->format('M d, Y') }}</td>
                <td class="text-center">
                  <span class="badge badge-{{ $admin->status ? 'success' : 'secondary' }}">
                    {{ $admin->status ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="text-center">
                  <img src="{{ $admin->picture
                              ? asset('storage/'.$admin->picture)
                              : 'https://via.placeholder.com/50' }}"
                       alt="{{ $admin->first_name }}"
                       style="width:50px; height:50px; border-radius:50%; border:1px solid #000;">
                </td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm">
                    <button type="button"
                            class="btn btn-outline-danger"
                            onclick="confirmDelete('{{ $admin->uuid }}', '{{ $admin->first_name }}')"
                            title="Delete">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                  <form id="delete-form-{{ $admin->uuid }}"
                        action="{{ route('admin.admin.delete', $admin->uuid) }}"
                        method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>ID Number(National)</th>
              <th>Member Since</th>
              <th>Status</th>
              <th class="text-center">Picture</th>
              <th class="text-center">Actions</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection

@stack('scripts')
@push('scripts')
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
@endpush
