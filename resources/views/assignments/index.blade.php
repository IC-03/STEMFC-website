@extends('layouts.app')

@section('main-container')
<div class="container  col-md-12">
	
<div class="card">
              <div class="card-header">
                <h3 class="card-title"> <a href="{{ route('assignments.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add Assignment
        </a></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">	
				  	
 <!--   @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif-->

    @if($assignments->isEmpty())
        <div class="alert alert-info">No assignments found.</div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $index => $assignment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $assignment->name }}</td>
                    <td>{{ $assignment->course->course_name ?? 'N/A' }}</td>
                    <td>
						
						
                        <a href="{{ asset('public/assignments/'. $assignment->file_path) }}" class="btn btn-sm btn-outline-info" target="_blank">
                            <i class="fa fa-file"></i> View
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('assignments.edit', $assignment) }}" class="btn btn-sm btn-warning">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this assignment?')">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
	
	</div>
	</div>

</div>
@endsection
