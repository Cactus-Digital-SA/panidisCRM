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
    <style>
        .auth-wrapper.auth-basic .auth-inner {
            max-width: 410px;
        }
    </style>
@endsection
@section('content')
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <div class="card mb-0">
                <div class="card-body">
                    <h2 class="card-title fw-bolder mb-1 text-center">Two Step Verification 💬</h2>
                    <p class="card-text mb-75 text-center">
                        Χρησιμοποιώντας μια εφαρμογή ελέγχου ταυτότητας όπως το Google Authenticator,
                        το Microsoft Authenticator, το Authy ή το 1Password.
                    </p>
                    <p class="card-text fw-bolder mb-2"> </p>
                    <br>
                    <form id="form" method="POST" action="{{ route('two-factor.login') }}" class="mt-2">
                        @csrf
                        <h6 class="text-center">Πληκτρολογήστε τον 6ψήφιο κωδικό ασφαλείας σας</h6>
                        <div class="auth-input-wrapper d-flex align-items-center justify-content-between numeral-mask-wrapper">
                                <input name="two_factor_code[]" type="tel" class="form-control auth-input height-50 text-center numeral-mask mx-1 my-2"
                                       maxlength="1" autofocus="" />

                                <input name="two_factor_code[]" type="tel" class="form-control auth-input height-50 text-center numeral-mask mx-1 my-2"
                                       maxlength="1" />

                                <input name="two_factor_code[]" type="tel" class="form-control auth-input height-50 text-center numeral-mask mx-1 my-2"
                                       maxlength="1" />

                                <input name="two_factor_code[]" type="tel" class="form-control auth-input height-50 text-center numeral-mask mx-1 my-2"
                                       maxlength="1" />

                                <input name="two_factor_code[]" type="tel" class="form-control auth-input height-50 text-center numeral-mask mx-1 my-2"
                                       maxlength="1" />

                                <input name="two_factor_code[]" type="tel" class="form-control auth-input height-50 text-center numeral-mask mx-1 my-2"
                                       maxlength="1" />
                                @error('two_factor_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                        </div>
                        <div class="row col-md-12">
                            <input id="code" type="hidden" class="form-control hidden @error('code') is-invalid @enderror" name="code" autocomplete="current-code">
                        </div>
                        <button type="submit" class="btn btn-primary w-100" tabindex="4">Verify</button>
                    </form>
                    <div class="text-center mt-2">
                        <span>Εάν έχετε κάποιο πρόβλημα με τον 6ψήφιο κωδικό </span>
                        <br>
                        <p class="font-weight-bold text-center">Μπορείτε να συνδεθείτε με τους κωδικούς ανάκτησης</p>
                        <a href="{{ route('two-factor.recovery_pass') }}">
                            <span class="btn btn-outline-primary font-weight-bold">Σύνδεση με κωδικούς ανάκτησης</span>
                        </a>
                        <br>
                        <br>
                        <p class="font-weight-bold text-center">Ή να στείλετε τον 6ψήφιο κωδικό στο email σας.</p>
                        <button type="button" id="send_email" name="send_email" class="btn btn-outline-primary prevent-multiple-submits font-weight-bold">Αποστολή στο Email <i data-feather='send'></i></button>
                    </div>
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

    <script type="module">
        $('.prevent-multiple-submits').click( function () {
            $('.prevent-multiple-submits').attr('disabled','true');
        })

        $('#form').submit(function (e) {
            var value = document.getElementsByName('two_factor_code[]');
            var code  = '';
            for (var i = 0; i < value.length; i++) {
                code+=value[i].value;
            }
            $('#code').val(code);
        });

        $("#send_email").click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('two-factor.send_email') }}",
                success: function(result) {
                    Swal.fire(
                        {
                            timer: 1500,
                            icon: "success",
                            title: 'Έχουμε στείλει το κωδικό στο email σας.',
                            showCancelButton: false,
                            showConfirmButton: false
                        }
                    )
                },
                error: function(result) {
                    $('.prevent-multiple-submits').prop("disabled", false);
                    $('.spinner').hide();
                    Swal.fire({
                        timer: 1500,
                        icon: 'error',
                        title: "Υπήρξε κάποιο πρόβλημα κατά την αποστολή.",
                        showCancelButton: false,
                        showConfirmButton: false
                    })
                }
            });
        });

    </script>

@endsection
