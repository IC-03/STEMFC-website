@extends('layouts.app')

@section('main-container')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        All Courses
                        <span class="badge badge-danger right">{{ $courses->count() }}</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('admin.course.create') }}" class="btn btn-outline-danger">
                            <i class="fas fa-book-medical"></i> New Course
                        </a>
                    </div>
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Course Name</th>
                                <th>Created Date</th>
                                <th>Group</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $course->course_name }}</td>
                                <td>{{ $course->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($course->groups->isNotEmpty())
                                        @foreach($course->groups as $group)
                                            <span class="badge badge-info">{{ $group->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No Group Assigned</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.course.edit', $course->uuid) }}" 
                                           class="btn btn-outline-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Delete Button -->
                                        <button
                                            type="button"
                                            class="btn btn-outline-danger"
                                            onclick="confirmDelete('{{ $course->uuid }}', '{{ $course->course_name }}')"
                                            title="Delete"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        <!-- Hidden Delete Form -->
                                        <form
                                            id="delete-form-{{ $course->uuid }}"
                                            action="{{ route('admin.course.delete', $course->uuid) }}"
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
                                <th>Course Name</th>
                                <th>Created Date</th>
                                <th>Group</th>
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
