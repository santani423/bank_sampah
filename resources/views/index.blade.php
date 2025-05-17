<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>IT Kashit - Technology & IT Solutions HTML Template | Homepage 02</title>

<!-- Stylesheets -->
<link href="{{ asset('kashIT/css/bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('kashIT/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('kashIT/css/responsive.css') }}" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

<!-- Color Switcher Mockup -->
<link href="{{ asset('kashIT/css/color-switcher-design.css') }}" rel="stylesheet">
<link id="theme-color-file" href="{{ asset('kashIT/css/color-themes/default-color.css') }}" rel="stylesheet">

<link rel="shortcut icon" href="{{ asset('kashIT/images/favicon.png') }}" type="image/x-icon">
<link rel="icon" href="{{ asset('kashIT/images/favicon.png') }}" type="image/x-icon">

<!-- Responsive -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
</head>

<body>
<div class="page-wrapper">

    <!-- Preloader -->
    <div class="preloader"></div>

    <!-- Main Header -->
    <header class="main-header header-style-two">

        <!-- Header Top -->
        <div class="header-top">
            <div class="auto-container">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="header-top_nav">
                        <a href="#">Terms & Condition</a>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Contact Us</a>
                    </div>
                    <div class="right-box d-flex">
                        <a class="header-top_email" href="mailto:info@example.com"><span>Email us:</span> info@example.com</a>
                        <div class="header-top_social">
                            <a href="#">fb.</a>
                            <a href="#">ln.</a>
                            <a href="#">tw.</a>
                            <a href="#">in.</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header Lower -->
        <div class="header-lower">
            <div class="auto-container">
                <div class="inner-container d-flex justify-content-between align-items-center">

                    <!-- Logo Box -->
                    <div class="logo-box d-flex">
                        <div class="logo"><a href="/"><img src="{{ asset('kashIT/images/logo.svg') }}" alt="" title=""></a></div>
                    </div>

                    <!-- Nav Outer -->
                    <div class="nav-outer d-flex align-items-center">

                        <!-- Main Menu -->
                        <nav class="main-menu show navbar-expand-md">
                            <div class="navbar-header">
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>

                            <div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
                                <ul class="navigation clearfix">
                                    <li><a href="/">Home</a></li>
                                    <li><a href="{{ route('login') }}">Login</a></li>
                                </ul>
                            </div>
                        </nav>

                        <!-- Mobile Navigation Toggler -->
                        <div class="mobile-nav-toggler"><span class="icon"><img src="{{ asset('kashIT/images/icons/menu.png') }}" alt="" /></span></div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Sticky Header -->
        <div class="sticky-header">
            <div class="auto-container">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="logo">
                        <a href="/" title=""><img src="{{ asset('kashIT/images/logo.svg') }}" alt="" title=""></a>
                    </div>
                    <div class="right-box">
                        <nav class="main-menu"></nav>
                        <div class="mobile-nav-toggler"><span class="icon"><img src="{{ asset('kashIT/images/icons/menu.png') }}" alt="" /></span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu">
            <div class="menu-backdrop"></div>
            <div class="close-btn"><span class="icon flaticon-cancel-1"></span></div>
            <nav class="menu-box">
                <div class="nav-logo"><a href="/"><img src="{{ asset('kashIT/images/logo.svg') }}" alt="" title=""></a></div>

                <!-- Search -->
                <div class="search-box">
                    <form method="post" action="contact.html">
                        <div class="form-group">
                            <input type="search" name="search-field" value="" placeholder="SEARCH HERE" required>
                            <button type="submit"><span class="icon flaticon-search"></span></button>
                        </div>
                    </form>
                </div>

                <!-- Mobile Menu Links -->
                <ul class="navigation clearfix"><!-- duplicated for mobile -->
                    <li><a href="/">Home</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                </ul>
            </nav>
        </div>
        <!-- End Mobile Menu -->

    </header>
    <!-- End Main Header -->

    <!-- Slider Two -->
    <section class="slider-two">
        <span class="slider-two_circle-one"></span>
        <span class="slider-two_circle-two"></span>
        <span class="slider-two_circle-three"></span>
        <div class="single-item-carousel owl-carousel owl-theme">
            <!-- Slide -->
            <div class="slide">
                <div class="slider-two_image-layer" style="background-image: url('{{ asset('kashIT/images/main-slider/1.jpg') }}');"></div>
                <div class="auto-container">
                    <div class="slider-two_title-column">
                        <div class="slider-two_title-inner">
                            <a href="https://www.youtube.com/watch?v=kxPCFljwJws" class="lightbox-video slider-two_play"><span class="fa fa-play"><i class="ripple"></i></span></a>
                            <h1 class="slider-two_heading">Bank Sampah</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Slider -->

</div>

<!-- Scroll To Top -->
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fas fa-arrow-up fa-fw"></span></div>

<!-- JS Scripts -->
<script src="{{ asset('kashIT/js/jquery.js') }}"></script>
<script src="{{ asset('kashIT/js/popper.min.js') }}"></script>
<script src="{{ asset('kashIT/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('kashIT/js/magnific-popup.min.js') }}"></script>
<script src="{{ asset('kashIT/js/appear.js') }}"></script>
<script src="{{ asset('kashIT/js/parallax.min.js') }}"></script>
<script src="{{ asset('kashIT/js/tilt.jquery.min.js') }}"></script>
<script src="{{ asset('kashIT/js/owl.js') }}"></script>
<script src="{{ asset('kashIT/js/wow.js') }}"></script>
<script src="{{ asset('kashIT/js/parallax-scroll.js') }}"></script>
<script src="{{ asset('kashIT/js/odometer.js') }}"></script>
<script src="{{ asset('kashIT/js/nav-tool.js') }}"></script>
<script src="{{ asset('kashIT/js/jquery-ui.js') }}"></script>
<script src="{{ asset('kashIT/js/color-settings.js') }}"></script>
<script src="{{ asset('kashIT/js/script.js') }}"></script>

<!--[if lt IE 9]>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
<script src="{{ asset('kashIT/js/respond.js') }}"></script>
<![endif]-->

</body>
</html>
