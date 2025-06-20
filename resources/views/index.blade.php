@extends('layouts.landingPage')

@section('content')
    <!--Main Slider Start-->
    <section class="main-slider">
        <div class="slideshow-container"
            style="width:100vw; max-width:100vw; left:50%; right:50%; margin-left:-50vw; margin-right:-50vw; position:relative;">
            <div class="slides-wrapper" id="slidesWrapper">
                <div class="mySlide">
                    <img src="{{ asset('assets/img/banner/1.jpg') }}" alt="Nature"
                        style="width:100vw; max-width:100vw;">
                    <div class="text">Caption One</div>
                </div>
                <div class="mySlide">
                    <img src="{{ asset('assets/img/banner/2.jpg') }}" alt="Nature"
                        style="width:100vw; max-width:100vw;">
                    <div class="text">Caption Two</div>
                </div>
                <div class="mySlide">
                    <img src="{{ asset('assets/img/banner/1.jpg') }}" alt="Nature"
                        style="width:100vw; max-width:100vw;">
                    <div class="text">Caption Three</div>
                </div>
            </div>
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
                            <img src="{{ asset('assets/icons/check.png') }}" width="80" alt="Pilih Sampah">
                        </div>
                        <h3 class="feature-one__title">
                            <a href="#">Pilah Sampah<br />di Rumah</a>
                        </h3>
                    </li>
                    <li class="feature-one__single wow fadeInUp" data-wow-delay="200ms">
                        <div class="feature-one__icon">
                            <img src="{{ asset('assets/icons/pickup-time.png') }}" width="80" alt="Tentukan Jadwal">
                        </div>
                        <h3 class="feature-one__title">
                            <a href="#">Tentukan Jadwal<br />Penjemputan</a>
                        </h3>
                    </li>
                    <li class="feature-one__single wow fadeInUp" data-wow-delay="300ms">
                        <div class="feature-one__icon">
                            <img src="{{ asset('assets/icons/garbage-truck.png') }}" width="80" alt="Ambil Sampah">
                        </div>
                        <h3 class="feature-one__title">
                            <a href="#">Petugas Kami<br />Mengambil Sampah</a>
                        </h3>
                    </li>
                    <li class="feature-one__single wow fadeInUp" data-wow-delay="400ms">
                        <div class="feature-one__icon">
                            <img src="{{ asset('assets/icons/recycle-bin.png') }}" width="80" alt="Sampah Dikelola">
                        </div>
                        <h3 class="feature-one__title">
                            <a href="#">Sampah Dikelola<br />Bertanggung Jawab</a>
                        </h3>
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <!--Feature One End-->
    <!--Why Choose Two Start-->
    <section class="why-choose-two">
        <div class="why-choose-two-bg" style="background-image: url(assets/images/backgrounds/why-choose-two-bg.jpg);">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="why-choose-two__left">
                        <div class="section-title text-left">
                            <span class="section-title__tagline">Mengapa Memilih Kami?</span>
                            <h2 class="section-title__title">Kami Pastikan Sampah Anda Dikelola dengan Bertanggung Jawab
                            </h2>
                        </div>
                        <p class="why-choose-two__text">
                            Bank Sampah kami berkomitmen untuk membantu masyarakat mengelola sampah secara bijak, ramah
                            lingkungan, dan memberikan manfaat ekonomi. Bersama, kita wujudkan lingkungan yang bersih dan
                            sehat.
                        </p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="why-choose-two__right">
                        <ul class="list-unstyled why-choose-two__counter">
                            <li class="why-choose-two__counter-single wow fadeInUp" data-wow-delay="100ms">
                                <div class="why-choose-two__counter-icon">
                                    <img src="{{ asset('assets/icons/nasabah.png') }}" width="80" alt="sadasd">
                                </div>
                                <div class="why-choose-two__counter-content">
                                    <h2 class="odometer" data-count="4850" id="nasabahCount">00</h2>
                                    <p class="why-choose-two__counter-text">Nasabah</p>
                                </div>
                            </li>
                            <li class="why-choose-two__counter-single wow fadeInUp" data-wow-delay="200ms">
                                <div class="why-choose-two__counter-icon">
                                    <img src="{{ asset('assets/icons/officer.png') }}" width="80" alt="">
                                </div>
                                <div class="why-choose-two__counter-content">
                                    <h2 class="odometer" data-count="99.9" id="petugasCount">00</h2>
                                    <p class="why-choose-two__counter-text">Petugas</p>
                                </div>
                            </li>
                            <li class="why-choose-two__counter-single wow fadeInUp" data-wow-delay="300ms">
                                <div class="why-choose-two__counter-icon">
                                    <img src="{{ asset('assets/icons/cabang.png') }}" width="80" alt="">
                                </div>
                                <div class="why-choose-two__counter-content">
                                    <h2 class="odometer" data-count="20660" id="cabangCount">00</h2>
                                    <p class="why-choose-two__counter-text">Cabang</p>
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


    <script>
        window.addEventListener('load', function() {
            setInterval(function() {
                fetch(`{{ route('api.summary.counts') }}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Lakukan sesuatu dengan data
                        console.log(data);
                        document.getElementById('nasabahCount').textContent = data.count_nasabah;
                        document.getElementById('petugasCount').textContent = data.count_petugas;
                        document.getElementById('cabangCount').textContent = data.count_cabang;
                    })
                    .catch(error => {
                        console.error('AJAX error:', error);
                    });
            }, 3000);
        });
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

        element.style {
            height: 100%;
        }

        .mySlide {
            min-width: 30%;
            position: relative;
            aspect-ratio: 16 / 9;
        }
    </style>
@endsection
