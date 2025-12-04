<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ !empty($title)? $title:'' }}</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Bootstrap Color Picker -->
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <!-- Bootstrap4 Duallistbox -->
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
        <!-- BS Stepper -->
        <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
        <!-- dropzonejs -->
        <link rel="stylesheet" href="{{ asset('plugins/dropzone/min/dropzone.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

        <link rel="icon" type="image/x-icon" href="{{ asset('dist/img/favicon.ico') }}">
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <!-- JQVMap -->
        <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
        <!-- Theme style -->
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

        <!-- Toastr -->
        <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css' ) }}">

        <!-- summernote -->
        <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        {{-- @if(Route::currentRouteName() === 'admin.attendance.add')
            <meta name="csrf-token" content="{{ csrf_token() }}">
        @endif --}}

        <!-- jQuery (required by AdminLTE pushmenu) -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
$.widget.bridge('uibutton', $.ui.button)
</script>

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
      top: 57px;                    /* directly below the header */
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
      margin-top: 57px;             /* below the header */
      margin-left: 250px;           /* beside the fixed sidebar */
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

    </head>
    <body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed">
    <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="Admin Logo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <!--
        <li class="nav-item d-none d-sm-inline-block">
            <a href="index3.html" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>
    -->
      
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
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
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
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
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
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @if(auth()->user()->role_id == 1)
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::segment(2)=='dashboard'? 'active': '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.admin.list') }}" class="nav-link {{ Request::segment(2)=='admin'? 'active': '' }}">
                    <i class="nav-icon fa fa-star"></i>
                    <p>Admin</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.student.list') }}" class="nav-link {{ Request::segment(2)=='student'? 'active': '' }}">
                    <i class="nav-icon fas fa fa-graduation-cap"></i>
                    <p>Student</p>
                </a>
            </li>
             <li class="nav-item">
                <a href="{{ route('admin.reports.fees') }}" class="nav-link {{ Request::segment(3)=='fees' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-money-bill-alt"></i>
                    <p>Fee Report</p>
                </a>
             </li>
				   <li class="nav-item">
                <a href="{{ route('admin.reports.agingreport') }}" class="nav-link {{ Request::segment(3)=='aging-report' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-money-bill-alt"></i>
                    <p>Accounts Receivable	</p>
                </a>
             </li>
			
				
             <li class="nav-item">
                <a href="{{ route('admin.attendance.index') }}" class="nav-link {{ Request::segment(2)=='attendance' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clipboard-list"></i>
                    <p>Attendance</p>
                </a>
             </li>
				
				    <li class="nav-item">
                <a href="{{ route('attendance.sheet') }}" class="nav-link {{ Request::segment(1)=='attendance-sheet' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clipboard-list"></i>
                    <p>Attendance Sheet</p>
                </a>
             </li>
				

            <li class="nav-item">
                <a href="{{ route('admin.registration.list') }}" class="nav-link {{ Request::segment(2)=='registration'? 'active': '' }}">
                    <i class="nav-icon fas fa-clipboard-list"></i>
                    <p>Registration</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.class.list') }}" class="nav-link {{ Request::segment(2)=='class'? 'active': '' }}">
                    <i class="nav-icon fas fa fa-chalkboard"></i>
                    <p>Class</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.course.list') }}" class="nav-link {{ Request::is('admin/course*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>Course</p>
                </a>
            </li>
           
            <li class="nav-item">
                <a href="{{ route('admin.professor.list') }}" class="nav-link {{ Request::segment(2)=='professor'? 'active': '' }}">
                    <i class="nav-icon fas fa fa-chalkboard-teacher"></i>
                    <p>Professor(Teacher)</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.guardian.list') }}" class="nav-link {{ Request::is('admin/guardian*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-people-carry"></i>
                    <p>Guardian</p>
                </a>
            </li>
            @elseif(auth()->user()->role_id == 2)
            <li class="nav-item">
                <a 
                    href="{{ route('teacher.dashboard') }}" 
                    class="nav-link {{ Request::is('teacher/dashboard') ? 'active' : '' }}"
                >
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <li class="nav-item">
                <a 
                    href="{{ route('grades.select.course') }}" 
                    class="nav-link {{ Request::is('teacher/grade/select-course') ? 'active' : '' }}"
                >
                    <i class="fas fa-arrow-circle-right nav-icon"></i>
                    <p>Grade Students</p>
                </a>
            </li>

            <li class="nav-item">
                <a 
                    href="{{ route('grades.view') }}" 
                    class="nav-link {{ Request::is('teacher/grades') ? 'active' : '' }}"
                >
                    <i class="fas fa-eye nav-icon"></i>
                    <p>View Grades</p>
                </a>
            </li>
				
			   <li class="nav-item">
                <a 
                    href="{{ route('assignments.index') }}" 
                    class="nav-link {{ Request::is('assignments') ? 'active' : '' }}"
                >
                    <i class="fas fa-eye nav-icon"></i>
                    <p>Assignments</p>
                </a>
            </li>	
