@extends('layouts.app')
@section('main-container')

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Summary Cards -->
    <div class="row">
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $classesCount }}</h3>
            <p>Classes</p>
          </div>
          <div class="icon"><i class="fas fa-chalkboard"></i></div>
          <a href="{{ route('teacher.subjects.list') }}" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div><!-- ./col -->

      <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{ $subjectsCount }}</h3>
            <p>Subjects Taught</p>
          </div>
          <div class="icon"><i class="fas fa-book"></i></div>
          <a href="{{ route('teacher.subjects.list') }}" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div><!-- ./col -->

      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ $studentsCount }}</h3>
            <p>Students</p>
          </div>
          <div class="icon"><i class="fas fa-user-graduate"></i></div>
          <a href="{{ route('grades.view') }}" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div><!-- ./col -->

      <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{ $attendanceCount }}</h3>
            <p>Attendance Records</p>
          </div>
          <div class="icon"><i class="fas fa-calendar-check"></i></div>
          <a href="{{ route('teacher.attendance.list') }}" class="small-box-footer">
             More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div><!-- ./col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <div class="col-12 text-center mt-4">
        <h4><strong>Welcome, {{ Auth::user()->first_name }}!</strong></h4>
        <p>Select an action from the summary cards above.</p>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection
