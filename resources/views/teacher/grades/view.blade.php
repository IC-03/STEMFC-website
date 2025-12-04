@extends('layouts.app')

@php
  $title = "View Student's Grades";
@endphp

@section('main-container')

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <!-- Card Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title" id="All">
            All Grades
            <span class="badge badge-warning right">{{ $grades->count() }}</span>
          </h3>
          <div>
            <a href="{{ route('grades.select.course') }}" class="btn btn-outline-primary mr-2">
              <i class="fas fa-plus-square"></i> Add New Grade
            </a>
            <a href="#final-grades" class="btn btn-outline-secondary">
              <i class="fas fa-graduation-cap"></i> Final Grades
            </a>
          </div>
        </div>

        <!-- Card Body -->
        <div class="card-body">
          <table id="all-grades-table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Date</th>
                <th>Student</th>
                <th>Course</th>
                <th>Assessment Type</th>
                <th>Percentage</th>
                <th>Grade Level</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
				<?php
        $typeTotals = [
            'test' => ['sum' => 0, 'count' => 0],
            'classwork' => ['sum' => 0, 'count' => 0],
            'homework' => ['sum' => 0, 'count' => 0],
            'other' => ['sum' => 0, 'count' => 0],
        ];
										 
										 
    ?>
              @foreach($grades as $grade)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ \Carbon\Carbon::parse($grade->date)->format('M d, Y') }}</td>
                  <td>{{ $grade->student->full_name }} ({{ $grade->student->id_no }})</td>
                  <td>{{ $grade->course->course_name }}</td>
                  <td class="text-capitalize">{{ $grade->assessment_type }}</td>
                  <td>
					   <?php
                    $calculateOriginalGradeORG = $grade->percentage / 2 / 10; 
					  
					  
					$calculateOriginalGrade = $grade->grade;

                    $type = $grade->assessment_type;
                    if (!isset($typeTotals[$type])) {
                        $type = 'other';
                    }

                    $typeTotals[$type]['sum'] += $calculateOriginalGrade;
                    $typeTotals[$type]['count']++;
                ?>
					  
					  
					  
					  {{ $grade->percentage }}%</td>
                  <td>{{ number_format($calculateOriginalGrade, 1) }}</td>
                  <td>
                    @if($calculateOriginalGrade >= 3.5)
                      <span class="badge badge-success">Pass</span>
                    @else
                      <span class="badge badge-danger">Fail</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm">
                      <a href="{{ route('grades.edit', $grade->id) }}"
                         class="btn btn-outline-primary"
                         title="Edit">
                        <i class="fas fa-edit"></i>
                      </a>
                      <button type="button"
                              class="btn btn-outline-danger"
                              onclick="confirmDelete('{{ $grade->id }}', '{{ $grade->student->full_name }}')"
                              title="Delete">
                        <i class="fas fa-trash"></i>
                      </button>
                      <form id="delete-form-{{ $grade->id }}"
                            action="{{ route('grades.delete', $grade->id) }}"
                            method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>

  {{-- ────── FINAL GRADES BY STUDENT ────── --}}
  @if($grades->isNotEmpty())
    @php
      // group and then sort by the student's full name
      $byStudent = $grades
        ->groupBy('student_id')
        ->sortBy(fn($grp) => $grp->first()->student->full_name);
      $idx = 1;
    @endphp

    <div id="final-grades" class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Final Grades by Student</h3>
            <a href="#All" class="btn btn-outline-secondary">
              <i class="fas fa-arrow-up"></i> Back to All Grades
            </a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="final-grades-table" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Test Avg (%)</th>
                    <th>Classwork Avg (%)</th>
                    <th>Homework Avg (%)</th>
                    <th>Final Pct (%)</th>
                    <th>Grade Level</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
					
                  <?php
					
					foreach($byStudent as $studentGrades){
               
                      /*$testAvg  = $studentGrades->where('assessment_type','test')->avg('percentage')   ?? 0;
                      $cwAvg    = $studentGrades->where('assessment_type','classwork')->avg('percentage') ?? 0;
                      $hwAvg    = $studentGrades->where('assessment_type','homework')->avg('percentage')  ?? 0;
                      $finalPct = $testAvg*0.5 + $cwAvg*0.3 + $hwAvg*0.2;
                      $rawGrade = 1 + ($finalPct/100)*4;
                      $finalGrade = round($rawGrade*2)/2;
                      $finalGrade = max(1.0, min(5.0, $finalGrade));
                      $status   = $finalGrade >= 3.5 ? 'Pass' : 'Fail';
                      $studentName = $studentGrades->first()->student->full_name;
					
					
		
				
						$testfinalAvg =  $testAvg*0.60;
				
						$cwfinalAvg =  $cwAvg*0.30;		
			
						$hwfinalAvg =  $hwAvg*0.10;	
				
					  $finalAvgPercenage = $testfinalAvg+$cwfinalAvg+$hwfinalAvg;
					
					
					$grandTotalAvg =   ($finalAvgPercenage/2)/10;*/
					  $studentName = $studentGrades->first()->student->full_name;
					
				$testPAvg  = $studentGrades->where('assessment_type','test')->avg('percentage')   ?? 0;
                $cwPAvg    = $studentGrades->where('assessment_type','classwork')->avg('percentage') ?? 0;
                $hwPAvg    = $studentGrades->where('assessment_type','homework')->avg('percentage')  ?? 0;
						
						$testfinalPAvg =  $testPAvg*0.60;
				
						$cwfinalPAvg =  $cwPAvg*0.30;		
			
						$hwfinalPAvg =  $hwPAvg*0.10;	
				
					  $finalAvgPercenage = $testfinalPAvg+$cwfinalPAvg+$hwfinalPAvg;		
						
						
						
					  $testAvg  = $studentGrades->where('assessment_type','test')->avg('grade')   ?? 0;
                      $cwAvg    = $studentGrades->where('assessment_type','classwork')->avg('grade') ?? 0;
                      $hwAvg    = $studentGrades->where('assessment_type','homework')->avg('grade')  ?? 0;
						
						
                      $finalPct = $testAvg*0.5 + $cwAvg*0.3 + $hwAvg*0.2;
						
                      $rawGrade = 1 + ($finalPct/100)*4;
						
                      $finalGrade = round($rawGrade*2)/2;
						
                      $finalGrade = max(1.0, min(5.0, $finalGrade));
						
                      $status2   = $finalGrade >= 3.5 ? 'Pass' : 'Fail';
						
                      
					
					
		
				
						$testfinalAvg =  $testAvg*0.60;
				
						$cwfinalAvg =  $cwAvg*0.30;		
			
						$hwfinalAvg =  $hwAvg*0.10;	
				
					  $grandTotalAvg = $testfinalAvg+$cwfinalAvg+$hwfinalAvg;
					
					   $status   = $grandTotalAvg >= 3.5 ? 'Pass' : 'Fail';
				
					
			
					?>
                    <tr>
                      <td>{{ $idx++ }}</td>
                      <td>{{ $studentName }}</td>
                      <td>{{ number_format($testPAvg,2) }}</td>
                      <td>{{ number_format($cwPAvg,2) }}</td>
                      <td>{{ number_format($hwPAvg,2) }}</td>
                      <td>{{ number_format($finalAvgPercenage,2) }}</td>
                      <td>{{ number_format($grandTotalAvg,1) }}</td>
                      <td>
                        @if($status === 'Pass')
                          <span class="badge badge-success">Pass</span>
                        @else
                          <span class="badge badge-danger">Fail</span>
                        @endif
                      </td>
                    </tr>
                <?php 		}?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  @endif

</div>

{{-- SweetAlert2 for delete confirmation --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function confirmDelete(id, studentName) {
    Swal.fire({
      title: `Delete grade for ${studentName}?`,
      text: "This action cannot be undone.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel'
    }).then(result => {
      if (result.isConfirmed) {
        document.getElementById(`delete-form-${id}`).submit();
      }
    });
  }
</script>

@push('scripts')
<script>
  $(function(){
    $('#all-grades-table').DataTable({
      dom: 'lBfrtip',
      responsive: true,
      paging: true,
      ordering: true,
      info: true,
      // load all rows, not just 10
      pageLength: -1,
      lengthMenu: [[10, 50, 100, -1], [10, 50, 100, "All"]],
      searching: true,
      buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });
    $('#final-grades-table').DataTable({
      dom: 'lBfrtip',
      responsive: true,
      paging: true,
      ordering: true,
      info: true,
      // load all rows, not just 10
      pageLength: -1,
      lengthMenu: [[10, 50, 100, -1], [10, 50, 100, "All"]],
      searching: true,
      buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });
  });
</script>
@endpush

@endsection
