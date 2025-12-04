@extends('layouts.app')

@php
  $title = "Grade Your Students";
@endphp

@section('main-container')

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        {{-- Card Header --}}
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title">
            Bulk Grading — {{ $course->course_name }}
            <span class="badge badge-warning right">{{ $students->count() }}</span>
          </h3>
          {{-- Back or other action if needed --}}
          <a href="{{ route('grades.view') }}" style='margin-left: 55%;' class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> View All Grades
          </a>
        </div>

        {{-- Card Body --}}
                <div class="card-body">
		  <div class="row">
			  
			          <div class="col-sm-4">
					 <div class="form-group">
						 <label>Search by name or ID</label>
						   <input
              type="text"
              id="studentFilter"
              placeholder="Filter by name or ID…"
              class="form-control form-control-sm"
            >
						  </div>
			  </div>
                    <div class="col-sm-4">
					 <div class="form-group">
						 <label>Type:</label>
					<select
                        name="test_type_fill" id="test_type_fill"
                        class="form-control form-control-sm"
                      >
                        <option value="">— Select Type —</option>
                        <option value="test">
                          Test
                        </option>
                        <option value="classwork" >
                          Classwork
                        </option>
                        <option value="homework" >
                          Homework
                        </option>
                      </select> 
						</div></div> 
					         <div class="col-sm-4">
					 <div class="form-group"> 
					 <label>Date</label>
                      <input type="date" name="change_grades_date" id="change_grades_date" value="" class="form-control form-control-sm" >
								 </div></div>
			</div>
					</div>
          {{-- Filter Script --}}
          <script>
	$(document).on('change', '#change_grades_date', function () {
    let newDate = $(this).val();
    $('.set_change_grades_date_cls').val(newDate);
});	
			  
$(document).on('change', '#test_type_fill', function () {
    let test_type_fill = $(this).val();
    $('.set_test_type_cls').val(test_type_fill);
});
			  
            document.getElementById('studentFilter').addEventListener('keyup', function() {
              const filter = this.value.toLowerCase();
              document.querySelectorAll('tbody tr').forEach(row => {
                const nameCell = row.querySelector('td.name-cell').innerText.toLowerCase();
                const idCell   = row.querySelector('td.id-cell').innerText.toLowerCase();
                row.style.display = (nameCell.includes(filter) || idCell.includes(filter))
                  ? ''
                  : 'none';
              });
            });
          </script>

          {{-- Bulk Grading Form --}}
          <form action="{{ route('grades.bulk.store', $course->id) }}" method="POST">
            @csrf
            <table class="table table-bordered table-hover">
              <thead class="thead-light">
                <tr>
                  <th>ID No.</th>
                  <th>Student</th>
                  <th>Type</th>
                  <th>Add Grade</th>
                  <th>Comments</th>
                  <th>Date</th>
                  <th>Grade</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($students as $student)
                  @php
                    $existing = $existingGrades->get($student->id);
                  @endphp
                  <tr>
                    {{-- ID No. --}}
                    <td class="id-cell">{{ $student->id_no }}</td>

                    {{-- Student Name (hidden student_id input) --}}
                    <td class="name-cell">
                      {{ $student->full_name }}
                      <input
                        type="hidden"
                        name="grades[{{ $loop->index }}][student_id]"
                        value="{{ $student->id }}"
                      >
                    </td>

                    {{-- Assessment Type --}}
                    <td>
                      <select
                        name="grades[{{ $loop->index }}][assessment_type]"
                        class="form-control form-control-sm set_test_type_cls"
                      >
                        <option value="">— Select —</option>
                        <option
                          value="test"
                          {{ old("grades.{$loop->index}.assessment_type", $existing->assessment_type ?? '') == 'test' ? 'selected' : '' }}
                        >
                          Test
                        </option>
                        <option
                          value="classwork"
                          {{ old("grades.{$loop->index}.assessment_type", $existing->assessment_type ?? '') == 'classwork' ? 'selected' : '' }}
                        >
                          Classwork
                        </option>
                        <option
                          value="homework"
                          {{ old("grades.{$loop->index}.assessment_type", $existing->assessment_type ?? '') == 'homework' ? 'selected' : '' }}
                        >
                          Homework
                        </option>
                      </select>
                      @error("grades.{$loop->index}.assessment_type")
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                    </td>

                    {{-- Percentage Input --}}
                    <td>
                      <input
                        type="number"
							 step="any"
                        class="form-control form-control-sm w-50"
                        name="grades[{{ $loop->index }}][percentage]"
                        value="{{ old("grades.{$loop->index}.percentage", $existing->grade ?? '') }}"
                      >
                      @error("grades.{$loop->index}.percentage")
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                    </td>
                    <td> 
					  <textarea class="form-control" rows="3" name="grades[{{ $loop->index }}][grade_comments]" placeholder="Enter comments"></textarea>
					  </td>

                    {{-- Date Input --}}
                    <td>
                      <input
                        type="date"
                        name="grades[{{ $loop->index }}][date]"
                        value="{{ old("grades.{$loop->index}.date", $existing->date ?? $today) }}"
                        class="form-control form-control-sm set_change_grades_date_cls"
                      >
                      @error("grades.{$loop->index}.date")
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                    </td>

                    {{-- Existing Grade (decimal 1.0–5.0) --}}
                    <td>
                      @if($existing)
                        <span>{{ number_format($existing->grade, 1) }}</span>
                      @else
                        <span class="text-muted">—</span>
                      @endif
                    </td>

                    {{-- Pass/Fail Status --}}
                    <td>
                      @if($existing)
                        @if($existing->grade >= 3.5)
                          <span class="badge badge-success">Pass</span>
                        @else
                          <span class="badge badge-danger">Fail</span>
                        @endif
                      @else
                        <span class="text-muted">—</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            {{-- Submit Button --}}
            <div class="mt-3 text-right">
              <button
                type="submit"
                class="btn btn-primary"
              >
                <i class="fas fa-save"></i> Save All Grades
              </button>
            </div>
          </form>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>
</div>
@endsection
