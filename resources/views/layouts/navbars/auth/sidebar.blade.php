<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ url('/') }}">
      <img src="{{url('/')}}/assets/img/Logo.png" class="navbar-brand-img h-100" alt="...">
      <span class="ms-3 font-weight-bold">Field Forge</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      @if (Auth::user()->role == "admin")
        @include('layouts.navbars.auth.admin.nav-links')
      @else
        @include('layouts.navbars.auth.employee.nav-links')
      @endif
    </ul>
  </div>
</aside>