@extends('backend.layouts.app')

@section('title', 'Δημιουργία Χρήστη')

@section('content')
    <form id="form" method="POST" action="{{ route('admin.users.store') }}" class="form-horizontal">
        @csrf
        <div class="card">
            <div class="card-header">
                @lang('Create User')
                <div class="card-header-actions">
                    <a class="card-header-action btn btn-warning"
                       href="{{route('admin.users.index')}}">
                        <i data-feather='arrow-left'></i> {{__('Cancel')}}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row mb-1 mt-1">
                    <label for="name" class="col-md-2 col-form-label">Όνομα</label>
                    <div class="col-md-10">
                        <input type="text" name="firstName" class="form-control" placeholder="Όνομα" value="{{ old('firstName') }}" maxlength="100" required />
                        <div class="invalid-feedback"> Το Όνομα είναι απαραίτητο. </div>
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="name" class="col-md-2 col-form-label">Επώνυμο</label>
                    <div class="col-md-10">
                        <input type="text" name="lastName" class="form-control" placeholder="Επώνυμο" value="{{ old('lastName') }}" maxlength="100" required />
                        <div class="invalid-feedback"> Το Επώνυμο είναι απαραίτητο. </div>
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="email" class="col-md-2 col-form-label">@lang('E-mail Address')</label>
                    <div class="col-md-10">
                        <input type="email" name="email" class="form-control" placeholder="{{ __('E-mail Address') }}" value="{{ old('email') }}" maxlength="255" required />
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="password" class="col-md-2 col-form-label">@lang('Password')</label>
                    <div class="col-md-10">
                        <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Password') }}" maxlength="100" required autocomplete="new-password" />
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="password_confirmation" class="col-md-2 col-form-label">@lang('Password Confirmation')</label>
                    <div class="col-md-10">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('Password Confirmation') }}" maxlength="100" required autocomplete="new-password" />
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="active" class="col-md-2 col-form-label">@lang('Active')</label>
                    <div class="col-md-10">
                        <div class="form-check">
                            <input name="active" id="active" class="form-check-input" type="checkbox" value="1" {{ old('active', true) ? 'checked' : '' }} />
                        </div>
                    </div>
                </div>

                <div x-data="{ emailVerified : false }">
                    <div class="form-group row mb-1 mt-1">
                        <label for="email_verified" class="col-md-2 col-form-label">@lang('E-mail Verified')</label>

                        <div class="col-md-10">
                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    name="email_verified"
                                    id="email_verified"
                                    value="1"
                                    class="form-check-input"
                                    {{ old('email_verified') ? 'checked' : '' }} />
                            </div><!--form-check-->
                        </div>
                    </div><!--form-group-->

                </div>
                @include('backend.content.extraData.components.extraData', ['extraData' => $extraData])

                @include('backend.auth.includes.roles')

                @include('backend.auth.includes.permissions')
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col text-end">
                        <button class="btn btn-primary float-right" type="submit">Δημιουργία Χρήστη</button>
                    </div><!--row-->
                </div><!--row-->
            </div>
        </div>
    </form>


@endsection

@push('after-scripts')
    <script type="module">

    </script>
@endpush