<li class="nav-item">
                <a href="{{ route('attendance.sheet') }}" class="nav-link {{ Request::segment(1)=='attendance-sheet' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clipboard-list"></i>
                    <p>Attendance Sheet</p>
                </a>
             </li>

            @elseif(auth()->user()->role_id == 3)
            <li class="nav-item">
                <a href="{{ route('student.dashboard') }}" class="nav-link {{ Request::segment(2)=='dashboard' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
				<li class="nav-item">
                <a href="{{ route('attendance.sheet') }}" class="nav-link {{ Request::segment(1)=='attendance-sheet' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clipboard-list"></i>
                    <p>Attendance Sheet</p>
                </a>
             </li>
				
				   <li class="nav-item">
                <a 
                    href="{{ route('student.assignments') }}" 
                    class="nav-link {{ Request::is('student/assignments') ? 'active' : '' }}"
                >
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
            <!--
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="nav-icon fas fa-copy"></i>
                <p>
                    Layout Options
                    <i class="fas fa-angle-left right"></i>
                    <span class="badge badge-info right">6</span>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="pages/layout/top-nav.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Top Navigation</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Top Navigation + Sidebar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/layout/boxed.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Boxed</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/layout/fixed-sidebar.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Fixed Sidebar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/layout/fixed-sidebar-custom.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Fixed Sidebar <small>+ Custom Area</small></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/layout/fixed-topnav.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Fixed Navbar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/layout/fixed-footer.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Fixed Footer</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/layout/collapsed-sidebar.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Collapsed Sidebar</p>
                    </a>
                </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>
                    Charts
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="pages/charts/chartjs.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>ChartJS</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/charts/flot.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Flot</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/charts/inline.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Inline</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/charts/uplot.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>uPlot</p>
                    </a>
                </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="nav-icon fas fa-tree"></i>
                <p>
                    UI Elements
                    <i class="fas fa-angle-left right"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="pages/UI/general.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>General</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/UI/icons.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Icons</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/UI/buttons.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Buttons</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/UI/sliders.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Sliders</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/UI/modals.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Modals & Alerts</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/UI/navbar.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Navbar & Tabs</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/UI/timeline.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Timeline</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/UI/ribbons.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ribbons</p>
                    </a>
                </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="nav-icon fas fa-edit"></i>
                <p>
                    Forms
                    <i class="fas fa-angle-left right"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="pages/forms/general.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>General Elements</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/forms/advanced.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Advanced Elements</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/forms/editors.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Editors</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/forms/validation.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Validation</p>
                    </a>
                </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
                <p>
                    Tables
                    <i class="fas fa-angle-left right"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="pages/tables/simple.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Simple Tables</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/tables/data.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>DataTables</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/tables/jsgrid.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>jsGrid</p>
                    </a>
                </li>
                </ul>
            </li>
            <li class="nav-header">EXAMPLES</li>
            <li class="nav-item">
                <a href="pages/calendar.html" class="nav-link">
                <i class="nav-icon far fa-calendar-alt"></i>
                <p>
                    Calendar
                    <span class="badge badge-info right">2</span>
                </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/gallery.html" class="nav-link">
                <i class="nav-icon far fa-image"></i>
                <p>
                    Gallery
                </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/kanban.html" class="nav-link">
                <i class="nav-icon fas fa-columns"></i>
                <p>
                    Kanban Board
                </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="nav-icon far fa-envelope"></i>
                <p>
                    Mailbox
                    <i class="fas fa-angle-left right"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="pages/mailbox/mailbox.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Inbox</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/mailbox/compose.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Compose</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/mailbox/read-mail.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Read</p>
                    </a>
                </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>
                    Pages
                    <i class="fas fa-angle-left right"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="pages/examples/invoice.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Invoice</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/profile.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Profile</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/e-commerce.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>E-commerce</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/projects.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Projects</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/project-add.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Project Add</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/project-edit.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Project Edit</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/project-detail.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Project Detail</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/contacts.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Contacts</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/faq.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>FAQ</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/contact-us.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Contact us</p>
                    </a>
                </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="nav-icon far fa-plus-square"></i>
                <p>
                    Extras
                    <i class="fas fa-angle-left right"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                        Login & Register v1
                        <i class="fas fa-angle-left right"></i>
                    </p>
                    </a>
                    <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="pages/examples/login.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Login v1</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages/examples/register.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Register v1</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages/examples/forgot-password.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Forgot Password v1</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages/examples/recover-password.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Recover Password v1</p>
                        </a>
                    </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                        Login & Register v2
                        <i class="fas fa-angle-left right"></i>
                    </p>
                    </a>
                    <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="pages/examples/login-v2.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Login v2</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages/examples/register-v2.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Register v2</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages/examples/forgot-password-v2.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Forgot Password v2</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages/examples/recover-password-v2.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Recover Password v2</p>
                        </a>
                    </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/lockscreen.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Lockscreen</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/legacy-user-menu.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Legacy User Menu</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/language-menu.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Language Menu</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/404.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Error 404</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/500.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Error 500</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/pace.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Pace</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/examples/blank.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Blank Page</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="starter.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Starter Page</p>
                    </a>
                </li>
                </ul>
            </li>
            <li class="nav-header">MISCELLANEOUS</li>
            <li class="nav-item">
                <a href="iframe.html" class="nav-link">
                <i class="nav-icon fas fa-ellipsis-h"></i>
                <p>Tabbed IFrame Plugin</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="https://adminlte.io/docs/3.1/" class="nav-link">
                <i class="nav-icon fas fa-file"></i>
                <p>Documentation</p>
                </a>
            </li>
            <li class="nav-header">MULTI LEVEL EXAMPLE</li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="fas fa-circle nav-icon"></i>
                <p>Level 1</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="nav-icon fas fa-circle"></i>
                <p>
                    Level 1
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Level 2</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                        Level 2
                        <i class="right fas fa-angle-left"></i>
                    </p>
                    </a>
                    <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Level 3</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Level 3</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Level 3</p>
                        </a>
                    </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Level 2</p>
                    </a>
                </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="fas fa-circle nav-icon"></i>
                <p>Level 1</p>
                </a>
            </li>
            <li class="nav-header">LABELS</li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="nav-icon far fa-circle text-danger"></i>
                <p class="text">Important</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="nav-icon far fa-circle text-warning"></i>
                <p>Warning</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="nav-icon far fa-circle text-info"></i>
                <p>Informational</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('auth.logout') }}" class="nav-link">
                <i class="nav-icon far fa-circle text-info"></i>
                <p>Logout</p>
                </a>
            </li>
            </ul>
        -->
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
</body>
</html>
