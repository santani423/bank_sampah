@extends('layouts.login')

@section('title', 'Login')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('asset/library/bootstrap-social/bootstrap-social.css') }}">
@endpush

@section('main')
    <div class="auth-right__inner mx-auto w-100">
        <a href="index.html" class="auth-right__logo">
            <img src="{{ asset('edmate/assets/images/logo/logo.png') }}" alt="">
        </a>
        <h2 class="mb-8">Welcome Back! 👋</h2>
        <p class="text-gray-600 text-15 mb-32">Please sign in to your account and start the adventure</p>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-24">
                <label for="username" class="form-label mb-8 h6">Username</label>
                <div class="position-relative">
                    <input 
                        type="text" 
                        class="form-control py-11 ps-40 @error('username') is-invalid @enderror" 
                        id="username" 
                        name="username" 
                        placeholder="Type your username" 
                        value="{{ old('username') }}">
                    <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex"><i class="ph ph-user"></i></span>
                    @error('username')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-24">
                <label for="password" class="form-label mb-8 h6">Password</label>
                <div class="position-relative">
                    <input 
                        type="password" 
                        class="form-control py-11 ps-40 @error('password') is-invalid @enderror" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password">
                    <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph ph-eye-slash" id="#password"></span>
                    <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex"><i class="ph ph-lock"></i></span>
                    @error('password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-32 flex-between flex-wrap gap-8">
                <div class="form-check mb-0 flex-shrink-0">
                    <input 
                        class="form-check-input flex-shrink-0 rounded-4" 
                        type="checkbox" 
                        name="remember" 
                        id="remember" 
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-15 flex-grow-1" for="remember">Remember Me</label>
                </div>
                <a href=" " class="text-main-600 hover-text-decoration-underline text-15 fw-medium">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-main rounded-pill w-100">Sign In</button>
            <p class="mt-32 text-gray-600 text-center">New on our platform?
                <a href="{{route('register')}}" class="text-main-600 hover-text-decoration-underline">Create an account</a>
            </p>

            <div class="divider my-32 position-relative text-center">
                <span class="divider__text text-gray-600 text-13 fw-medium px-26 bg-white">or</span>
            </div>

            <ul class="flex-align gap-10 flex-wrap justify-content-center">
                <li>
                    <a href="https://www.facebook.com" class="w-38 h-38 flex-center rounded-6 text-facebook-600 bg-facebook-50 hover-bg-facebook-600 hover-text-white text-lg">
                        <i class="ph-fill ph-facebook-logo"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.twitter.com" class="w-38 h-38 flex-center rounded-6 text-twitter-600 bg-twitter-50 hover-bg-twitter-600 hover-text-white text-lg">
                        <i class="ph-fill ph-twitter-logo"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.google.com" class="w-38 h-38 flex-center rounded-6 text-google-600 bg-google-50 hover-bg-google-600 hover-text-white text-lg">
                        <i class="ph ph-google-logo"></i>
                    </a>
                </li>
            </ul>
        </form>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->

    <!-- Page Specific JS File -->
@endpush
