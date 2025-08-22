@extends('frontend.layouts.app')

@section('title', __('Please confirm your password before continuing.'))

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
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <div class="card mb-0">
                    <div class="card-body">
                        <a href="{{route('admin.home')}}" class="brand-logo">

                        </a>
                        <h4 class="card-title mb-1">@lang('Please confirm your password before continuing.') 🔒</h4>
                        <form class="auth-forgot-password-form mt-2" action="{{route('password.confirm')}}" method="POST">
                            @csrf
                            <div class="mb-1">
                                <label for="forgot-password-email" class="form-label">@lang('Password')</label>
                                <input type="password" class="form-control" id="password" name="password" aria-describedby="password" tabindex="1" autofocus />
                            </div>
                            <p class="text-center mt-2">
                                <button class="btn btn-primary" type="submit">@lang('Confirm Password')</button>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('vendor-script')
    @vite([
        'resources/assets/vendor/libs/cleavejs/cleave.js',
        'resources/assets/vendor/libs/@form-validation/popular.js',
        'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
        'resources/assets/vendor/libs/@form-validation/auto-focus.js'
    ])
@endsection

@section('page-script')
    @vite([
      'resources/assets/js/pages-auth.js',
      'resources/assets/js/pages-auth-two-steps.js'
    ])
@endsection
