@extends('layouts.landingPage')

@section('content')
     
 
  <!--Contact One Start-->
            <section class="contact-one contact-page">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6">
                            <div class="contact-one__left">
                                <div class="section-title text-left">
                                    <span class="section-title__tagline"
                                        >Contact With Us</span
                                    >
                                    <h2 class="section-title__title">
                                        Have Questions? Feel Free to Write Us
                                    </h2>
                                </div>
                                <p class="contact-one__text">
                                    Lorem ipsum dolor sit amet, consectetur
                                    notted adipis not icing elit sed do eiusmod
                                    tempor incididunt.
                                </p>
                                <ul class="list-unstyled contact-one__info">
                                    <li>
                                        <div class="icon">
                                            <span class="icon-message"></span>
                                        </div>
                                        <div class="text">
                                            <p>Call Anytime</p>
                                            <a href="tel:12463330088"
                                                >+ 1 - (246) 333-0088</a
                                            >
                                        </div>
                                    </li>
                                    <li>
                                        <div class="icon">
                                            <span
                                                class="icon-phone-call"
                                            ></span>
                                        </div>
                                        <div class="text">
                                            <p>Write Email</p>
                                            <a href="mailto:needhelp@wostin.com"
                                                >needhelp@wostin.com</a
                                            >
                                        </div>
                                    </li>
                                    <li>
                                        <div class="icon">
                                            <span
                                                class="icon-placeholder"
                                            ></span>
                                        </div>
                                        <div class="text">
                                            <p>Visit Us Anytime</p>
                                            <span
                                                >880 Broklyn Street New York.
                                                USA</span
                                            >
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6">
                            <div class="contact-one__right">
                                <form
                                    action="assets/inc/sendemail.php"
                                    class="contact-one__form contact-form-validated"
                                    novalidate="novalidate"
                                >
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6">
                                            <div
                                                class="contact-one__form-input-box"
                                            >
                                                <input
                                                    type="text"
                                                    placeholder="Your name"
                                                    name="name"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6">
                                            <div
                                                class="contact-one__form-input-box"
                                            >
                                                <input
                                                    type="email"
                                                    placeholder="Email address"
                                                    name="email"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6">
                                            <div
                                                class="contact-one__form-input-box"
                                            >
                                                <input
                                                    type="text"
                                                    placeholder="Phone number"
                                                    name="phone"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6">
                                            <div
                                                class="contact-one__form-input-box"
                                            >
                                                <input
                                                    type="text"
                                                    placeholder="Subject"
                                                    name="subject"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div
                                                class="contact-one__form-input-box text-message-box"
                                            >
                                                <textarea
                                                    name="message"
                                                    placeholder="Write message"
                                                ></textarea>
                                            </div>
                                            <div class="contact-one__btn-box">
                                                <button
                                                    type="submit"
                                                    class="thm-btn contact-one__btn"
                                                >
                                                    Send a Message
                                                </button>
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

 
 
@endsection

@section('scripts')
     
@endsection

@section('styles')
   
@endsection
@section('title', 'Registrasi')
@section('description', 'Halaman Registrasi untuk pengguna baru')