@extends('layouts.landingPage')

@section('content')
    <!--Main Slider Start-->
    <section class="main-slider main-slider-two">
        <div class="main-slider-ripped-paper"
            style="background-image: url({{ asset('wostin/files/assets/images/shapes/ripped-paper.png') }});">
        </div>
        <div class="swiper-container thm-swiper__slider"
            data-swiper-options='{"slidesPerView": 1, "loop": true,
                "effect": "fade",
                "pagination": {
                "el": "#main-slider-pagination",
                "type": "bullets",
                "clickable": true
                },
                "navigation": {
                "nextEl": "#main-slider__swiper-button-next",
                "prevEl": "#main-slider__swiper-button-prev"
                },
                "autoplay": {
                "delay": 5000
                }}'>
            <div class="swiper-wrapper">

                <div class="swiper-slide">
                    <div class="image-layer"
                        style="background-image: url({{ asset('wostin/files/assets/images/backgrounds/main-slider-2-1.jpg') }});">
                    </div>
                    <!-- /.image-layer -->
                    <div class="main-slider-two__big-text">wostin</div>
                    <div class="container">
                        <div class="main-slider-two__content-box">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="main-slider__content">
                                        <h2>Home & Business <br> Waste Pickup <br> Solution</h2>
                                        <a href="request-pickup.html" class="thm-btn">Request a Pickup</a>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="main-slider-two__video-box">
                                        <div class="main-slider-two__video-link">
                                            <a href="https://www.youtube.com/watch?v=Get7rqXYrbQ" class="video-popup">
                                                <div class="main-slider-two__video-icon">
                                                    <span class="fa fa-play"></span>
                                                    <i class="ripple"></i>
                                                </div>
                                            </a>
                                        </div>
                                        <p class="main-slider-two__video-text">Watch <br> How it Works</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="image-layer"
                        style="background-image: url({{ asset('wostin/files/assets/images/backgrounds/main-slider-2-2.jpg') }});">
                    </div>
                    <!-- /.image-layer -->
                    <div class="main-slider-two__big-text">wostin</div>
                    <div class="container">
                        <div class="main-slider-two__content-box">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="main-slider__content">
                                        <h2>Home & Business <br> Waste Pickup <br> Solution</h2>
                                        <a href="request-pickup.html" class="thm-btn">Request a Pickup</a>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="main-slider-two__video-box">
                                        <div class="main-slider-two__video-link">
                                            <a href="https://www.youtube.com/watch?v=Get7rqXYrbQ" class="video-popup">
                                                <div class="main-slider-two__video-icon">
                                                    <span class="fa fa-play"></span>
                                                    <i class="ripple"></i>
                                                </div>
                                            </a>
                                        </div>
                                        <p class="main-slider-two__video-text">Watch <br> How it Works</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="image-layer"
                        style="background-image: url({{ asset('wostin/files/assets/images/backgrounds/main-slider-2-3.jpg') }});">
                    </div>
                    <!-- /.image-layer -->
                    <div class="main-slider-two__big-text">wostin</div>
                    <div class="container">
                        <div class="main-slider-two__content-box">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="main-slider__content">
                                        <h2>Home & Business <br> Waste Pickup <br> Solution</h2>
                                        <a href="request-pickup.html" class="thm-btn">Request a Pickup</a>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="main-slider-two__video-box">
                                        <div class="main-slider-two__video-link">
                                            <a href="https://www.youtube.com/watch?v=Get7rqXYrbQ" class="video-popup">
                                                <div class="main-slider-two__video-icon">
                                                    <span class="fa fa-play"></span>
                                                    <i class="ripple"></i>
                                                </div>
                                            </a>
                                        </div>
                                        <p class="main-slider-two__video-text">Watch <br> How it Works</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- If we need navigation buttons -->
            <div class="main-slider__nav-two">
                <div class="swiper-button-prev" id="main-slider__swiper-button-next">
                    <i class="icon-right-arrow arrow-left"></i>
                </div>
                <div class="swiper-button-next" id="main-slider__swiper-button-prev">
                    <i class="icon-right-arrow"></i>
                </div>
            </div>

        </div>
    </section>
    <!--Main Slider End-->

    
        <!--Introduction Start-->
        <section class="introduction">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="introducton__left">
                            <div class="section-title text-left">
                                <span class="section-title__tagline">Our Company Introduction</span>
                                <h2 class="section-title__title">Why you Should Choose Wostin for Waste</h2>
                            </div>
                            <p class="introducton__text">Duis aute irure dolor in reprehenderit in voluptate velit esse
                                cillum dolore eu convenient scheduling, account fugiat nulla pariatur.</p>
                            <ul class="list-unstyled introduction__bottom">
                                <li>
                                    <div class="introduction__bottom-icon">
                                        <img src="{{ asset('wostin/files/assets/images/icon/introduction-bottom-icon-1.png') }}"
                                            alt="">
                                    </div>
                                    <h3 class="introduction__bottom-title">We Deliver</h3>
                                    <p class="introduction__bottom-text">Nullam mollis elit quis dus is lacinia not
                                        efficitur.</p>
                                </li>
                                <li>
                                    <div class="introduction__bottom-icon">
                                        <img src="{{ asset('wostin/files/assets/images/icon/introduction-bottom-icon-2.png') }}"
                                            alt="">
                                    </div>
                                    <h3 class="introduction__bottom-title">No Surprises</h3>
                                    <p class="introduction__bottom-text">Nullam mollis elit quis dus is lacinia not
                                        efficitur.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="introducton__right">
                            <div class="introducton__img-box wow slideInRight" data-wow-delay="100ms"
                                data-wow-duration="2500ms">
                                <div class="introducton__img-1">
                                    <img src="{{ asset('wostin/files/assets/images/resources/introducton-img-1.jpg') }}"
                                        alt="">
                                    <div class="introducton__trusted">
                                        <p class="introducton__trusted-text">Trusted by</p>
                                        <h3 class="odometer" data-count="4890">00</h3>
                                    </div>
                                </div>
                                <div class="introducton__img-2">
                                    <img src="{{ asset('wostin/files/assets/images/resources/introducton-img-2.jpg') }}"
                                        alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Introduction End-->

        <!--Waste Materials Start-->
        <section class="waste-materials">
            <div class="container">
                <div class="waste-materials__inner">
                    <div class="waste-materials-bg"
                        style="background-image: url({{ asset('wostin/files/assets/images/backgrounds/waste-materials-bg.jpg') }});">
                    </div>
                    <div class="waste-materials-shape-1 float-bob-y"><img
                            src="{{ asset('wostin/files/assets/images/shapes/waste-materials-shape-1.png') }}"
                            alt=""></div>
                    <div class="waste-materials__title-box">
                        <h2 class="waste-materials__title">Waste Materials We <br> Collect. Recycle & Dispose</h2>
                    </div>
                    <div class="waste-materials__points">
                        <ul class="list-unstyled waste-materials__point-1">
                            <li>
                                <div class="icon">
                                    <span class="fa fa-check"></span>
                                </div>
                                <div class="text">
                                    <p>Steel</p>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <span class="fa fa-check"></span>
                                </div>
                                <div class="text">
                                    <p>Plastic</p>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <span class="fa fa-check"></span>
                                </div>
                                <div class="text">
                                    <p>Lights & Bulbs</p>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <span class="fa fa-check"></span>
                                </div>
                                <div class="text">
                                    <p>Large Trash</p>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <span class="fa fa-check"></span>
                                </div>
                                <div class="text">
                                    <p>Books & Papers</p>
                                </div>
                            </li>
                        </ul>
                        <ul class="list-unstyled waste-materials__point-1 waste-materials__point-2">
                            <li>
                                <div class="icon">
                                    <span class="fa fa-check"></span>
                                </div>
                                <div class="text">
                                    <p>Common Waste</p>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <span class="fa fa-check"></span>
                                </div>
                                <div class="text">
                                    <p>Aluminium</p>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <span class="fa fa-check"></span>
                                </div>
                                <div class="text">
                                    <p>Bottles</p>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <span class="fa fa-check"></span>
                                </div>
                                <div class="text">
                                    <p>Food & Grocery</p>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <span class="fa fa-check"></span>
                                </div>
                                <div class="text">
                                    <p>Electronics & Glass</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!--Waste Materials End-->

        <!--Manage Waste Start-->
        <section class="manage-waste">
            <div class="manage-waste-bg-box">
                <div class="manage-waste-bg jarallax" data-jarallax data-speed="0.2" data-imgPosition="50% 0%"
                    style="background-image: url({{ asset('wostin/files/assets/images/backgrounds/manage-waste-bg.jpg') }});">
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="manage-waste__inner">
                            <h3 class="manage-waste__title">Manage Waste Effectively <br> and Reduce Environmental
                                Impact</h3>
                            <div class="manage-waste__btn-box">
                                <a href="request-pickup.html" class="thm-btn manage-waste__btn-1">Request a Pickup</a>
                                <a href="contact.html" class="thm-btn manage-waste__btn-2">Contact With us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Manage Waste End-->

        <!--Industries Two Start-->
        <section class="industries-one industries-two">
            <div class="industries-one-bg"
                style="background-image: url({{ asset('wostin/files/assets/images/backgrounds/industries-one-bg.jpg') }});">
            </div>
            <div class="industries-two__top">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-7 col-lg-7">
                            <div class="industries-two__top-left">
                                <div class="section-title text-left">
                                    <span class="section-title__tagline">We Cover Industries</span>
                                    <h2 class="section-title__title">Industries We Served</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-5">
                            <div class="industries-two__top-right">
                                <p class="industries-two__top-text">Lorem ipsum dolor sit amet, consectetur adipisicing
                                    elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. quis nostrud
                                    exercitation ullamco laboris.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay="100ms">
                        <!--Industries One Single-->
                        <div class="industries-one__single">
                            <div class="industries-one__img">
                                <img src="{{ asset('wostin/files/assets/images/resources/industries-1-1.jpg') }}"
                                    alt="">
                            </div>
                            <div class="industries-one__content">
                                <div class="industries-one__icon">
                                    <span class="icon-shop"></span>
                                </div>
                                <h3 class="industries-one__title"><a href="industry-details.html">Hotel &
                                        Restaurant</a>
                                </h3>
                                <p class="industries-one__text">Sed quia magni dolores eos ratione voluptatem sequi
                                    site, qui nesciunt eque sit porro.</p>
                                <div class="industries-one__arrow">
                                    <a href="industry-details.html">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay="200ms">
                        <!--Industries One Single-->
                        <div class="industries-one__single">
                            <div class="industries-one__img">
                                <img src="{{ asset('wostin/files/assets/images/resources/industries-1-2.jpg') }}"
                                    alt="">
                            </div>
                            <div class="industries-one__content">
                                <div class="industries-one__icon">
                                    <span class="icon-stethoscope"></span>
                                </div>
                                <h3 class="industries-one__title"><a href="industry-details.html">Medical &
                                        Hospital</a>
                                </h3>
                                <p class="industries-one__text">Sed quia magni dolores eos ratione voluptatem sequi
                                    site, qui nesciunt eque sit porro.</p>
                                <div class="industries-one__arrow">
                                    <a href="industry-details.html">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay="300ms">
                        <!--Industries One Single-->
                        <div class="industries-one__single">
                            <div class="industries-one__img">
                                <img src="{{ asset('wostin/files/assets/images/resources/industries-1-3.jpg') }}"
                                    alt="">
                            </div>
                            <div class="industries-one__content">
                                <div class="industries-one__icon">
                                    <span class="icon-grocery"></span>
                                </div>
                                <h3 class="industries-one__title"><a href="industry-details.html">Grocery Stores</a>
                                </h3>
                                <p class="industries-one__text">Sed quia magni dolores eos ratione voluptatem sequi
                                    site, qui nesciunt eque sit porro.</p>
                                <div class="industries-one__arrow">
                                    <a href="industry-details.html">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Industries Two End-->

        <!--Why Choose One Start-->
        <section class="why-choose-one">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5">
                        <div class="why-choose-one__left">
                            <div class="why-choose-one__img">
                                <img src="{{ asset('wostin/files/assets/images/resources/why-choose-one-img-1.jpg') }}"
                                    alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7">
                        <div class="why-choose-one__right">
                            <div class="why-choose-one__content">
                                <h3 class="why-choose-one__title">Why Choose Wostin?</h3>
                                <p class="why-choose-one__text">There are many variations of passages of Lorem Ipsum
                                    available, but the majority have suffered alteration in some form, by injected
                                    humour.</p>
                                <ul class="list-unstyled why-choose-one__points">
                                    <li>
                                        <div class="icon">
                                            <span class="fa fa-check"></span>
                                        </div>
                                        <div class="text">
                                            <p>Donec fermentum leo id elit commodo, vel sodales</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="icon">
                                            <span class="fa fa-check"></span>
                                        </div>
                                        <div class="text">
                                            <p>Nullam a consequat diam, id pharetra massa</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="icon">
                                            <span class="fa fa-check"></span>
                                        </div>
                                        <div class="text">
                                            <p>Praesent porttitor enim quis risus gravida</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Why Choose One End-->

        <!--benefit One Start-->
        <section class="benefit-one">
            <div class="benefit-one__container">
                <ul class="list-unstyled benefit-one__content">
                    <!--benefit One Single-->
                    <li class="benefit-one__single wow fadeInUp" data-wow-delay="100ms">
                        <div class="benefit-one__img">
                            <img src="{{ asset('wostin/files/assets/images/resources/benefit-one-img-1.jpg') }}"
                                alt="">
                            <div class="benefit-one__title-box">
                                <h3 class="benefit-one__title">Easy Setup, <br> Exceptional Service</h3>
                            </div>
                            <div class="benefit-one__shape-1">
                                <img src="{{ asset('wostin/files/assets/images/shapes/benefit-one-shape-1.png') }}"
                                    alt="">
                            </div>
                        </div>
                    </li>
                    <!--benefit One Single-->
                    <li class="benefit-one__single wow fadeInUp" data-wow-delay="200ms">
                        <div class="benefit-one__img">
                            <img src="{{ asset('wostin/files/assets/images/resources/benefit-one-img-2.jpg') }}"
                                alt="">
                            <div class="benefit-one__title-box">
                                <h3 class="benefit-one__title">No Annual <br> Contract Required</h3>
                            </div>
                            <div class="benefit-one__shape-1">
                                <img src="{{ asset('wostin/files/assets/images/shapes/benefit-one-shape-1.png') }}"
                                    alt="">
                            </div>
                        </div>
                    </li>
                    <!--benefit One Single-->
                    <li class="benefit-one__single wow fadeInUp" data-wow-delay="300ms">
                        <div class="benefit-one__img">
                            <img src="{{ asset('wostin/files/assets/images/resources/benefit-one-img-3.jpg') }}"
                                alt="">
                            <div class="benefit-one__title-box">
                                <h3 class="benefit-one__title">Trained & Professional <br> Staff Members</h3>
                            </div>
                            <div class="benefit-one__shape-1">
                                <img src="{{ asset('wostin/files/assets/images/shapes/benefit-one-shape-1.png') }}"
                                    alt="">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
        <!--benefit One End-->

        <!--Brand One Start-->
        <section class="brand-one brand-two">
            <div class="container">
                <div class="thm-swiper__slider swiper-container"
                    data-swiper-options='{"spaceBetween": 100, "slidesPerView": 5, "autoplay": { "delay": 5000 }, "breakpoints": {
                    "0": {
                        "spaceBetween": 30,
                        "slidesPerView": 2
                    },
                    "375": {
                        "spaceBetween": 30,
                        "slidesPerView": 2
                    },
                    "575": {
                        "spaceBetween": 30,
                        "slidesPerView": 3
                    },
                    "767": {
                        "spaceBetween": 50,
                        "slidesPerView": 4
                    },
                    "991": {
                        "spaceBetween": 50,
                        "slidesPerView": 5
                    },
                    "1199": {
                        "spaceBetween": 100,
                        "slidesPerView": 5
                    }
                }}'>
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="{{ asset('wostin/files/assets/images/brand/brand-1-1.png') }}" alt="">
                        </div><!-- /.swiper-slide -->
                        <div class="swiper-slide">
                            <img src="{{ asset('wostin/files/assets/images/brand/brand-1-2.png') }}" alt="">
                        </div><!-- /.swiper-slide -->
                        <div class="swiper-slide">
                            <img src="{{ asset('wostin/files/assets/images/brand/brand-1-3.png') }}" alt="">
                        </div><!-- /.swiper-slide -->
                        <div class="swiper-slide">
                            <img src="{{ asset('wostin/files/assets/images/brand/brand-1-4.png') }}" alt="">
                        </div><!-- /.swiper-slide -->
                        <div class="swiper-slide">
                            <img src="{{ asset('wostin/files/assets/images/brand/brand-1-5.png') }}" alt="">
                        </div><!-- /.swiper-slide -->
                        <div class="swiper-slide">
                            <img src="{{ asset('wostin/files/assets/images/brand/brand-1-1.png') }}" alt="">
                        </div><!-- /.swiper-slide -->
                        <div class="swiper-slide">
                            <img src="{{ asset('wostin/files/assets/images/brand/brand-1-2.png') }}" alt="">
                        </div><!-- /.swiper-slide -->
                        <div class="swiper-slide">
                            <img src="{{ asset('wostin/files/assets/images/brand/brand-1-3.png') }}" alt="">
                        </div><!-- /.swiper-slide -->
                        <div class="swiper-slide">
                            <img src="{{ asset('wostin/files/assets/images/brand/brand-1-4.png') }}" alt="">
                        </div><!-- /.swiper-slide -->
                        <div class="swiper-slide">
                            <img src="{{ asset('wostin/files/assets/images/brand/brand-1-5.png') }}" alt="">
                        </div><!-- /.swiper-slide -->
                    </div>
                </div>
            </div>
        </section>
        <!--Brand One End-->

        <!--Testimonial Two Start-->
        <section class="testimonial-two">
            <div class="testimonial-two-bg"
                style="background-image: url({{ asset('wostin/files/assets/images/backgrounds/testimonial-two-bg.jpg') }});">
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5">
                        <div class="testimonial-two__left">
                            <div class="section-title text-left">
                                <span class="section-title__tagline">Our Customers Feedbacks</span>
                                <h2 class="section-title__title">What Our Customers Are Talking ABout Our Services</h2>
                            </div>
                            <p class="testimonial-two__text-1">Proin a lacus arcu. Nullam id dui eu orci maximus. Cras
                                at auctor lectus, pretium tellus.</p>
                            <a href="testimonials.html" class="thm-btn testimonial-two__btn">View All Feedbacks</a>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-7">
                        <div class="testimonial-two__right">

                            <div class="owl-carousel owl-theme thm-owl__carousel testimonial-two__carousel"
                                data-owl-options='{
                                "loop": true,
                                "autoplay": true,
                                "margin": 30,
                                "nav": false,
                                "dots": false,
                                "smartSpeed": 500,
                                "autoplayTimeout": 10000,
                                "navText": ["<span class=\"fa fa-angle-left\"></span>","<span class=\"fa fa-angle-right\"></span>"],
                                "responsive": {
                                    "0": {
                                        "items": 1
                                    },
                                    "768": {
                                        "items": 1
                                    },
                                    "1200": {
                                        "items": 2.01
                                    }
                                }
                            }'>

                                <!--Testimonial One Single-->
                                <div class="testimonial-two__single">
                                    <div class="testimonial-two__feedback-box">
                                        <div class="testimonial-two__feedback">
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                        </div>
                                        <h3 class="testimonial-two__title">Hard workers and on time!</h3>
                                    </div>
                                    <p class="testimonial-two__text">Lorem ipsum is simply free text dolor not sit
                                        amet,
                                        consectetur notted adipisicing elit sed do eiusmod tempor incididunt labore et
                                        dolore text.</p>
                                    <div class="testimonial-two__client-info-box">
                                        <div class="testimonial-two__client-info">
                                            <h4 class="testimonial-two__client-name">Kevin Martin</h4>
                                            <p class="testimonial-two__client-title">Customer</p>
                                        </div>
                                        <div class="testimonial-two__quote">
                                            <span class="icon-quote"></span>
                                        </div>
                                    </div>
                                    <div class="testimonial-two__client-img">
                                        <img src="{{ asset('wostin/files/assets/images/testimonial/testimonial-2-1.jpg') }}"
                                            alt="">
                                    </div>
                                </div>
                                <!--Testimonial One Single-->
                                <div class="testimonial-two__single">
                                    <div class="testimonial-two__feedback-box">
                                        <div class="testimonial-two__feedback">
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                        </div>
                                        <h3 class="testimonial-two__title">Great job and fair pricing</h3>
                                    </div>
                                    <p class="testimonial-two__text">Lorem ipsum is simply free text dolor not sit
                                        amet,
                                        consectetur notted adipisicing elit sed do eiusmod tempor incididunt labore et
                                        dolore text.</p>
                                    <div class="testimonial-two__client-info-box">
                                        <div class="testimonial-two__client-info">
                                            <h4 class="testimonial-two__client-name">Jessica Brown</h4>
                                            <p class="testimonial-two__client-title">Customer</p>
                                        </div>
                                        <div class="testimonial-two__quote">
                                            <span class="icon-quote"></span>
                                        </div>
                                    </div>
                                    <div class="testimonial-two__client-img">
                                        <img src="{{ asset('wostin/files/assets/images/testimonial/testimonial-2-2.jpg') }}"
                                            alt="">
                                    </div>
                                </div>
                                <!--Testimonial One Single-->
                                <div class="testimonial-two__single">
                                    <div class="testimonial-two__feedback-box">
                                        <div class="testimonial-two__feedback">
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                        </div>
                                        <h3 class="testimonial-two__title">Cleaned and dispose well</h3>
                                    </div>
                                    <p class="testimonial-two__text">Lorem ipsum is simply free text dolor not sit
                                        amet,
                                        consectetur notted adipisicing elit sed do eiusmod tempor incididunt labore et
                                        dolore text.</p>
                                    <div class="testimonial-two__client-info-box">
                                        <div class="testimonial-two__client-info">
                                            <h4 class="testimonial-two__client-name">Mike Hardson</h4>
                                            <p class="testimonial-two__client-title">Customer</p>
                                        </div>
                                        <div class="testimonial-two__quote">
                                            <span class="icon-quote"></span>
                                        </div>
                                    </div>
                                    <div class="testimonial-two__client-img">
                                        <img src="{{ asset('wostin/files/assets/images/testimonial/testimonial-2-3.jpg') }}"
                                            alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Testimonial Two End-->

        <!--Project Two Start-->
        <section class="project-two">
            <div class="project-two-bg-box">
                <div class="project-two-bg"
                    style="background-image: url({{ asset('wostin/files/assets/images/backgrounds/project-two-bg.jpg') }});">
                </div>
            </div>
            <div class="container">
                <div class="section-title text-center">
                    <span class="section-title__tagline">Recent Closed Projects</span>
                    <h2 class="section-title__title">Recently We Successfully Completed <br> Some Waste Projects</h2>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="project-two__carousel owl-carousel owl-theme thm-owl__carousel"
                            data-owl-options='{
                            "loop": true,
                            "autoplay": true,
                            "margin": 30,
                            "nav": false,
                            "dots": true,
                            "smartSpeed": 500,
                            "autoplayTimeout": 10000,
                            "navText": ["<span class=\"fa fa-angle-left\"></span>","<span class=\"fa fa-angle-right\"></span>"],
                            "responsive": {
                                "0": {
                                    "items": 1
                                },
                                "768": {
                                    "items": 2
                                },
                                "992": {
                                    "items": 4
                                },
                                "1200": {
                                    "items": 4
                                }
                            }
                        }'>
                            <!--Project Two Single-->
                            <div class="project-two__single">
                                <div class="project-two__img">
                                    <img src="{{ asset('wostin/files/assets/images/resources/project-2-1.jpg') }}"
                                        alt="">
                                    <div class="project-two__content">
                                        <p class="project-two__subtitle">Waste Pickup</p>
                                        <h3 class="project-two__title"><a href="project-details.html">Grocery
                                                Waste</a>
                                        </h3>
                                    </div>
                                    <div class="project-two__arrow">
                                        <a href="project-details.html"><i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!--Project Two Single-->
                            <div class="project-two__single">
                                <div class="project-two__img">
                                    <img src="{{ asset('wostin/files/assets/images/resources/project-2-2.jpg') }}"
                                        alt="">
                                    <div class="project-two__content">
                                        <p class="project-two__subtitle">Waste Pickup</p>
                                        <h3 class="project-two__title"><a href="project-details.html">Home Trash
                                                Picked</a></h3>
                                    </div>
                                    <div class="project-two__arrow">
                                        <a href="project-details.html"><i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!--Project Two Single-->
                            <div class="project-two__single">
                                <div class="project-two__img">
                                    <img src="{{ asset('wostin/files/assets/images/resources/project-2-3.jpg') }}"
                                        alt="">
                                    <div class="project-two__content">
                                        <p class="project-two__subtitle">Waste Pickup</p>
                                        <h3 class="project-two__title"><a href="project-details.html">Construction
                                                Waste</a></h3>
                                    </div>
                                    <div class="project-two__arrow">
                                        <a href="project-details.html"><i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!--Project Two Single-->
                            <div class="project-two__single">
                                <div class="project-two__img">
                                    <img src="{{ asset('wostin/files/assets/images/resources/project-2-4.jpg') }}"
                                        alt="">
                                    <div class="project-two__content">
                                        <p class="project-two__subtitle">Waste Pickup</p>
                                        <h3 class="project-two__title"><a href="project-details.html">Business
                                                Waste</a>
                                        </h3>
                                    </div>
                                    <div class="project-two__arrow">
                                        <a href="project-details.html"><i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!--Project Two Single-->
                            <div class="project-two__single">
                                <div class="project-two__img">
                                    <img src="{{ asset('wostin/files/assets/images/resources/project-2-1.jpg') }}"
                                        alt="">
                                    <div class="project-two__content">
                                        <p class="project-two__subtitle">Waste Pickup</p>
                                        <h3 class="project-two__title"><a href="project-details.html">Grocery
                                                Waste</a>
                                        </h3>
                                    </div>
                                    <div class="project-two__arrow">
                                        <a href="project-details.html"><i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!--Project Two Single-->
                            <div class="project-two__single">
                                <div class="project-two__img">
                                    <img src="{{ asset('wostin/files/assets/images/resources/project-2-2.jpg') }}"
                                        alt="">
                                    <div class="project-two__content">
                                        <p class="project-two__subtitle">Waste Pickup</p>
                                        <h3 class="project-two__title"><a href="project-details.html">Home Trash
                                                Picked</a></h3>
                                    </div>
                                    <div class="project-two__arrow">
                                        <a href="project-details.html"><i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!--Project Two Single-->
                            <div class="project-two__single">
                                <div class="project-two__img">
                                    <img src="{{ asset('wostin/files/assets/images/resources/project-2-3.jpg') }}"
                                        alt="">
                                    <div class="project-two__content">
                                        <p class="project-two__subtitle">Waste Pickup</p>
                                        <h3 class="project-two__title"><a href="project-details.html">Construction
                                                Waste</a></h3>
                                    </div>
                                    <div class="project-two__arrow">
                                        <a href="project-details.html"><i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!--Project Two Single-->
                            <div class="project-two__single">
                                <div class="project-two__img">
                                    <img src="{{ asset('wostin/files/assets/images/resources/project-2-4.jpg') }}"
                                        alt="">
                                    <div class="project-two__content">
                                        <p class="project-two__subtitle">Waste Pickup</p>
                                        <h3 class="project-two__title"><a href="project-details.html">Business
                                                Waste</a>
                                        </h3>
                                    </div>
                                    <div class="project-two__arrow">
                                        <a href="project-details.html"><i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!--Project Two Single-->
                            <div class="project-two__single">
                                <div class="project-two__img">
                                    <img src="{{ asset('wostin/files/assets/images/resources/project-2-1.jpg') }}"
                                        alt="">
                                    <div class="project-two__content">
                                        <p class="project-two__subtitle">Waste Pickup</p>
                                        <h3 class="project-two__title"><a href="project-details.html">Grocery
                                                Waste</a>
                                        </h3>
                                    </div>
                                    <div class="project-two__arrow">
                                        <a href="project-details.html"><i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!--Project Two Single-->
                            <div class="project-two__single">
                                <div class="project-two__img">
                                    <img src="{{ asset('wostin/files/assets/images/resources/project-2-2.jpg') }}"
                                        alt="">
                                    <div class="project-two__content">
                                        <p class="project-two__subtitle">Waste Pickup</p>
                                        <h3 class="project-two__title"><a href="project-details.html">Home Trash
                                                Picked</a></h3>
                                    </div>
                                    <div class="project-two__arrow">
                                        <a href="project-details.html"><i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!--Project Two Single-->
                            <div class="project-two__single">
                                <div class="project-two__img">
                                    <img src="{{ asset('wostin/files/assets/images/resources/project-2-3.jpg') }}"
                                        alt="">
                                    <div class="project-two__content">
                                        <p class="project-two__subtitle">Waste Pickup</p>
                                        <h3 class="project-two__title"><a href="project-details.html">Construction
                                                Waste</a></h3>
                                    </div>
                                    <div class="project-two__arrow">
                                        <a href="project-details.html"><i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!--Project Two Single-->
                            <div class="project-two__single">
                                <div class="project-two__img">
                                    <img src="{{ asset('wostin/files/assets/images/resources/project-2-4.jpg') }}"
                                        alt="">
                                    <div class="project-two__content">
                                        <p class="project-two__subtitle">Waste Pickup</p>
                                        <h3 class="project-two__title"><a href="project-details.html">Business
                                                Waste</a>
                                        </h3>
                                    </div>
                                    <div class="project-two__arrow">
                                        <a href="project-details.html"><i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Project Two End-->

        <!--Contact One Start-->
        <section class="contact-one">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6">
                        <div class="contact-one__left">
                            <div class="section-title text-left">
                                <span class="section-title__tagline">Contact With Us</span>
                                <h2 class="section-title__title">Have Questions? Feel Free to Write Us</h2>
                            </div>
                            <p class="contact-one__text">Lorem ipsum dolor sit amet, consectetur notted adipis not
                                icing
                                elit sed do eiusmod tempor incididunt.</p>
                            <ul class="list-unstyled contact-one__info">
                                <li>
                                    <div class="icon">
                                        <span class="icon-message"></span>
                                    </div>
                                    <div class="text">
                                        <p>Call Anytime</p>
                                        <a href="tel:12463330088">+ 1 - (246) 333-0088</a>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <span class="icon-phone-call"></span>
                                    </div>
                                    <div class="text">
                                        <p>Write Email</p>
                                        <a href="mailto:needhelp@wostin.com">needhelp@wostin.com</a>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <span class="icon-placeholder"></span>
                                    </div>
                                    <div class="text">
                                        <p>Visit Us Anytime</p>
                                        <span>880 Broklyn Street New York. USA</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="contact-one__right">
                            <form action=" " class="contact-one__form contact-form-validated"
                                novalidate="novalidate">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="contact-one__form-input-box">
                                            <input type="text" placeholder="Your name" name="name">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="contact-one__form-input-box">
                                            <input type="email" placeholder="Email address" name="email">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="contact-one__form-input-box">
                                            <input type="text" placeholder="Phone number" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="contact-one__form-input-box">
                                            <input type="text" placeholder="Subject" name="subject">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="contact-one__form-input-box text-message-box">
                                            <textarea name="message" placeholder="Write message"></textarea>
                                        </div>
                                        <div class="contact-one__btn-box">
                                            <button type="submit" class="thm-btn contact-one__btn">Send a
                                                Message</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Contact One End-->

        <!--Google Map Start-->
        <section class="google-map">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4562.753041141002!2d-118.80123790098536!3d34.152323469614075!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80e82469c2162619%3A0xba03efb7998eef6d!2sCostco+Wholesale!5e0!3m2!1sbn!2sbd!4v1562518641290!5m2!1sbn!2sbd"
                class="google-map__one" allowfullscreen></iframe>

        </section>
        <!--Google Map End-->

        <!--News Two Start-->
        <section class="news-two">
            <div class="container">
                <div class="section-title text-center">
                    <span class="section-title__tagline">What’s Happening</span>
                    <h2 class="section-title__title">Latest News & Articles</h2>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay="100ms">
                        <!--News Two Single-->
                        <div class="news-two__single">
                            <div class="news-two__img">
                                <img src="{{ asset('wostin/files/assets/images/blog/news-2-1.jpg') }}"
                                    alt="">
                                <div class="news-two__date">
                                    <p>20 <br> Dec</p>
                                </div>
                                <div class="news-two__content">
                                    <ul class="list-unstyled news-two__meta">
                                        <li><a href="news-details.html"><i class="far fa-clock"></i> by Admin </a>
                                    </ul>
                                    <h3 class="news-two__title"><a href="news-details.html">Learn Why Recycling is
                                            Important</a></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay="200ms">
                        <!--News Two Single-->
                        <div class="news-two__single">
                            <div class="news-two__img">
                                <img src="{{ asset('wostin/files/assets/images/blog/news-2-2.jpg') }}"
                                    alt="">
                                <div class="news-two__date">
                                    <p>20 <br> Dec</p>
                                </div>
                                <div class="news-two__content">
                                    <ul class="list-unstyled news-two__meta">
                                        <li><a href="news-details.html"><i class="far fa-clock"></i> by Admin </a>
                                    </ul>
                                    <h3 class="news-two__title"><a href="news-details.html">Leverage agile frameworks
                                            to
                                            provide</a></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 wow fadeInUp" data-wow-delay="300ms">
                        <!--News Two Single-->
                        <div class="news-two__single">
                            <ul class="list-unstyled news-two__list">
                                <li class="news-two__list-single">
                                    <div class="news-two__list-img">
                                        <img src="{{ asset('wostin/files/assets/images/blog/news-2-list-1.jpg') }}"
                                            alt="">
                                    </div>
                                    <div class="news-two__list-content">
                                        <ul class="list-unstyled news-two__list-meta">
                                            <li><a href="news-details.html"><i class="fa fa-calendar-alt"></i> 20 Dec
                                                </a>
                                            </li>
                                            <li><span>/</span></li>
                                            <li><a href="news-details.html"><i class="far fa-comments"></i> 02
                                                    Comments</a>
                                            </li>
                                        </ul>
                                        <h3 class="news-two__list-title"><a href="news-details.html">Have Clean &
                                                Healthy Future with us</a></h3>
                                    </div>
                                </li>
                                <li class="news-two__list-single">
                                    <div class="news-two__list-img">
                                        <img src="{{ asset('wostin/files/assets/images/blog/news-2-list-2.jpg') }}"
                                            alt="">
                                    </div>
                                    <div class="news-two__list-content">
                                        <ul class="list-unstyled news-two__list-meta">
                                            <li><a href="news-details.html"><i class="fa fa-calendar-alt"></i> 20 Dec
                                                </a>
                                            </li>
                                            <li><span>/</span></li>
                                            <li><a href="news-details.html"><i class="far fa-comments"></i> 02
                                                    Comments</a>
                                            </li>
                                        </ul>
                                        <h3 class="news-two__list-title"><a href="news-details.html">Bring to the
                                                table
                                                win-win survival</a></h3>
                                    </div>
                                </li>
                                <li class="news-two__list-single">
                                    <div class="news-two__list-img">
                                        <img src="{{ asset('wostin/files/assets/images/blog/news-2-list-3.jpg') }}"
                                            alt="">
                                    </div>
                                    <div class="news-two__list-content">
                                        <ul class="list-unstyled news-two__list-meta">
                                            <li><a href="news-details.html"><i class="fa fa-calendar-alt"></i> 20 Dec
                                                </a>
                                            </li>
                                            <li><span>/</span></li>
                                            <li><a href="news-details.html"><i class="far fa-comments"></i> 02
                                                    Comments</a>
                                            </li>
                                        </ul>
                                        <h3 class="news-two__list-title"><a href="news-details.html">A Quick solutions
                                                for daily service</a></h3>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--News Two End-->

        <!--CTA One Start-->
        <section class="cta-one">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="cta-one__inner">
                            <h2 class="cta-one__title">Do You Have Home or Business Waste?</h2>
                            <div class="cta-one__btn-box">
                                <a href="request-pickup.html" class="thm-btn cta-one__btn">Request a Pickup</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--CTA One  End-->
@endsection
