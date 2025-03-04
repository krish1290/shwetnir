@extends('layouts.auth2')
@section('title', __('user.users'))
@push('plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/loader.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/owl-carousel/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/owl-carousel/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/authentication/auth_1.css') }}">
@endpush

@section('content')
    <!-- Main Body Starts -->
    <div class="login-one" style="background-image: url({{asset('uploads/login_logos/'.getLoginPageImage('App\Http\helpers')->login_logo)}});background-size: cover;>
        <div class="container-fluid login-one-container">
            <div class="p-30 h-100">
                <div class="row main-login-one h-100">

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 p-0">
                        <form method="POST" action="{{ route('login') }}" style="height:100%">
                            @csrf
                            <div class="login-one-start">
                                <h6 class="mt-2 text-primary text-center font-20">{{ __('Log In') }}</h6>
                                <p class="text-center text-muted mt-3 mb-3 font-14">{{ __('Please Log into your account') }}
                                </p>
                                <div class="login-one-inputs mt-5">
                                    <input id="username" type="text"
                                        class="form-control @error('username') is-invalid @enderror" name="username"
                                        value="{{ old('username') }}" required autocomplete="username" autofocus>
                                    <i class="las la-user-alt"></i>
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="login-one-inputs mt-3">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">
                                    <i class="las la-lock"></i>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="login-one-inputs check mt-4">
                                    <input class="form-check-input inp-cbx" type="checkbox" style="display: none"
                                        name="remember" id="cbx" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="cbx" for="cbx">
                                        <span>
                                            <svg width="12px" height="10px" viewBox="0 0 12 10">
                                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                            </svg>
                                        </span>
                                        <span class="font-13 text-muted">{{ __('Remember me ?') }}</span>
                                    </label>
                                </div>
                                <div class="login-one-inputs mt-4">
                                    <button class="ripple-button ripple-button-primary btn-lg btn-login" type="submit">
                                        <div class="ripple-ripple js-ripple">
                                            <span class="ripple-ripple__circle"></span>
                                        </div>
                                        {{ __('LOG IN') }}
                                    </button>
                                </div>

                                @if (Route::has('password.request'))
                                    <div class="login-one-inputs mt-4 text-center font-12 strong">
                                        <a href="{{ route('password.request') }}"
                                            class="text-primary">{{ __('Forgot Your Password?') }}</a>
                                    </div>
                                @endif
                                {{-- <div class="login-one-inputs social-logins mt-4">
                                <div class="social-btn"><a href="#" class="fb-btn"><i class="lab la-facebook-f"></i></a></div>
                                <div class="social-btn"><a href="#" class="twitter-btn"><i class="lab la-twitter"></i>
                                    </a></div>
                                <div class="social-btn"><a href="#" class="google-btn"><i class="lab la-google-plus"></i>
                                    </a></div>
                            </div> --}}
                            </div>
                        </form>
                    </div>

                    <div class="col-xl-8 col-lg-6 col-md-6 d-none d-md-block p-0">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Body Ends -->
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/js/loader.js') }}"></script>
    <script src="{{ asset('assets/js/libs/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('plugins/owl-carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('plugins/owl-carousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/authentication/auth_1.js') }}"></script>
@endpush
