@extends('frontend/layouts/app')

@section('title', 'Επαναφορά Κωδικού Πρόσβασης')

@section('vendor-style')
    @vite([
    ])
@endsection

@section('page-style')
    @vite([
      'resources/assets/vendor/scss/pages/page-auth.scss'
    ])
    <style>
        .input-group {
            box-shadow: none !important;
        }
        .input-group.has-validation > .form-control:first-child{
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }
        .input-group-merge .form-control:not(:last-child) {
            border-right: 1px solid var(--bs-border-color);
        }
    </style>
@endsection


@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Forgot Password basic -->
                <div class="card mb-0">
                    <div class="card-body">
                        <h1 class="card-title mb-1 font-medium-1">🔒 Επαναφορά κωδικού</h1>
                        <p class="card-text mb-2 fsc-2">{{ __('locale.ForgotPasswordParagraph') }}</p>

                        @if (session('status'))
                            <div class="mb-1 text-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form class="auth-forgot-password-form mt-4 mb-3" id="formAuthentication" method="POST"
                              action="{{ route('password.email') }}">
                            @csrf
                            <label for="email" class="form-label fsc-4">Email</label>
                            <div class="mb-2 input-group input-group-merge">
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" autofocus>
                                @error('email')
                                <span class="invalid-feedback" id="emailInvalidFeedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror('email')
                            </div>
                            @error('recaptcha')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                            <button type="submit" class="btn btn-primary btn-submit w-100 mb-2 mt-4 fsc-4 fw-bold" id="btn-submit"
                                    tabindex="2">{{ __('locale.Reset Link') }}</button>
                        </form>
                        <p class="text-center mt-0">

                            @if (Route::has('login'))
                                <a class="fsc-2" href="{{ route('login') }}"> <i
                                        data-feather="chevron-left fsc-2"></i> Σύνδεση </a>
                            @endif
                        </p>
                    </div>
                </div>
                <!-- /Forgot Password basic -->
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {

            const emailInput = document.getElementById("email");
            const emailFeedback = document.getElementById("emailInvalidFeedback");

            if (emailInput) {
                emailInput.addEventListener("input", function () {
                    if (emailFeedback) {
                        emailFeedback.classList.add("d-none");
                    }
                });
            }
        });
    </script>

    @vite([
      'resources/assets/js/pages-auth.js'
    ])
@endsection
