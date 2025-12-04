{{-- resources/views/student/myGrades.blade.php --}}
@extends('layouts.app')

@php
  $title = 'Mis Calificaciones';
@endphp

@section('main-container')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        {{-- Tarjeta: Filtro + Resultados --}}
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Mis Calificaciones</h3>
          </div>

          <div class="card-body">
            {{-- Formulario de Filtro --}}
            <form method="GET" action="{{ route('student.grades') }}" class="form-inline mb-4">
              {{-- Cursos --}}
              <div class="form-group mr-3">
                <label for="course_id" class="mr-2 font-weight-bold">Curso</label>
                <select name="course_id" id="course_id" class="form-control">
                  <option value="">Todos los cursos</option>
                  @foreach($courses as $course)
                    <option value="{{ $course->id }}"
                      {{ (string)$selectedCourse === (string)$course->id ? 'selected' : '' }}>
                      {{ $course->course_name }}
                    </option>
                  @endforeach
                </select>
                

              </div>

              {{-- Tipos de Evaluación --}}
              <div class="form-group mr-3">
                <label for="type" class="mr-2 font-weight-bold">Evaluación</label>
                <select name="type" id="type" class="form-control">
                  <option value="">Todos los tipos</option>
                  <option value="test"      {{ $selectedType === 'test'      ? 'selected' : '' }}>Prueba</option>
                  <option value="classwork" {{ $selectedType === 'classwork' ? 'selected' : '' }}>Trabajo en clase</option>
                  <option value="homework"  {{ $selectedType === 'homework'  ? 'selected' : '' }}>Tarea</option>
                </select>
              </div>

              <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Ver Calificaciones
              </button>
            </form>

                <p class="text-right">
                  <a href="#final-grades" class="btn btn-outline-secondary btn-sm">
                    Ir a Calificaciones Finales <i class="fas fa-arrow-down ml-1"></i>
                  </a>
                </p>
           

            {{-- Tabla de Calificaciones --}}
            @if($grades->isEmpty())
              <div class="alert alert-info">
                No se encontraron calificaciones con los filtros seleccionados.
              </div>
            @else
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Fecha</th>
                      <th>Curso</th>
                      <th>Tipo</th>
                      <th>Porcentaje</th>
                      <th>Nota</th>
                      <th>Estado</th>
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
                        <td>{{ \Carbon\Carbon::parse($grade->date)->format('d M, Y') }}</td>
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
                        <td>{{ number_format( $calculateOriginalGrade, 1) }}</td>
                        <td>
                          @if( $calculateOriginalGrade >= 3.5)
                            <span class="badge badge-success">Aprobado</span>
                          @else
                            <span class="badge badge-danger">Reprobado</span>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        </div>

        {{-- ────── CALIFICACIONES FINALES POR CURSO ────── --}}
        @if($grades->isNotEmpty())
          @php
            $byCourse = $grades->groupBy('course_id');
            $idx = 1;
          @endphp

          <div class="card mt-4" id="final-grades">
            <div class="card-header bg-secondary text-white">
              <h3 class="card-title">Calificaciones Finales por Curso</h3>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="final-grades-table" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Curso</th>
                      <th>Prom. Pruebas (%)</th>
                      <th>Prom. Clase (%)</th>
                      <th>Prom. Tarea (%)</th>
                      <th>Prom. Final (%)</th>
                      <th>Nota Final</th>
                      <th>Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($byCourse as $courseId => $courseGrades)
                   <?php
                        
                        $courseName = $courseGrades->first()->course->course_name;
					  
					  $testPAvg  = $courseGrades->where('assessment_type','test')->avg('percentage')   ?? 0;
                $cwPAvg    = $courseGrades->where('assessment_type','classwork')->avg('percentage') ?? 0;
                $hwPAvg    = $courseGrades->where('assessment_type','homework')->avg('percentage')  ?? 0;
						
						$testfinalPAvg =  $testPAvg*0.60;
				
						$cwfinalPAvg =  $cwPAvg*0.30;		
			
						$hwfinalPAvg =  $hwPAvg*0.10;	
				
					  $finalAvgPercenage = $testfinalPAvg+$cwfinalPAvg+$hwfinalPAvg;		
						
						
					  $testAvg  = $courseGrades->where('assessment_type','test')->avg('grade')   ?? 0;
                      $cwAvg    = $courseGrades->where('assessment_type','classwork')->avg('grade') ?? 0;
                      $hwAvg    = $courseGrades->where('assessment_type','homework')->avg('grade')  ?? 0;
						
					
						$testfinalAvg =  $testAvg*0.60;
				
						$cwfinalAvg =  $cwAvg*0.30;		
			
						$hwfinalAvg =  $hwAvg*0.10;	
				
					  $grandTotalAvg = $testfinalAvg+$cwfinalAvg+$hwfinalAvg;
					  
					  
					    $status = $grandTotalAvg >= 3.5 ? 'Aprobado' : 'Reprobado';
					  
					  
                   ?>
                      <tr>
                        <td>{{ $idx++ }}</td>
                        <td>{{ $courseName }}</td>
                        <td>{{ number_format($testPAvg,2) }}</td>
                        <td>{{ number_format($cwPAvg,2) }}</td>
                        <td>{{ number_format($hwPAvg,2) }}</td>
                        <td>{{ number_format($finalAvgPercenage,2) }}</td>
                        <td>{{ number_format($grandTotalAvg,1) }}</td>
                        <td>
                          @if($status === 'Aprobado')
                            <span class="badge badge-success">Aprobado</span>
                          @else
                            <span class="badge badge-danger">Reprobado</span>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @endif

      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  $(function(){
    $('#final-grades-table').DataTable({
      dom: 'Bfrtip',
      buttons: ['copy','csv','excel','pdf','print','pageLength'],
      lengthMenu: [[10, 25, 50, -1],[10,25,50,"Todos"]],
      responsive: true,
      paging:   true,
      searching:true,
      ordering: true
    });
  });
</script>
@endpush
