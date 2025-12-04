@extends('layouts.app')

@section('main-container')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            All Professors
            <span class="badge badge-success right">{{ $users->count() }}</span>
          </h3>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <a href="{{ route('admin.professor.new') }}" class="btn btn-outline-success">
              <i class="fas fa-user-plus"></i> New Professor
            </a>
          </div>

          <table id="example1" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>ID Number (National)</th>
                <th>Telephone</th>
                <th>Gender</th>
                <th>Member Since</th>
                <th>Status</th>
                <th>Courses &amp; Classes</th>
                <th>Picture</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                  <td>{{ $user->id_no }}</td>
                  <td>{{ $user->telephone }}</td>
                  <td>{{ ucfirst($user->gender ?? '') }}</td>
                  <td>{{ $user->created_at->format('M d, Y') }}</td>
                  <td class="text-center">
                    @php
                      $roleName = $user->role->role_name ?? 'No Role';
                    @endphp
                    <span class="badge badge-{{ $user->isAdmin() ? 'danger' : 'secondary' }}">
                      {{ ucwords($roleName) }}
                    </span>
                  </td>
                  <td>
                    @php
                      $assignedCourses = $user->assignedCourses;
                    @endphp

                    @if($assignedCourses->isNotEmpty())
                      @foreach($assignedCourses as $course)
                        <div class="mb-2">
                          <strong>{{ $course->course_name }}</strong>
                          @php
                            $teachingGroups = $user->teachingGroups
                              ->filter(fn($group) => $group->courses->contains('id', $course->id));
                          @endphp

                          @if($teachingGroups->isNotEmpty())
                            <div class="ml-2">
                              @foreach($teachingGroups as $grp)
                                <span class="badge badge-primary">{{ $grp->name }}</span>
                              @endforeach
                            </div>
                          @else
                            <div class="ml-2 text-muted">No classes</div>
                          @endif
                        </div>
                      @endforeach
                    @else
                      <span class="text-muted">None</span>
                    @endif
                  </td>
                  <td class="text-center p-0">
                    <img
                      src="{{ $user->picture ? asset('storage/' . $user->picture) : 'https://via.placeholder.com/50' }}"
                      alt="{{ $user->first_name }} {{ $user->last_name }}"
                      style="width:50px; height:50px; border-radius:50%; object-fit:cover;">
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm">
                      <a href="{{ route('admin.professor.edit', $user->uuid) }}" class="btn btn-outline-warning" title="Edit Profile">
                        <i class="fas fa-edit"></i>
                      </a>

                      <button type="button" class="btn btn-outline-danger" onclick="confirmDelete('{{ $user->uuid }}')" title="Delete Professor">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                    <form id="delete-form-{{ $user->uuid }}" action="{{ route('admin.professor.delete', $user->uuid) }}" method="POST" style="display:none;">
                      @csrf
                      @method('DELETE')
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="10" class="text-center text-muted">No professors found.</td>
                </tr>
              @endforelse
            </tbody>
            <tfoot>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>ID Number (National)</th>
                <th>Telephone</th>
                <th>Gender</th>
                <th>Member Since</th>
                <th>Status</th>
                <th>Courses &amp; Classes</th>
                <th>Picture</th>
                <th>Actions</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function confirmDelete(uuid) {
    Swal.fire({
      title: 'Are you sure?',
      text: "This will permanently delete the professor and clear assignments.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById(`delete-form-${uuid}`).submit();
      }
    });
  }
</script>
@endsection
