<div class="clear-both"></div>

@if(!empty(session('success')))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-check"></i> Alert!</h5>
    {{ session('success') }}
</div>
@endif


@if(!empty(session('error')))
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h3><i class="icon fas fa-check"></i> Alert!</h3>
    <span class="text-white">{{ session('error') }}</span>
</div>
@endif

@if(!empty(session('payment error'))) <!-- Ensure this key matches what you set in your controller -->
<div class="alert alert-danger alert-dismissible" role="alert"> <!-- Changed alert-error to alert-danger -->
    {{ session('payment error') }}
</div>
@endif

@if(!empty(session('warning')))
<div class="alert alert-warning alert-dismissible" role="alert">
    {{ session('warning') }}
</div>
@endif

@if(!empty(session('info')))
<div class="alert alert-info alert-dismissible fade in" role="alert">
    {{ session('info') }} <!-- Corrected from 'into' to 'info' -->
</div>
@endif

@if(!empty(session('secondary')))
<div class="alert alert-secondary alert-dismissible fade in" role="alert">
    {{ session('secondary') }}
</div>
@endif

@if(!empty(session('primary')))
<div class="alert alert-primary alert-dismissible fade in" role="alert">
    {{ session('primary') }}
</div>
@endif

@if(!empty(session('light')))
<div class="alert alert-light alert-dismissible fade in" role="alert">
    {{ session('light') }}
</div>
@endif
