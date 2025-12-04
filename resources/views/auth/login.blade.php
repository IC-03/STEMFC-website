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

<body class="vertical-layout vertical-menu-modern 1-column  bg-full-screen-image blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
    <!-- BEGIN: Content-->

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
                <section class="row flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-lg-4 col-md-8 col-10 box-shadow-2 p-0">
                            <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                                <div class="card-header border-0">
                                    <div class="card-title text-center">
                                        <img src="{{ url('dist/img/AdminLTELogo.png') }}" class="branding text-center" alt="branding logo">
                                        <span class="display-5 font-weight-bold"> STEM Foundation</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-1"><span>Account Details</span></p>
                                    <div class="card-body">
                                        @include('_message')

                                        <form class="form-horizontal" action="{{ route('auth.login') }}" method="POST">
                                            @csrf
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="number" class="form-control" id="user-id" name="id_no" value="{{ request('id_no') }}" placeholder="Your ID Number" autocomplete="on" required>
                                                <div class="form-control-position">
                                                    <i class="la la-user"></i>
                                                </div>
                                            </fieldset>
                                            <fieldset class="form-group position-relative has-icon-left has-icon-right">
                                                <input type="password" class="form-control" id="user-password" name="password" placeholder="Enter Password" autocomplete="off" required>
                                                <div class="form-control-position">
                                                    <i class="la la-key"></i>
                                                </div>
                                                <div style="position:absolute; top:50%; right:15px; transform:translateY(-50%); cursor:pointer;">
                                                    <i id="toggle-password" class="la la-eye"></i>
                                                </div>
                                            </fieldset>
                                            <div class="form-group row">
                                                <div class="col-sm-6 col-12 text-center text-sm-left pr-0">
                                                    <fieldset>
                                                        <input type="checkbox" name="remember" id="remember-me" class="chk-remember" {{ request('remember') ? 'checked' : '' }}>
                                                        <label for="remember-me"> Remember Me</label>
                                                    </fieldset>
                                                </div>
                                                <div class="col-sm-6 col-12 float-sm-left text-center text-sm-right">
                                                    <a href="{{ route('auth.forgotpassword') }}" class="card-link">Forgot Password?</a>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-outline-info btn-block"><i class="ft-unlock"></i> Login</button>
                                        </form>
                                    </div>
                                   <!-- 
                                    <p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-0"><span>New to STEM?</span>
                                    </p>
                                    <div class="card-body">
                                        <a href="{{ route('auth.register') }}" class="btn btn-outline-danger btn-block">
                                            <i class="la la-user"></i>Register
                                        </a>
                                    </div>
                                   -->
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{ url('dist/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- Bootstrap JS (required for dismissible alerts) -->
     <script src="{{ url('dist/js/bootstrap.js') }}"></script>
    <script src="{{ url('dist/js/bootstrap-alert.js') }}"></script>


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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggle = document.getElementById('toggle-password');
            var password = document.getElementById('user-password');
            toggle.addEventListener('click', function() {
                var type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.classList.toggle('la-eye');
                this.classList.toggle('la-eye-slash');
            });
        });
    </script>


    <script src="{{ url('dist/js/bootstrap.bundle.min.js') }}"></script>


    <!-- BEGIN: Page JS-->
    {{-- <script src="{{ url('dist/js/form-login-register.js') }}"></script> --}}
    <!-- END: Page JS-->


</body>
<!-- END: Body-->

</html>





