@php
    /** @var App\Domains\Auth\Models\User $user */
@endphp
<!-- form -->
<form id="form" method="post" action="{{ $changePasswordUrl ?? '#' }}#{{ request()->get('tab', 'navs-pills-top-security') }}" class="validate-form" target="_parent">
    @csrf
    @method('PATCH')
    <!-- change password -->
    <div class="card">
        <div class="card-header border-bottom pb-0">
            <h4 class="card-title">Αλλαγή κωδικού</h4>
            <p>({{$user->getName()}})</p>
        </div>
        <div class="card-body pt-3">
            <div class="row">
                <div class="col-12 col-sm-12 mb-1">
                    <label class="form-label" for="account-old-password">Νέος κωδικός</label>
                    <div class="input-group form-password-toggle input-group-merge">
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Νέος κωδικός" maxlength="100" required autocomplete="new-password"/>
                        <div class="input-group-text cursor-pointer">
                            <i data-feather="eye"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12 mb-1">
                    <label class="form-label" for="account-new-password">Επιβεβαίωση κωδικού</label>
                    <div class="input-group form-password-toggle input-group-merge">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-control" placeholder="Επιβεβαίωση κωδικού" maxlength="100"
                               required autocomplete="new-password" />
                        <div class="input-group-text cursor-pointer">
                            <i data-feather="eye"></i>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <p class="fw-bolder">Απαιτήσεις Κωδικού:</p>
                    <ul class="ps-1 ms-25">
                        <li class="mb-50">Τουλάχιστον 8 χαρακτήρες</li>
                        <li class="mb-50">Τουλάχιστον ένα πεζό χαρακτήρα</li>
                        <li>Τουλάχιστον ένα αριθμό ή σύμβολο</li>
                    </ul>
                </div>
                @if(isset($changePasswordUrl))
                <div class="col-12">
                    <button class="btn btn-primary me-1 mt-1" type="submit">Αποθήκευση αλλαγών</button>
                </div>
                @endif
            </div>
        </div>
    </div>
</form>
<!--/ form -->
