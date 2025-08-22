@extends('frontend/layouts/app')

@section('title', 'Login')

@section('vendor-style')
    @vite([
      'resources/assets/vendor/libs/@form-validation/form-validation.scss'
    ])
@endsection

@section('page-style')
    @vite([
      'resources/assets/vendor/scss/pages/page-auth.scss'
    ])
@endsection

@section('content')
    <div class="container-xxl">

        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Login -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <a href="#" class="app-brand-link gap-2" style="width: 60%;">
                                <img src="{{ Vite::asset('resources/images/logo/Logo_Black.svg') }}" alt class="h-auto">
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="card-title mb-1">{{__('locale.Welcome')}} 👋</h4>
                        <p class="card-text mb-2">{{__('locale.SignParagraph')}}</p>
                        @if (session('status'))
                            <div class="alert alert-success mb-1 rounded-0" role="alert">
                                <div class="alert-body">
                                    {{ session('status') }}
                                </div>
                            </div>
                        @endif
                        <form class="auth-login-form mt-2" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                       placeholder="Enter your email" autofocus>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">{{__('locale.Password')}}</label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}">
                                            <small>{{__('locale.Forgot Password')}}</small>
                                        </a>
                                    @endif
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                           aria-describedby="password" />
                                    <span id="toggle-password" class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember-me">
                                        {{__('locale.Remember Me')}}
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">{{__('locale.Sign in')}}</button>
                            </div>
                        </form>

    {{--                    <div class="divider my-4">--}}
    {{--                        <div class="divider-text">Follow Us</div>--}}
    {{--                    </div>--}}

    {{--                    <div class="auth-footer-btn d-flex justify-content-center">--}}
    {{--                        <a href="javascript:;" class="btn btn-icon btn-label-facebook me-3">--}}
    {{--                            <i class="tf-icons fa-brands fa-facebook-f fs-5"></i>--}}
    {{--                        </a>--}}
    {{--                        <a href="javascript:;" class="btn btn-icon btn-label-google-plus me-3">--}}
    {{--                            <i class="tf-icons fa-brands fa-google fs-5"></i>--}}
    {{--                        </a>--}}
    {{--                        <a href="javascript:;" class="btn btn-icon btn-label-twitter">--}}
    {{--                            <i class="tf-icons fa-brands fa-twitter fs-5"></i>--}}
    {{--                        </a>--}}
    {{--                    </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    @vite([
      'resources/assets/vendor/libs/@form-validation/popular.js',
      'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
      'resources/assets/vendor/libs/@form-validation/auto-focus.js'
    ])
@endsection

@section('page-script')

    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.querySelector('#toggle-password');
            const passwordField = document.querySelector('#password');

            togglePassword.addEventListener('click', function () {
                // Toggle the type attribute
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);

                // Toggle the eye icon
                this.querySelector('i').classList.toggle('ti-eye');
                this.querySelector('i').classList.toggle('ti-eye-off');
            });

            const emailInput = document.getElementById("email");
            const emailFeedback = document.getElementById("emailInvalidFeedback");

            if (emailInput) {
                emailInput.addEventListener("input", function () {
                    if (emailFeedback) {
                        emailFeedback.classList.add("d-none");
                    }
                });
            }

            const passwordInput = document.getElementById("password");
            const passwordFeedback = document.getElementById("passwordInvalidFeedback");

            if (passwordInput) {
                passwordInput.addEventListener("input", function () {
                    if (passwordFeedback) {
                        passwordFeedback.classList.add("d-none");
                    }
                });
            }

        });
    </script>

    @vite([
      'resources/assets/js/pages-auth.js'
    ])
@endsection
