@extends('layouts.app')

@section('main-container')

@php
    // Cálculo de tarifas y saldos...
    $stratum = $student->stratum ?? null;

    switch ($stratum) {
        case 1:
        case 2:
            $baseFee = 180000;
            break;
        case 3:
            $baseFee = 220000;
            break;
        case 4:
            $baseFee = 280000;
            break;
        default:
            $baseFee = 0;
    }

    $discount = 20000;
    $fullPaymentAmount = $baseFee - $discount;

    $totalPaid = optional($student->payments)->sum('amount_paid') ?? 0;
    $firstPaymentOption = $student->payments->first()->amount_to_pay ?? null;

    if ($firstPaymentOption == $fullPaymentAmount) {
        $displayTotalFee = $fullPaymentAmount;
    } else {
        $displayTotalFee = $baseFee;
    }

    $displayBalance = $displayTotalFee - $totalPaid;
@endphp

<section class="content" style="background-color:white; padding:5px; border-radius: 7px;">
  <div class="container-fluid">

    <h4 class="mt-4">¡Bienvenido{{ Auth::user()->first_name ? '' : 'a' }}, {{ Auth::user()->first_name }}!</h4>

    {{-- Fila 1: Mis Cursos & Calificaciones --}}
    <div class="row">

      {{-- Mis Cursos --}}
      <div class="col-lg-6 col-md-12 mb-4">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Mis Cursos ({{ $courses->count() }})</h3>
          </div>
          <div class="card-body">
            @if($courses->isEmpty())
              <p>No estás inscrito en ningún curso.</p>
            @else
              <ul class="list-group">
                @foreach($courses as $course)
                  <li class="list-group-item">
                    {{ $course->course_name }}
                    <span class="float-right text-sm">
                      @if($course->groups->isNotEmpty())
                        @php
                          $groupId = $course->pivot->group_id;
                          $group = $course->groups->firstWhere('id', $groupId);
                        @endphp

                        @if($group)
                          <span class="badge badge-secondary">{{ $group->name }}</span>
                        @else
                          <span class="badge badge-secondary">Sin Grupo</span>
                        @endif
                      @else
                        <span class="badge badge-secondary">Sin Grupo</span>
                      @endif
                    </span>
                  </li>
                @endforeach
              </ul>
            @endif
          </div>
        </div>
      </div>

      {{-- Calificaciones --}}
      <div class="col-lg-6 col-md-12 mb-4">
        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Calificaciones</h3>
          </div>
          <div class="card-body text-center">
            <p class="mb-3">Ver todas tus calificaciones asignadas a continuación:</p>
            <a href="{{ route('student.grades') }}" class="btn btn-outline-success">
              <i class="fas fa-graduation-cap"></i> Ver Mis Calificaciones
            </a>
          </div>
        </div>
      </div>

    </div>

    {{-- Fila 2: Asistencia & Saldo --}}
    <div class="row">

      {{-- Asistencia con pestañas --}}
      <div class="col-lg-6 col-md-12 mb-4">
        <div class="card bg-white text-dark" x-data="{ tab: '7dias' }">
          <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center border-bottom">
            <h3 class="card-title mb-0">Asistencia</h3>
            <div class="btn-group">
              <button
                @click="tab = '7dias'"
                :class="tab === '7dias'
                  ? 'btn btn-sm btn-dark text-white'
                  : 'btn btn-sm btn-outline-dark text-dark'">
                7 Días
              </button>
              <button
                @click="tab = '1anio'"
                :class="tab === '1anio'
                  ? 'btn btn-sm btn-dark text-white'
                  : 'btn btn-sm btn-outline-dark text-dark'">
                1 Año
              </button>
              <button
                @click="tab = 'general'"
                :class="tab === 'general'
                  ? 'btn btn-sm btn-dark text-white'
                  : 'btn btn-sm btn-outline-dark text-dark'">
                Total
              </button>
            </div>
          </div>
          <div class="card-body bg-white text-dark">
            {{-- 7 Días --}}
            <div x-show="tab === '7dias'">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Presente</th>
                    <th>Ausente</th>
                    <th>Justificado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $stats7['Present'] }}</td>
                    <td>{{ $stats7['Absent'] }}</td>
                    <td>{{ $stats7['Excused'] }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            {{-- 1 Año --}}
            <div x-show="tab === '1anio'" x-cloak>
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Presente</th>
                    <th>Ausente</th>
                    <th>Justificado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $statsYear['Present'] }}</td>
                    <td>{{ $statsYear['Absent'] }}</td>
                    <td>{{ $statsYear['Excused'] }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            {{-- Total --}}
            <div x-show="tab === 'general'" x-cloak>
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Presente</th>
                    <th>Ausente</th>
                    <th>Justificado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $statsAll['Present'] }}</td>
                    <td>{{ $statsAll['Absent'] }}</td>
                    <td>{{ $statsAll['Excused'] }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- Saldo Pendiente --}}
      <div class="col-lg-6 col-md-12 mb-4">
        <div class="card bg-white text-dark">
          <div class="card-header bg-danger text-white border-bottom">
            <h3 class="card-title mb-0">Saldo Pendiente</h3>
          </div>
          <div class="card-body bg-white text-dark">
            <table class="table table-sm mb-0">
              <thead>
                <tr>
                  <th>Cuota Total</th>
                  <th>Total Pagado</th>
                  <th>Saldo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>${{ number_format($displayTotalFee, 2) }}</td>
                  <td>${{ number_format($totalPaid, 2) }}</td>
                  <td>${{ number_format($displayBalance, 2) }}</td>
                </tr>
              </tbody>
            </table>
            <div class="mt-3 text-center">
              <a
                href="{{ route('student.receipt') }}"
                class="btn btn-outline-primary"
                target="_blank"
                rel="noopener noreferrer"
              >
                Descargar Mi Recibo
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
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
</section>
@endsection
