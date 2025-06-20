@extends('layouts.landingPage')

@section('content')
<!-- Login Section Start -->
<section class="contact-one contact-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-6">
                <div class="contact-one__right">
                    <div class="section-title text-center mb-4">
                        <span class="section-title__tagline">Login</span>
                        <h2 class="section-title__title">Sign In to Your Account</h2>
                    </div>
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="contact-one__form-input-box">
                                    <input
                                        type="email"
                                        placeholder="Email address"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                    />
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="contact-one__form-input-box">
                                    <input
                                        type="password"
                                        placeholder="Password"
                                        name="password"
                                        required
                                    />
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="contact-one__btn-box">
                                    <button type="submit" class="thm-btn contact-one__btn">
                                        Login
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 mt-3 text-center">
                                <a href="{{ route('password.request') }}">Forgot Your Password?</a>
                            </div>
                            <div class="col-12 mt-2 text-center">
                                <span>Don't have an account? <a href="{{ route('register') }}">Register</a></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Login Section End -->
@endsection

@section('scripts')
@endsection

@section('styles')
@endsection

@section('title', 'Login')
@section('description', 'Halaman Login untuk pengguna')
