@php
    /**
    * @var App\Domains\Auth\Models\User $user
    * */
@endphp
@extends('backend.layouts.app')

@section('title', 'Ενημέρωση Χρήστη')

@section('content-header')
    <div class="col-xl-12">
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-top-details" aria-controls="navs-pills-top-details"
                            aria-selected="true"><i class="ti-xs ti ti-users me-1"></i> {{__('Details')}}</button>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{Route::is('admin.users.change-password', $user->getId()) ? 'active' : ''}}" href="{{route('admin.users.change-password',$user->getId())}}">
                        <i class="ti-xs ti ti-lock me-1"></i> Security
                    </a>
                </li>
{{--                <li class="nav-item">--}}
{{--                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"--}}
{{--                            data-bs-target="#navs-pills-top-security" aria-controls="navs-pills-top-security"--}}
{{--                            aria-selected="true"> <i class="ti-xs ti ti-lock me-1"></i> Security</button>--}}
{{--                </li>--}}
                @foreach($user->getMorphables() as $morph)
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-pills-{{$morph->value}}"
                                aria-controls="navs-pills-top-{{$morph->value}}"
                                aria-selected="false">{{ __(Str::ucfirst($morph->value))}}</button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="tab-content p-0">
        <div class="tab-pane fade show active" id="navs-pills-top-details" role="tabpanel">
            <div class="row">
                <div class="col-md-12">
                    <form id="form" method="POST" action="{{ route('admin.users.update',$user->getId()) }}" class="form-horizontal">
                        @csrf
                        @method('PATCH')
                        <div class="row justify-content-center mt-4">
                            <!-- User Sidebar -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">

                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row mb-1 mt-1">
                                            <label for="name" class="col-md-2 col-form-label">Όνομα</label>
                                            <div class="col-md-10">
                                                <input type="text" name="firstName" class="form-control" placeholder="Όνομα" value="{{ old('firstName') ?? $user?->getUserDetails()?->getFirstName() }}" maxlength="100" required />
                                                <div class="invalid-feedback"> Το Όνομα είναι απαραίτητο. </div>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-1 mt-1">
                                            <label for="name" class="col-md-2 col-form-label">Επώνυμο</label>
                                            <div class="col-md-10">
                                                <input type="text" name="lastName" class="form-control" placeholder="Επώνυμο" value="{{ old('lastName') ?? $user?->getUserDetails()?->getLastName() }}" maxlength="100" required />
                                                <div class="invalid-feedback"> Το Επώνυμο είναι απαραίτητο. </div>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-1 mt-1">
                                            <label for="email" class="col-md-2 col-form-label">@lang('E-mail Address')</label>
                                            <div class="col-md-10">
                                                <input type="email" name="email" class="form-control" placeholder="{{ __('E-mail Address') }}" value="{{ old('email') ?? $user->getEmail() }}" maxlength="255" required />
                                                <div class="invalid-feedback"> Το Email είναι απαραίτητο. </div>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-1 mt-1">
                                            <label for="email" class="col-md-2 col-form-label">Τηλέφωνο</label>
                                            <div class="col-md-10">
                                                <input type="text" name="phone" class="form-control" placeholder="Τηλέφωνο" value="{{ $user?->getUserDetails()?->getPhone() }}" />
                                            </div>
                                        </div>
                                        <div class="form-group row mb-1 mt-1">
                                            <label for="email" class="col-md-2 col-form-label">Επιβεβαίωση Τηλεφώνου</label>
                                            <div class="col-md-10">
                                                <input type="checkbox" name="phoneConfirmed" id="phoneConfirmed" value="1" class="font-small-4 form-check-input ms-2" {{ $user?->getUserDetails()?->getPhoneConfirmed() ? 'checked' : '' }} />
                                            </div>
                                        </div>
                                        <div class="form-group row mb-1 mt-1">
                                            <label for="email" class="col-md-2 col-form-label">Επιβεβαίωση Email</label>
                                            <div class="col-md-10">
                                                <input type="checkbox" name="emailVerified" id="emailVerified" value="1" class="font-small-4 form-check-input ms-2" {{ $user?->getEmailVerifiedAt() ? 'checked' : '' }} />
                                            </div>
                                        </div>
                                        <div class="form-group row mb-1 mt-1">
                                            <label for="email" class="col-md-2 col-form-label">Ημ/νια Γέννησης</label>
                                            <div class="col-md-10">
                                                <input type="text" id="birthday" name="birthday" class="form-control flatpickr-basic"
                                                       placeholder="DD-MM-YYYY" autocomplete="off" value="{{ $user?->getUserDetails()?->getBirthday()?->format('d-m-Y') }}"
                                                />
                                            </div>
                                        </div>

                                        @include('backend.content.extraData.components.extraData', ['model' => $user, 'extraData' => $extraData])

                                        @include('backend.auth.includes.roles')

                                        @include('backend.auth.includes.permissions')
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col text-end">
                                                <button class="btn btn-primary float-right" type="submit">Ενημέρωση Χρήστη</button>
                                            </div><!--row-->
                                        </div><!--row-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

{{--        <div class="tab-pane fade" id="navs-pills-top-security" role="tabpanel">--}}
{{--            <div class="row">--}}
{{--                <?php--}}
{{--                    $changePasswordUrl = route('profile.change-password.update', $user->getId());--}}
{{--                    if(Auth::user()->hasRole('Administrator'))--}}
{{--                        $changePasswordUrl = route('admin.users.change-password.update', $user->getId());--}}
{{--                ?>--}}
{{--                <div class="col-md-6">--}}
{{--                    @include('backend.auth.users.includes.change-password', ['changePasswordUrl' => $changePasswordUrl])--}}
{{--                </div>--}}
{{--                <div class="col-md-6">--}}
{{--                    @include('backend.auth.users.includes.2fa_settings')--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}


        @foreach($user->getMorphables() as $morph)
            <div class="tab-pane fade" id="navs-pills-{{$morph->value}}" role="tabpanel">
                <div class="pb-3">
                    <x-morphs.morph morph="{{ $morph->value }}" :model="$user"/>
                </div>
            </div>
        @endforeach
    </div>



@endsection

@push('after-scripts')
    <script type="module">

    </script>
@endpush
