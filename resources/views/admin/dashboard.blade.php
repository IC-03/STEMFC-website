@extends('layouts.app')
@section('main-container')

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center mt-2">
                    <h4><strong>Welcome, {{ Auth::user()->first_name }}!</strong></h4>
                    <p>Select an action from the summary cards below.</p>
                </div>
              </div>
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $admins }}</h3>

                            <p>All Admins</p>
                        </div>
                        <div class="icon">
                            <i class="far fa-star"></i>
                            {{-- <i class="ion ion-bag"></i> --}}
                        </div>
                        <a href="{{ route('admin.admin.list') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $students }}</h3>

                            <p>Students</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <a href="{{ route('admin.student.list') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $teachers }}</h3>

                            <p>Professors</p>
                        </div>
                        <div class="icon">
                            {{-- <i class="fas fa-user-graduate"></i> --}}
                            <i class="fas fa fa-chalkboard-teacher"></i>
                        </div>
                        <a href="{{ route('admin.professor.list') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $parents }}</h3>

                            <p>Guardians</p>
                        </div>
                        <div class="icon">
                            <i class="nav-icon fas fa-people-carry"></i>
                        </div>
                        <a href="{{ route('admin.guardian.list') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        @include('_message')


        <!-- /.row -->
        <!-- Main row -->
    </section>


@endsection