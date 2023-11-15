<!--
=========================================================
* Soft UI Dashboard - v1.0.3
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
@php
    $base_url = url('/');
@endphp
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{$base_url}}/assets/img/favicon.ico">
  <title>
    Field Forge
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="{{$base_url}}/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="{{$base_url}}/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="{{$base_url}}/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link rel="stylesheet" href="{{$base_url}}/assets/js/plugins/select2/css/select2.min.css">
  <link id="pagestyle" href="{{$base_url}}/assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
  <link rel="stylesheet" href="{{$base_url}}/assets/js/plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  @stack('stylesheets')
  <link rel="stylesheet" href="{{$base_url}}/css/app.css"/>
</head>

<body class="g-sidenav-show  bg-gray-100">
  @auth
    @yield('auth')
  @endauth
  @guest
    @yield('guest')
  @endguest
  
  <!--   Core JS Files   -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="{{$base_url}}/js/requesthandler.js"></script>
  <script src="{{$base_url}}/assets/js/core/popper.min.js"></script>
  <script src="{{$base_url}}/assets/js/core/bootstrap.min.js"></script>
  <script src="{{$base_url}}/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="{{$base_url}}/assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="{{$base_url}}/assets/js/plugins/fullcalendar.min.js"></script>
  <script src="{{$base_url}}/assets/js/plugins/chartjs.min.js"></script>
  <script src="{{$base_url}}/assets/js/plugins/toastr/toastr.min.js"></script>
  <script src="{{$base_url}}/assets/js/plugins/select2/js/select2.min.js"></script>
  <script src="{{$base_url}}/assets/js/plugins/select2/js/i18n/tr.js"></script>
  <script src="{{$base_url}}/assets/js/plugins/jquery.nestable.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{$base_url}}/assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>
  <script src="{{$base_url}}/assets/js/main.js"></script>
  @stack('scripts')

  @if(session('success'))
  <script>
        toastr.success("{{session('success')}}");
  </script>
  @endif

  @if(session('error'))
  <script>
        toastr.warning("{{session('error')}}");
  </script>
  @endif
</body>

</html>