<style>
    /* ─── FIXED HEADER ───────────────────────────────────────────────────────── */
    .main-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1030;
        height: 57px;
    }

    /* ─── FIXED SIDEBAR (LARGE SCREENS ONLY) ─────────────────────────────────── */
    @media (min-width: 768px) {
        .main-sidebar {
            position: fixed !important;
            top: 57px;
            /* directly below the header */
            left: 0;
            width: 250px;
            height: calc(100vh - 57px);
            overflow-y: auto;
            background-color: #343a40;
            z-index: 1020;
            /* We’ll still let AdminLTE toggle “sidebar-collapse” on body to push it out */
            transition: margin-left 0.3s ease;
        }

        .content-wrapper {
            margin-top: 57px;
            /* below the header */
            margin-left: 250px;
            /* beside the fixed sidebar */
            padding: 20px;
            min-height: calc(100vh - 57px);
            transition: margin-left 0.3s ease;
        }

        /* When AdminLTE adds “sidebar-collapse” on <body>, collapse the sidebar */
        body.sidebar-collapse .main-sidebar {
            margin-left: -250px;
        }

        body.sidebar-collapse .content-wrapper {
            margin-left: 0;
        }
    }

    /* ─── SMALL SCREENS (<768px): DO NOT OVERRIDE AdminLTE’s DEFAULT ────────── */
    @media (max-width: 767.98px) {

        /* Just push content below the header; let AdminLTE handle the sidebar */
        .content-wrapper {
            margin-top: 57px;
            margin-left: 0;
            padding: 20px;
            min-height: calc(100vh - 57px);
        }
    }

    /* ─── COMMON SIDEBAR COLORS & LINKS ───────────────────────────────────── */
    .main-sidebar .brand-link,
    .main-sidebar .sidebar {
        background-color: #343a40;
    }

    .main-sidebar .nav-sidebar .nav-link {
        color: #c2c7d0;
    }

    .main-sidebar .nav-sidebar .nav-link:hover,
    .main-sidebar .nav-sidebar .nav-link.active {
        background-color: #4b545c;
        color: #fff;
    }

    /* ─── MAKE HAMBURGER CLICKABLE ─────────────────────────────────────────── */
    .main-header .nav-link[data-widget="pushmenu"] {
        cursor: pointer;
    }
</style>

<!-- <body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed"> -->
<div class="wrapper">

    <!-- Preloader -->
    <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="Admin Logo" height="60"
                width="60">
        </div> -->

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>

        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->
            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="fas fa-search"></i>
                </a>
                <div class="navbar-search-block">
                    <form class="form-inline">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">STEM Foundation</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info d-block" style="color:white">
                    {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    @if(auth()->user()->role_id == 1)
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link {{ Request::segment(2) == 'dashboard' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.admin.list') }}"
                                class="nav-link {{ Request::segment(2) == 'admin' ? 'active' : '' }}">
                                <i class="nav-icon fa fa-star"></i>
                                <p>Admin</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.student.list') }}"
                                class="nav-link {{ Request::segment(2) == 'student' ? 'active' : '' }}">
                                <i class="nav-icon fas fa fa-graduation-cap"></i>
                                <p>Student</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reports.fees') }}"
                                class="nav-link {{ Request::segment(3) == 'fees' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-money-bill-alt"></i>
                                <p>Fee Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reports.agingreport') }}"
                                class="nav-link {{ Request::segment(3) == 'aging-report' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-money-bill-alt"></i>
                                <p>Accounts Receivable </p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route('admin.attendance.index') }}"
                                class="nav-link {{ Request::segment(2) == 'attendance' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>Attendance</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('attendance.sheet') }}"
                                class="nav-link {{ Request::segment(1) == 'attendance-sheet' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>Attendance Sheet</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route('admin.registration.list') }}"
                                class="nav-link {{ Request::segment(2) == 'registration' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>Registration</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.class.list') }}"
                                class="nav-link {{ Request::segment(2) == 'class' ? 'active' : '' }}">
                                <i class="nav-icon fas fa fa-chalkboard"></i>
                                <p>Class</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.course.list') }}"
                                class="nav-link {{ Request::is('admin/course*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-book"></i>
                                <p>Course</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.professor.list') }}"
                                class="nav-link {{ Request::segment(2) == 'professor' ? 'active' : '' }}">
                                <i class="nav-icon fas fa fa-chalkboard-teacher"></i>
                                <p>Professor(Teacher)</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.guardian.list') }}"
                                class="nav-link {{ Request::is('admin/guardian*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-people-carry"></i>
                                <p>Guardian</p>
                            </a>
                        </li>
                    @elseif(auth()->user()->role_id == 2)
                        <li class="nav-item">
                            <a href="{{ route('teacher.dashboard') }}"
                                class="nav-link {{ Request::is('teacher/dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('grades.select.course') }}"
                                class="nav-link {{ Request::is('teacher/grade/select-course') ? 'active' : '' }}">
                                <i class="fas fa-arrow-circle-right nav-icon"></i>
                                <p>Grade Students</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('grades.view') }}"
                                class="nav-link {{ Request::is('teacher/grades') ? 'active' : '' }}">
                                <i class="fas fa-eye nav-icon"></i>
                                <p>View Grades</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('assignments.index') }}"
                                class="nav-link {{ Request::is('assignments') ? 'active' : '' }}">
                                <i class="fas fa-eye nav-icon"></i>
                                <p>Assignments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('attendance.sheet') }}"
                                class="nav-link {{ Request::segment(1) == 'attendance-sheet' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>Attendance Sheet</p>
                            </a>
                        </li>

                    @elseif(auth()->user()->role_id == 3)
                        <li class="nav-item">
                            <a href="{{ route('student.dashboard') }}"
                                class="nav-link {{ Request::segment(2) == 'dashboard' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('attendance.sheet') }}"
                                class="nav-link {{ Request::segment(1) == 'attendance-sheet' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>Attendance Sheet</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('student.assignments') }}"
                                class="nav-link {{ Request::is('student/assignments') ? 'active' : '' }}">
                                <i class="fas fa-eye nav-icon"></i>
                                <p>My Assignments</p>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('auth.logout') }}" class="nav-link">
                            <i class="nav-icon far fa fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </li>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $title }} </h1>
                    </div><!-- /.col -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- </body> -->