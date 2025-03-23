<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="{{ asset('assets/images/logo.svg') }}" type="image/x-icon">
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Able Pro is trending dashboard template made using Bootstrap 5 design framework. Able Pro is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies.">
    <meta name="keywords"
        content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard">
    <meta name="author" content="Phoenixcoded">

    <!-- [Page specific CSS] start -->
    <link href="{{ asset('assets/css/plugins/animate.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css') }}"
        rel="stylesheet" />
    <!-- [Page specific CSS] end -->
    <!-- [Font] Family -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}" id="main-font-link" />

    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}" />
</head>

<body class="landing-page">
    <!-- [ Main Content ] start -->
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Header ] start -->
    <header id="home" style="background-image: url({{ asset('assets/images/landing/img-headerbg.jpg') }})">
        <!-- [ Nav ] start -->
        <!-- [ Nav ] start -->
        <nav class="navbar navbar-expand-md navbar-light default">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo" style="width: 100px;"
                        class="" />
                </a>
                <button class="navbar-toggler rounded" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-start">
                        @auth
                            <li class="nav-item">
                                <a class="btn btn btn-success" href="{{ route('dashboard') }}">Dashboard <i
                                        class="ti ti-external-link"></i></a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="btn btn btn-success" href="{{ route('login') }}">Halaman Masuk <i
                                        class="ti ti-external-link"></i></a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
        <!-- [ Nav ] start -->

        <!-- [ Nav ] start -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 text-center">
                    <h1 class="mb-4 wow fadeInUp" data-wow-delay="0.2s">Sistem Pembayaran Sumbangan Pembinaan Pendidikan
                        <span class="text-primary">(SPP)</span>
                    </h1>
                    <div class="row justify-content-center wow fadeInUp" data-wow-delay="0.3s">
                        <div class="col-md-8">
                            <h2 class="text-muted mb-0">SD IT AR-RAHMAN</h2>
                        </div>
                    </div>
                    {{-- <div class="my-4 my-sm-5 wow fadeInUp" data-wow-delay="0.4s">
                        <a href="../elements/bc_alert.html" class="btn btn-outline-secondary me-2">Explore
                            Components</a>
                        <a href="../dashboard/index.html" class="btn btn-primary">Live Preview</a>
                    </div> --}}
                </div>
            </div>
        </div>
    </header>
    <!-- [ Header ] End -->

    <!-- [ footer apps ] start -->
    {{-- <footer class="footer" style="background-image: url({{ asset('assets/images/landing/img-headerbg.jpg') }})">
        <div class="container">
            <div class="row align-items-center">
                <div class="col my-1 wow fadeInUp" data-wow-delay="0.4s">
                    <p class="mb-0">Hak Cipta © 2025. <a href="#" target="_blank">SD IT AR-RAHMAN.</a></p>
                </div>
                {{-- <div class="col-auto my-1">
                    <ul class="list-inline footer-sos-link mb-0">
                        <li class="list-inline-item wow fadeInUp" data-wow-delay="0.4s"><a href="#">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-instagram"></use>
                                </svg> </a></li>
                    </ul>
                </div> --}}
    </div>
    </div>
    </footer> --}}
    <!-- [ footer apps ] End -->

    <!-- [ Main Content ] end -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js') }}"></script>
    <!-- Required Js -->
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>



</body>

</html>
