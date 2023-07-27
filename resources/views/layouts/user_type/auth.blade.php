@extends('layouts.app')

@section('auth')
    @include('layouts.navbars.auth.sidebar')
    <main class="main-content position-relative">
        @include('layouts.navbars.auth.nav')
        <div class="container-fluid py-4">
            @yield('content')
        </div>
    </main>
@endsection