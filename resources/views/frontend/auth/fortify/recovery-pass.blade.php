@extends('frontend.layouts.app')

@section('title', 'Two Step Verification')

@section('vendor-style')
    @vite([
    ])
@endsection

@section('page-style')
    @vite([
      'resources/assets/vendor/scss/pages/page-auth.scss'
    ])
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <div class="auth-wrapper auth-basic px-2">
                <div class="auth-inner my-2">
                    <div class="card mb-0">
                        <div class="card-body">
                            <h2 class="card-title fw-bolder mb-1">Two Step Verification Recovery Pass 💬</h2>
                            <p class="card-text mb-75">
                                Χρησιμοποιώντας έναν απο τους κωδικούς ανάκτησης μπορείτε να έχετε πρόσβαση στον λογαριασμό.
                            </p>
                            <p class="card-text fw-bolder mb-2"> </p>
                            <br>
                            <form id="form" method="POST" action="{{ route('two-factor.login') }}" class="mt-2">
                                @csrf
                                <h6>Πληκτρολογήστε τον κωδικό ανάκτησης για τον λογαριασμό σας.</h6>
                                <div class="auth-input-wrapper d-flex align-items-center justify-content-between">
                                    <input id="recovery_code" type="text" class="form-control @error('code') is-invalid @enderror" name="recovery_code" autocomplete="current-code">
                                </div>
                                <div>
                                    <br>
                                    @error('two_factor_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                                <button type="submit" class="btn btn-primary w-100" tabindex="4">Verify</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('after-scripts')
    <script src="{{asset(mix('vendors/js/forms/cleave/cleave.min.js'))}}"></script>
    <script src="{{asset(mix('js/scripts/pages/auth-two-steps.js'))}}"></script>
@endpush
