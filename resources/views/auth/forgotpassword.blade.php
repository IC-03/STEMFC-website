<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Forgot Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <link rel="stylesheet" href="{{ url('dist/css/vendors.min.css') }}">
  <link rel="stylesheet" href="{{ url('dist/css/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ url('dist/css/colors.css') }}">
  <link rel="stylesheet" href="{{ url('dist/css/components.css') }}">
  <link rel="stylesheet" href="{{ url('dist/css/login-register.css') }}">
  <style>.branding { height: 5em; }</style>
</head>
<body class="vertical-layout vertical-menu-modern 1-column bg-full-screen-image blank-page">

  <div class="app-content content">
    <div class="content-wrapper">
      <section class="row flexbox-container">
        <div class="col-12 d-flex align-items-center justify-content-center">
          <div class="col-lg-4 col-md-8 col-10 box-shadow-2 p-0">
            <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
              <div class="card-header border-0">
                <div class="card-title text-center">
                  <img src="{{ url('dist/img/AdminLTELogo.png') }}" class="branding" alt="branding logo">
                  <span class="display-5 font-weight-bold">STEM Foundation</span>
                </div>
              </div>
              <div class="card-content">
                <p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-1">
                  <span style="font-weight:bold;">Forgot Password</span>
                </p>
                <div class="card-body">
                            

                 @include('_message')


                  <form action="{{ route('auth.recoverpassword') }}" method="POST" novalidate>
                    @csrf

                    <fieldset class="form-group position-relative has-icon-left">
                    <input type="text" name="id_no" class="form-control" placeholder="ID Number" value="{{ old('id_no') }}" required>
                    <div class="form-control-position"><i class="la la-id-card"></i></div>
                    </fieldset>

                    <fieldset class="form-group position-relative has-icon-left">
                    <input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ old('first_name') }}" required>
                    <div class="form-control-position"><i class="la la-user"></i></div>
                    </fieldset>

                    <fieldset class="form-group position-relative has-icon-left">
                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ old('last_name') }}" required>
                    <div class="form-control-position"><i class="la la-user"></i></div>
                    </fieldset>

                    <label style="font-weight: bold;">Password must be at least 8 characters</label>
                    <fieldset class="form-group position-relative has-icon-left">
                      
                      <input
                        type="password"
                        id="user-password"
                        name="password"
                        class="form-control"
                        placeholder="New Password"
                        required>
                      <div class="form-control-position"><i class="la la-lock"></i></div>
                      <div style="position:absolute; top:35%; right:15px; cursor:pointer;">
                        <i id="toggle-password" class="la la-eye"></i>
                      </div>
                    </fieldset>

                    <fieldset class="form-group position-relative has-icon-left">
                      <input
                        type="password"
                        id="user-password1"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Confirm Password"
                        required>
                      <div class="form-control-position"><i class="la la-lock"></i></div>
                      <div style="position:absolute; top:35%; right:15px; cursor:pointer;">
                        <i id="toggle-password1" class="la la-eye"></i>
                      </div>
                    </fieldset>

                    <button type="submit" class="btn btn-outline-info btn-block mb-2">
                      <i class="ft-refresh-cw"></i> Update Password
                    </button>

                    <div class="text-center">
                      Already a member? <a href="{{ route('login') }}" class="card-link">Login</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    // Toggle password visibility
    [
      { btnId: 'toggle-password',  fldId: 'user-password'  },
      { btnId: 'toggle-password1', fldId: 'user-password1' }
    ].forEach(({ btnId, fldId }) => {
      const btn = document.getElementById(btnId), fld = document.getElementById(fldId);
      if (!btn || !fld) return;
      btn.addEventListener('click', () => {
        fld.type = fld.type === 'password' ? 'text' : 'password';
        btn.classList.toggle('la-eye');
        btn.classList.toggle('la-eye-slash');
      });
    });
  });
  </script>

  <!-- JS includes -->
  <script src="{{ url('dist/js/vendors.min.js') }}"></script>
  <script src="{{ url('dist/js/app-menu.js') }}"></script>
  <script src="{{ url('dist/js/app.js') }}"></script>
</body>
</html>
