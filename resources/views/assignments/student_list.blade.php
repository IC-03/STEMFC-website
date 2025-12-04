@extends('layouts.app')

@section('main-container')
<div class="container mt-5 col-md-12">


	
	<div class="card">
              <div class="card-header">
                <h3 class="card-title">Assigned Assignments</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">	

    @if($assignments->isEmpty())
        <div class="alert alert-info">No assignments available for this course.</div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Assignment Name</th>
                    <th>Uploaded By (Teacher)</th>
                    <th>Uploaded On</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $index => $assignment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $assignment->name }}</td>
                    <td>{{ $assignment->teacher->full_name ?? 'Unknown' }}</td>
                    <td>{{ $assignment->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ asset('public/assignments/'. $assignment->file_path) }}" 
                           class="btn btn-sm btn-outline-info" target="_blank">
                            <i class="fa fa-download"></i> Download
                        </a>
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
