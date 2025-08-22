<!-- two-step verification -->
@if($authUser->getId() == $user->getId())
    <div class="card">
        <div class="card-header border-bottom pb-0">
            <h4 class="card-title">Two-step verification</h4>
        </div>
        <div class="card-body pt-3 my-2 py-25">
            <!-- 2FA confirmed, we show a 'disable' button to disable it : -->
            @if($authUser->getTwoFactorConfirmed())
                <form action="{{ route('two-factor.disable')}}" method="post">
                    @csrf
                    @method('delete')
                    <h4>Authenticator Apps</h4>
                    <p>
                        Με τον έλεγχο ταυτότητας δύο παραγόντων, έχετε την επιλογή να δημιουργήσετε
                        ένα κλειδί ανάκτησης για να βοηθήσετε στη βελτίωση της ασφάλειας του λογαριασμού.
                    </p>
                    <p>
                        Χρησιμοποιώντας μια εφαρμογή ελέγχου ταυτότητας όπως το Google Authenticator,
                        το Microsoft Authenticator, το Authy ή το 1Password, σαρώστε τον κωδικό QR.
                        Θα δημιουργήσει έναν 6ψήφιο κωδικό που θα τον εισάγετε σε κάθε σας σύνδεση.
                    </p>
                    <div class="d-flex justify-content-center my-2 py-50">
                        {!! $authUser->getTwoFactorQrCodeSvg() !!}
                    </div>
                    <div class="d-flex justify-content-center my-2 py-50">
                        <button type="submit" class="btn btn-danger">
                            Απενεργοποίηση two-factor authentication
                        </button>
                    </div>
                </form>
            @elseif($authUser->getTwoFactorSecret())
                <p class="fw-bolder">Ο έλεγχος ταυτότητας δύο παραγόντων Δεν έχει επιβεβαιωθεί ακόμα.</p>
                <p>
                    Χρησιμοποιώντας μια εφαρμογή ελέγχου ταυτότητας όπως το Google Authenticator,
                    το Microsoft Authenticator, το Authy ή το 1Password, σαρώστε τον κωδικό QR.
                    Θα δημιουργήσει έναν 6ψήφιο κωδικό που θα τον εισάγετε για επιβεβαίωση.
                </p>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#twoFactorAuthModal">
                    Επιβεβαίωση two-factor authentication
                </button>
            @else
                <form method="POST" action="{{ route('two-factor.enable')}}">
                    @csrf
                    <p class="fw-bolder">Ο έλεγχος ταυτότητας δύο παραγόντων Δεν έχει ενεργοποιηθεί ακόμα.</p>
                    <p>
                        Με τον έλεγχο ταυτότητας δύο παραγόντων, έχετε την επιλογή να δημιουργήσετε
                        ένα κλειδί ανάκτησης για να βοηθήσετε στη βελτίωση της ασφάλειας του λογαριασμού.
                    </p>
                    <button type="submit" class="btn btn-primary">
                        Ενεργοποίηση two-factor authentication
                    </button>
                </form>
            @endif
        </div>
    </div>
@endif
<!-- / two-step verification -->
@if($authUser->getTwoFactorConfirmed())
    <div class="col-12 mt-4">
        <!-- api key list -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <span data-bs-toggle="collapse" data-bs-target="#accordionRecoveryKeys" aria-expanded="true" aria-controls="accordionRecoveryKeys">
                        Recovery Keys
                    </span>
                    <span id="download-recovery-keys">
                        <i class="ti ti-download text-primary cursor-pointer" style="height: 2rem; width: 2rem"></i>
                    </span>
                </h4>
                <span class="btn btn-info" data-bs-toggle="collapse" data-bs-target="#accordionRecoveryKeys" aria-expanded="true" aria-controls="accordionRecoveryKeys">
                    Προβολή κλειδιών
                </span>
            </div>
            <div id="accordionRecoveryKeys" class="accordion-collapse collapse card-body" data-bs-parent="#accordionRecoveryKeys">
                <p class="card-text">
                    Αν χρειάζεται να επαναφέρετε το συνθηματικό σας, τότε μπορείτε να χρησιμοποιήσετε τα κλειδιά ανάκτησης
                    για να αποκτήσετε ξανά πρόσβαση στην εφαρμογή. Η χρήση κλειδιού ανάκτησης είναι πιο ασφαλής,
                    όμως σημαίνει ότι εσείς είστε υπεύθυνοι για τη διατήρηση της πρόσβασης στον λογαριασμό σας
                    και στο κλειδί ανάκτησης.
                </p>
                <div class="row gy-2">
                    @foreach (json_decode(decrypt($authUser->getTwoFactorRecoveryCodes())) as $key=>$code)
                        <div class="col-6">
                            <div class="bg-light-secondary position-relative rounded p-2">
                                <div class="d-flex align-items-center flex-wrap ">
                                    <h4 class="text-primary mb-1 font-medium-3"> Recovery Key {{$key+1}}</h4>
                                </div>
                                <h6 class="d-flex align-items-center fw-bolder">
                                    <span id="key_{{$key}}" class="me-50">{{ $code }}</span>
                                </h6>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- / api key list -->
    </div>
@endif
<!--/ two-step verification -->

@if($authUser->getTwoFactorSecret() && $authUser->getId() == $user->getId())
    @include('backend.auth.users.includes.modal-two-factor-auth')
@endif

@push('after-scripts')

    @vite(['resources/assets/js/modal-two-factor-auth.js'])

    <script type="module">
        @if($authUser->getTwoFactorConfirmed() && $authUser->getId() == $user->getId())
            $('#download-recovery-keys').on('click', function () {
                download_file();
            });
            function download_file() {
                let code='';
                let arr={!!  decrypt($authUser->getTwoFactorRecoveryCodes()) !!};
                for(var i=0;i<arr.length;i++){
                    arr[i]="#key "+(i+1)+": "+arr[i];
                }
                code  = arr.join('\r\n\n');

                let file = new File([code], 'recovery_keys.txt', {
                    type: 'text/plain',
                })

                let link = document.createElement('a')
                let url = URL.createObjectURL(file)

                link.href = url
                link.download = file.name
                document.body.appendChild(link)
                link.click()

                document.body.removeChild(link)
                window.URL.revokeObjectURL(url)
            }
        @endif
    </script>
@endpush
