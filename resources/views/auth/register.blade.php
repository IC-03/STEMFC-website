<!DOCTYPE html>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Modern admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities with bitcoin dashboard.">
    <meta name="keywords" content="admin template, modern admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
    <meta name="author" content="PIXINVENT">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/x-icon" href="{{ url('dist/img/favicon.ico') }}">
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ url('dist/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('dist/css/icheck.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('dist/css/custom.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ url('dist/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('dist/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('dist/css/components.css') }}">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ url('dist/css/vertical-menu-modern.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('dist/css/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('dist/css/login-register.css') }}">
    <!-- END: Page CSS-->

    <link rel="stylesheet" type="text/css" href="{{ url('dist/css/bootstrap-switch.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('dist/css/switchery.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('dist/css/switch.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('dist/css/palette-switch.css') }}">

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ url('dist/css/style.css') }}">
    <!-- END: Custom CSS-->
    <style>
        .branding{
            height: 5em;
        }

    </style>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="horizontal-layout horizontal-menu 1-column bg-full-screen-image blank-page" data-open="hover" data-menu="horizontal-menu" data-col="1-column">
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <section class="row flexbox-container">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="col-lg-4 col-md-8 col-10 box-shadow-2 p-0">
                        <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                            <div class="card-header border-0 pb-0 text-center">
                                <img src="{{ url('dist/img/AdminLTELogo.png') }}" class="branding mb-2" alt="branding logo">
                                <h3 class="font-weight-bold">STEM Foundation</h3>
                            </div>
                            <div class="card-content">
                                <p class="card-subtitle text-muted text-center font-small-3 my-2">
                                    <span>Registration Form</span>
                                </p>
                                <div class="card-body">
                                    @include('_message')

                                    <form action="{{ route('auth.store') }}" method="POST" novalidate>
                                        @csrf

                                        <!-- First Name -->
                                        <div class="form-group">
                                            <label for="firstname">First Name</label>
                                            <input
                                                type="text"
                                                class="form-control @error('firstname') is-invalid @enderror"
                                                id="firstname"
                                                name="firstname"
                                                placeholder="First Name"
                                                value="{{ old('firstname') }}"
                                                required
                                            >
                                            @error('firstname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Last Name -->
                                        <div class="form-group">
                                            <label for="lastname">Last Name</label>
                                            <input
                                                type="text"
                                                class="form-control @error('lastname') is-invalid @enderror"
                                                id="lastname"
                                                name="lastname"
                                                placeholder="Last Name"
                                                value="{{ old('lastname') }}"
                                                required
                                            >
                                            @error('lastname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input
                                                type="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                id="email"
                                                name="email"
                                                placeholder="Your Email Address"
                                                value="{{ old('email') }}"
                                                required
                                            >
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Password -->
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input
                                                type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="password"
                                                name="password"
                                                placeholder="Enter Password"
                                                required
                                            >
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input
                                                type="password"
                                                class="form-control"
                                                id="password_confirmation"
                                                name="password_confirmation"
                                                placeholder="Confirm Password"
                                                required
                                            >
                                        </div>

                                        <button type="submit" class="btn btn-outline-info btn-block">
                                            <i class="la la-user"></i> Register
                                        </button>
                                    </form>
                                </div>

                                <div class="text-center mt-3">Already Member?</div>
                                <div class="card-body pt-0">
                                    <a href="{{ route('login') }}" class="btn btn-outline-danger btn-block">
                                        <i class="ft-unlock"></i> Login
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- END: Content-->


<!-- BEGIN: Vendor JS-->
<script src="{{ url('dist/js/vendors.min.js') }}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ url('dist/js/jqBootstrapValidation.js') }}"></script>
<script src="{{ url('dist/js/icheck.min.js') }}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ url('dist/js/app-menu.js') }}"></script>
<script src="{{ url('dist/js/app.js') }}"></script>
<script src="{{ url('dist/js/bootstrap-switch.min.js') }}"></script>
<script src="{{ url('dist/js/switchery.min.js') }}"></script>
<script src="{{ url('dist/js/switch.js') }}"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->

<!-- END: Page JS-->
</body>

</html>
