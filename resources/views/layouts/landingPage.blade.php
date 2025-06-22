<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
    <!-- favicons Icons -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('wostin/files/assets/images/favicons/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('wostin/files/assets/images/favicons/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('wostin/files/assets/images/favicons/favicon-16x16.png') }}" />
    <link rel="manifest" href="{{ asset('wostin/files/assets/images/favicons/site.webmanifest') }}" />
    <meta name="description" content="@yield('description')" />

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap"
        rel="stylesheet" />

    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/animate/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/animate/custom-animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/jarallax/jarallax.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('wostin/files/assets/vendors/jquery-magnific-popup/jquery.magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/nouislider/nouislider.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/nouislider/nouislider.pips.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/odometer/odometer.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/swiper/swiper.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/wostin-icons/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/tiny-slider/tiny-slider.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/reey-font/stylesheet.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/owl-carousel/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/owl-carousel/owl.theme.default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/bxslider/jquery.bxslider.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('wostin/files/assets/vendors/bootstrap-select/css/bootstrap-select.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/vegas/vegas.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/jquery-ui/jquery-ui.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/vendors/timepicker/timePicker.css') }}" />

    <!-- template styles -->
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/css/wostin.css') }}" />
    <link rel="stylesheet" href="{{ asset('wostin/files/assets/css/wostin-responsive.css') }}" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
    @yield('styles')
</head>

<body>
    <!-- <div class="preloader">
        <img class="preloader__image" width="60" src="{{ asset('wostin/files/assets/images/loader.png') }}" alt="" />
    </div> -->
    <!-- /.preloader -->
    <div class="page-wrapper">
        <header class="main-header clearfix">
            <div class="main-header__inner clearfix">
             
             
                    <div class="main-menu__menu-bottom">
                        <nav class="navbar navbar-expand-lg navbar-light w-100" style="background-color: transparent !important;">
                            <div class="container-fluid px-0">
                                <!-- Logo kiri -->
                                <a class="navbar-brand d-flex align-items-center" href="#">
                                    <img src="{{ asset('wostin/files/assets/images/resources/logo-1.png') }}" width="155" alt="" />
                                </a>
                                <!-- Toggle untuk mobile di kanan -->
                                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <!-- Menu dan tombol login -->
                                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                                    <div class="mx-auto order-0">
                                        <div class="navbar-nav d-flex flex-row gap-0 justify-content-center flex-lg-row flex-column align-items-center w-100">
                                            <a class="nav-item nav-link px-3 py-2 flex-fill text-center" href="#">Beranda</a>
                                            <span class="d-none d-lg-block" style="height: 24px; border-left: 1px solid #ccc;"></span>
                                            <a class="nav-item nav-link px-3 py-2 flex-fill text-center" href="{{route('about')}}">Profil Perusahaan</a>
                                            <span class="d-none d-lg-block" style="height: 24px; border-left: 1px solid #ccc;"></span>
                                            <a class="nav-item nav-link px-3 py-2 flex-fill text-center" href="{{route('kegiatan')}}">Kegiatan</a>
                                            <span class="d-none d-lg-block" style="height: 24px; border-left: 1px solid #ccc;"></span>
                                            <a class="nav-item nav-link px-3 py-2 flex-fill text-center" href="{{route('berita')}}">Berita</a>
                                        </div>
                                        <style>
                                            @media (max-width: 991.98px) {
                                                .navbar-nav {
                                                    width: 100% !important;
                                                }
                                                .navbar-nav .nav-link {
                                                    border-bottom: 1px solid #ccc;
                                                    border-left: none !important;
                                                    width: 100%;
                                                    text-align: left;
                                                }
                                                .navbar-nav span {
                                                    display: none !important;
                                                }
                                                .navbar-nav .nav-link:last-child {
                                                    border-bottom: none;
                                                }
                                            }
                                        </style>
                                    </div>
                                    <!-- Tombol login di bawah menu saat mobile, di kanan saat desktop -->
                                    <div class="d-flex justify-content-lg-end justify-content-start w-100 mt-3 mt-lg-0">
                                        <a href="{{ route('login') }}" class="btn btn-success px-4">Login</a>
                                    </div>
                                </div>
                            </div>
                        </nav>
                        <style>
                            @media (max-width: 991.98px) {
                                .navbar-nav {
                                    flex-direction: column !important;
                                    gap: 0.5rem !important;
                                    align-items: flex-start !important;
                                }
                                .navbar .btn-success {
                                    width: 100% !important;
                                }
                            }
                        </style>
                    </div>
                </div>
                {{-- <div class="main-header__right">
                        <a href="{{ route('login') }}" class="btn btn-success"> Login</a>
                    </div> --}}
            </div>
        </header>

        <div class="stricky-header stricked-menu main-menu">
            <div class="sticky-header__content"></div>
            <!-- /.sticky-header__content -->
        </div>
        <!-- /.stricky-header -->
        @yield('content')




        <!--Site Footer Start-->
        <footer class="site-footer">
            <div class="site-footer-bg"
                style="
                        background-image: url(assets/images/backgrounds/site-footer-bg.jpg);
                    ">
            </div>
            <div class="site-footer__top">
                <div class="container">
                    <div class="site-footer__top-inner">
                        <div class="site-footer__top-logo">
                            <a href="index.html"><img
                                    src="{{ asset('wostin/files/assets/images/resources/footer-logo.png') }}"
                                    alt="" /></a>
                        </div>
                        <div class="site-footer__top-right">
                            <p class="site-footer__top-right-text">
                                Waste Disposal Management & Pickup Services
                            </p>
                            <div class="site-footer__social">
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-facebook"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                                <a href="#"><i class="fab fa-pinterest-p"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="site-footer__middle">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                            <div class="footer-widget__column footer-widget__about">
                                <h3 class="footer-widget__title">About</h3>
                                <div class="footer-widget__about-text-box">
                                    <p class="footer-widget__about-text">
                                        Lorem ipsum dolor sited ame etur adi
                                        pisicing elit tempor labore.
                                    </p>
                                </div>
                                <form class="footer-widget__newsletter-form">
                                    <div class="footer-widget__newsletter-input-box">
                                        <input type="email" placeholder="Email Address" name="email" />
                                        <button type="submit" class="footer-widget__newsletter-btn">
                                            <i class="far fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="200ms">
                            <div class="footer-widget__column footer-widget__links clearfix">
                                <h3 class="footer-widget__title">Links</h3>
                                <ul class="footer-widget__links-list list-unstyled clearfix">
                                    <li><a href="about.html">About</a></li>
                                    <li>
                                        <a href="request-pickup.html">Request Pickup</a>
                                    </li>
                                    <li>
                                        <a href="about.html">Management</a>
                                    </li>
                                    <li>
                                        <a href="services.html">Start Service</a>
                                    </li>
                                    <li>
                                        <a href="contact.html">Contact</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="300ms">
                            <div class="footer-widget__column footer-widget__services clearfix">
                                <h3 class="footer-widget__title">
                                    Services
                                </h3>
                                <ul class="footer-widget__services-list list-unstyled clearfix">
                                    <li>
                                        <a href="dumpster-rental.html">Dumpster Rentals</a>
                                    </li>
                                    <li>
                                        <a href="about.html">Bulk Trash Pickup</a>
                                    </li>
                                    <li>
                                        <a href="about.html">Waste Removal</a>
                                    </li>
                                    <li>
                                        <a href="zero-waste.html">Zero Waste</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="400ms">
                            <div class="footer-widget__column footer-widget__contact clearfix">
                                <h3 class="footer-widget__title">
                                    Contact
                                </h3>
                                <p class="footer-widget__contact-text">
                                    880 Broklyn Road Street, New Town DC
                                    5002, New York. USA
                                </p>
                                <div class="footer-widget__contact-info">
                                    <div class="footer-widget__contact-icon">
                                        <span class="icon-contact"></span>
                                    </div>
                                    <div class="footer-widget__contact-content">
                                        <p class="footer-widget__contact-mail-phone">
                                            <a href="mailto:needhelp@wostin.com"
                                                class="footer-widget__contact-mail">needhelp@wostin.com</a>
                                            <a href="tel:2463330088" class="footer-widget__contact-phone">+ 1- (246)
                                                333-0088</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="site-footer__bottom">
                <div class="site-footer-bottom-shape"
                    style="
                            background-image: url(assets/images/shapes/site-footer-bottom-shape.png);
                        ">
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="site-footer__bottom-inner">
                                <p class="site-footer__bottom-text">
                                    © Copyright 2022 by
                                    <a href="#">Layerdrops.com</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!--Site Footer End-->
    </div>
    <!-- /.page-wrapper -->

    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <!-- /.mobile-nav__overlay -->
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>

            <div class="logo-box">
                <a href="index.html" aria-label="logo image"><img
                        src="{{ asset('wostin/files/assets/images/resources/footer-logo.png') }}" width="155"
                        alt="" /></a>
            </div>
            <!-- /.logo-box -->
            <div class="mobile-nav__container"></div>
            <!-- /.mobile-nav__container -->

            <ul class="mobile-nav__contact list-unstyled">
                <li>
                    <i class="fa fa-envelope"></i>
                    <a href="mailto:needhelp@packageName__.com">needhelp@wostin.com</a>
                </li>
                <li>
                    <i class="fa fa-phone-alt"></i>
                    <a href="tel:666-888-0000">666 888 0000</a>
                </li>
            </ul>
            <!-- /.mobile-nav__contact -->
            <div class="mobile-nav__top">
                <div class="mobile-nav__social">
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-facebook-square"></a>
                    <a href="#" class="fab fa-pinterest-p"></a>
                    <a href="#" class="fab fa-instagram"></a>
                </div>
                <!-- /.mobile-nav__social -->
            </div>
            <!-- /.mobile-nav__top -->
        </div>
        <!-- /.mobile-nav__content -->
    </div>
    <!-- /.mobile-nav__wrapper -->

    <div class="search-popup">
        <div class="search-popup__overlay search-toggler"></div>
        <!-- /.search-popup__overlay -->
        <div class="search-popup__content">
            <form action="#">
                <label for="search" class="sr-only">search here</label><!-- /.sr-only -->
                <input type="text" id="search" placeholder="Search Here..." />
                <button type="submit" aria-label="search submit" class="thm-btn">
                    <i class="icon-magnifying-glass"></i>
                </button>
            </form>
        </div>
        <!-- /.search-popup__content -->
    </div>
    <!-- /.search-popup -->

    <a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fa fa-angle-up"></i></a>

    <script src="{{ asset('wostin/files/assets/vendors/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/jarallax/jarallax.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/jquery-ajaxchimp/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/jquery-appear/jquery.appear.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/jquery-circle-progress/jquery.circle-progress.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/jquery-magnific-popup/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/jquery-validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/nouislider/nouislider.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/odometer/odometer.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/swiper/swiper.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/tiny-slider/tiny-slider.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/wnumb/wNumb.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/wow/wow.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/isotope/isotope.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/countdown/countdown.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/owl-carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/bxslider/jquery.bxslider.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/vegas/vegas.min.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('wostin/files/assets/vendors/timepicker/timePicker.js') }}"></script>

    <!-- template js -->
    <script src="{{ asset('wostin/files/assets/js/wostin.js') }}"></script>
    @yield('scripts')
</body>

</html>
