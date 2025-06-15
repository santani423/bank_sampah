@extends('layouts.landingPage')

@section('content')
    <!--Main Slider Start-->
    <section class="main-slider">
        <div class="slideshow-container">
            <div class="slides-wrapper" id="slidesWrapper">
                <div class="mySlide">
                    <img src="https://i.ytimg.com/vi/1CZhGDU5cWM/maxresdefault.jpg" alt="Nature">
                    <div class="text">Caption One</div>
                </div>
                <div class="mySlide">
                    <img src="https://i.ytimg.com/vi/1CZhGDU5cWM/maxresdefault.jpg" alt="Snow">
                    <div class="text">Caption Two</div>
                </div>
                <div class="mySlide">
                    <img src="https://i.ytimg.com/vi/1CZhGDU5cWM/maxresdefault.jpg" alt="Mountains">
                    <div class="text">Caption Three</div>
                </div>
            </div>
        </div>
        <br>
        <div style="text-align:center">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </section>
    <!--Main Slider End-->

    <!--Feature One Start-->
    <section class="feature-one">
        <div class="container">
            <div class="feature-one__inner">
                <ul class="list-unstyled feature-one__list">
                    <li class="feature-one__single wow fadeInUp" data-wow-delay="100ms">
                        <div class="feature-one__icon">
                            <span class="icon-budget"></span>
                        </div>
                        <h3 class="feature-one__title">
                            <a href="about.html">You Estimate <br />
                                Load Size</a>
                        </h3>
                    </li>
                    <li class="feature-one__single wow fadeInUp" data-wow-delay="200ms">
                        <div class="feature-one__icon">
                            <span class="icon-calendar"></span>
                        </div>
                        <h3 class="feature-one__title">
                            <a href="about.html">You Choose <br />
                                a Time</a>
                        </h3>
                    </li>
                    <li class="feature-one__single wow fadeInUp" data-wow-delay="300ms">
                        <div class="feature-one__icon">
                            <span class="icon-garbage"></span>
                        </div>
                        <h3 class="feature-one__title">
                            <a href="about.html">We Pick <br />
                                & Clean up</a>
                        </h3>
                    </li>
                    <li class="feature-one__single wow fadeInUp" data-wow-delay="400ms">
                        <div class="feature-one__icon">
                            <span class="icon-garbage-truck"></span>
                        </div>
                        <h3 class="feature-one__title">
                            <a href="about.html">We Responsibily <br />
                                dispose</a>
                        </h3>
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <!--Feature One End-->
   <!--Why Choose Two Start-->
        <section class="why-choose-two">
            <div class="why-choose-two-bg"
                style="background-image: url(assets/images/backgrounds/why-choose-two-bg.jpg);"></div>
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6">
                        <div class="why-choose-two__left">
                            <div class="section-title text-left">
                                <span class="section-title__tagline">Why Choose Us?</span>
                                <h2 class="section-title__title">We Make Sure your Waste Goes to the Right Place</h2>
                            </div>
                            <p class="why-choose-two__text">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua enim ad minim veniam.
                            </p>
                            <div class="why-choose-two__founder">
                                <div class="why-choose-two__founder-img">
                                    <img src="assets/images/resources/why-choose-two-founder-img.jpg" alt="">
                                </div>
                                <div class="why-choose-two__founder-content">
                                    <h3 class="why-choose-two__founder-name">Kevin Martin</h3>
                                    <p class="why-choose-two__founder-title">CEO - Co Founder</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="why-choose-two__right">
                            <ul class="list-unstyled why-choose-two__counter">
                                <li class="why-choose-two__counter-single wow fadeInUp" data-wow-delay="100ms">
                                    <div class="why-choose-two__counter-icon">
                                        <span class="icon-waste"></span>
                                    </div>
                                    <div class="why-choose-two__counter-content">
                                        <h2 class="odometer" data-count="4850">00</h2> 
                                        <p class="why-choose-two__counter-text">Waste Picked & Dispose </p>
                                    </div>
                                </li>
                                <li class="why-choose-two__counter-single wow fadeInUp" data-wow-delay="200ms">
                                    <div class="why-choose-two__counter-icon">
                                        <span class="icon-success"></span>
                                    </div>
                                    <div class="why-choose-two__counter-content">
                                        <h2 class="odometer" data-count="99.9">00</h2> 
                                        <p class="why-choose-two__counter-text">Our Company is Successful</p>
                                    </div>
                                </li>
                                <li class="why-choose-two__counter-single wow fadeInUp" data-wow-delay="300ms">
                                    <div class="why-choose-two__counter-icon">
                                        <span class="icon-affection"></span>
                                    </div>
                                    <div class="why-choose-two__counter-content">
                                        <h2 class="odometer" data-count="20660">00</h2> 
                                        <p class="why-choose-two__counter-text">Satisfied & Happy People</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Why Choose Two End-->


 
 

 

 

 
 

 
 
@endsection

@section('scripts')
    <script>
        let slideIndex = 0;
        const slidesWrapper = document.getElementById("slidesWrapper");
        const slides = document.getElementsByClassName("mySlide");
        const dots = document.getElementsByClassName("dot");
        const totalSlides = slides.length;

        function showSlide(index) {
            const offset = -index * 100;
            slidesWrapper.style.transform = `translateX(${offset}%)`;

            for (let i = 0; i < dots.length; i++) {
                dots[i].classList.remove("active");
            }
            dots[index].classList.add("active");
        }

        function autoSlide() {
            slideIndex = (slideIndex + 1) % totalSlides;
            showSlide(slideIndex);
            setTimeout(autoSlide, 5000); // 5 detik
        }

        // Mulai otomatis
        showSlide(slideIndex);
        setTimeout(autoSlide, 5000);
    </script>
@endsection

@section('styles')
    <style>
        * {
            box-sizing: border-box;
        }

        .slideshow-container {
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .slides-wrapper {
            display: flex;
            transition: transform 0.6s ease-in-out;
            width: 300%;
            /* 3 slides */
        }

        .mySlide {
            min-width: 100%;
            position: relative;
            aspect-ratio: 16 / 9;
            /* Buat responsif tinggi */
        }

        .mySlide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Crop gambar agar sesuai container */
            display: block;
        }

        .text {
            color: #fff;
            font-size: 15px;
            padding: 8px 12px;
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            background: rgba(0, 0, 0, 0.4);
        }

        .dot {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }

        .active {
            background-color: #717171;
        }

        @media only screen and (max-width: 600px) {
            .text {
                font-size: 12px;
            }

            .mySlide {
                aspect-ratio: 4 / 3;
            }
        }
    </style>
@endsection
