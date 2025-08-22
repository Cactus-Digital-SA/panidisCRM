<!-- add authentication apps modal -->
<div class="modal fade" id="twoFactorAuthModal" tabindex="-1" aria-labelledby="twoFactorAuthTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg two-factor-auth-apps">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 mx-50">
                <h1 class="text-center mb-2 pb-50" id="twoFactorAuthAppsTitle">Add Authenticator App</h1>

                <h4>Authenticator Apps</h4>
                <p>
                    Χρησιμοποιώντας μια εφαρμογή ελέγχου ταυτότητας όπως το Google Authenticator,
                    το Microsoft Authenticator, το Authy ή το 1Password, σαρώστε τον κωδικό QR.
                    Θα δημιουργήσει έναν 6ψήφιο κωδικό που θα τον εισάγετε για επιβεβαίωση.
                </p>

                <div class="d-flex justify-content-center my-2 py-50">
                    <!-- 2FA enabled, we display the QR code : -->
                    @if(auth()->user()->two_factor_secret)
                        {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        <!-- 2FA not enabled, we show an 'enable' button  : -->
                    @else
                        <form action="/user/two-factor-authentication" method="post">
                            @csrf
                            <button type="submit">Activate 2FA</button>
                        </form>
                    @endif
                </div>

                <form class="row gy-1" action="{{route('fortify.two-factor.confirm')}}" method="post">
                    @csrf
                    <div class="col-12">
                        <input class="form-control" name="code" id="authenticationCode" placeholder="Enter authentication code" required/>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-secondary mt-2 me-1" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary mt-2">
                            <span class="me-50">Verify</span>
                            <i data-feather="chevron-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- / add authentication apps modal-->
