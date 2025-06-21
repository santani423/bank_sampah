@extends('layouts.landingPage')

@section('title', 'Kegiatan')

@section('content')
  <section class="page-header">
        <div class="page-header-bg"
            style="background-image: url({{ asset('assets/images/backgrounds/page-header-bg.jpg') }});"></div>
        <div class="container">
            <div class="page-header__inner">
                <h2>Kegiatan</h2>
                
            </div>
        </div>
    </section>

       <!--Gallery Page Start-->
        <section class="gallery-page">
            <div class="container">
                <div class="row masonary-layout">
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <!--Gallery Page Single-->
                        <div class="gallery-page__single">
                            <div class="gallery-page__img">
                                <img src="{{ asset('wostin/files/assets/images/gallery/gallery-page-1.jpg') }}" alt="">
                                <div class="gallery-page__icon">
                                    <a class="img-popup" href="assets/images/gallery/gallery-page-1.jpg') }}"><span
                                            class="icon-plus-symbol"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <!--Gallery Page Single-->
                        <div class="gallery-page__single">
                            <div class="gallery-page__img">
                                <img src="{{ asset('wostin/files/assets/images/gallery/gallery-page-2.jpg') }}" alt="">
                                <div class="gallery-page__icon">
                                    <a class="img-popup" href="assets/images/gallery/gallery-page-2.jpg') }}"><span
                                            class="icon-plus-symbol"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <!--Gallery Page Single-->
                        <div class="gallery-page__single">
                            <div class="gallery-page__img">
                                <img src="{{ asset('wostin/files/assets/images/gallery/gallery-page-3.jpg') }}" alt="">
                                <div class="gallery-page__icon">
                                    <a class="img-popup" href="assets/images/gallery/gallery-page-3.jpg') }}"><span
                                            class="icon-plus-symbol"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <!--Gallery Page Single-->
                        <div class="gallery-page__single">
                            <div class="gallery-page__img">
                                <img src="{{ asset('wostin/files/assets/images/gallery/gallery-page-4.jpg') }}" alt="">
                                <div class="gallery-page__icon">
                                    <a class="img-popup" href="assets/images/gallery/gallery-page-4.jpg') }}"><span
                                            class="icon-plus-symbol"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <!--Gallery Page Single-->
                        <div class="gallery-page__single">
                            <div class="gallery-page__img">
                                <img src="{{ asset('wostin/files/assets/images/gallery/gallery-page-5.jpg') }}" alt="">
                                <div class="gallery-page__icon">
                                    <a class="img-popup" href="assets/images/gallery/gallery-page-5.jpg') }}"><span
                                            class="icon-plus-symbol"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <!--Gallery Page Single-->
                        <div class="gallery-page__single">
                            <div class="gallery-page__img">
                                <img src="{{ asset('wostin/files/assets/images/gallery/gallery-page-6.jpg') }}" alt="">
                                <div class="gallery-page__icon">
                                    <a class="img-popup" href="assets/images/gallery/gallery-page-6.jpg') }}"><span
                                            class="icon-plus-symbol"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <!--Gallery Page Single-->
                        <div class="gallery-page__single">
                            <div class="gallery-page__img">
                                <img src="{{ asset('wostin/files/assets/images/gallery/gallery-page-7.jpg') }}" alt="">
                                <div class="gallery-page__icon">
                                    <a class="img-popup" href="assets/images/gallery/gallery-page-7.jpg') }}"><span
                                            class="icon-plus-symbol"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Gallery Page End-->
@endsection