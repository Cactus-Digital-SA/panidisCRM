@php
    /**
    * @var \App\Domains\Companies\Models\Company $company
    */
@endphp
@extends('backend.layouts.app')

@section('title', 'Επεξεργασία Εταιρείας')

@section('vendor-style')
    @vite([])
@endsection

@section('page-style')
    @vite([])
@endsection

@section('content-header')
    <div class="content-header-right text-md-end col-md-5 d-md-block d-none mb-2 header-btn">
        <div class="mb-1 breadcrumb-right">
            <div class="col-12 d-flex ms-auto justify-content-end p-0">

            </div>
        </div>
    </div>
@endsection

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Αρχική</a>
    </li>
    <li class="breadcrumb-item">Εταιρείες
    </li>
    <li class="breadcrumb-item active">Επεξεργασία
    </li>
@endsection

@section('content')

    <form id="form" method="POST" action="{{ route('admin.companies.update', $company->getId()) }}" class="form-horizontal needs-validation" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PATCH')
        <div class="card">
            <div class="card-body">
                <div class="form-group row mb-1 mt-1">
                    <label for="erpId" class="col-md-2 col-form-label">ERP ID</label>
                    <div class="col-md-10">
                        <input type="text" name="erpId" class="form-control" placeholder="ERP ID"
                               value="{{ old('erpId', $company->getErpId()) }}" maxlength="100" />
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="name" class="col-md-2 col-form-label">Όνομα <i class="fa fa-asterisk fa-2xs text-danger"></i></label>
                    <div class="col-md-10">
                        <input type="text" name="name" class="form-control" placeholder="{{ 'Όνομα' }}"
                            value="{{ old('name', $company->getName()) }}" maxlength="100" required />
                        <div class="invalid-feedback"> Το Όνομα είναι απαραίτητο. </div>
                    </div>
                </div>
{{--                <div class="form-group row mb-1 mt-1">--}}
{{--                    <label for="activity" class="col-md-2 col-form-label">Email</label>--}}
{{--                    <div class="col-md-10">--}}
{{--                        <input type="email" name="email" class="form-control" placeholder="{{ 'Email' }}"--}}
{{--                               value="{{ old('email',$company->getEmail()) }}"/>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="form-group row mb-1 mt-1">--}}
{{--                    <label for="activity" class="col-md-2 col-form-label">Τηλέφωνο</label>--}}
{{--                    <div class="col-md-10">--}}
{{--                        <input type="text" name="phone" class="form-control" placeholder="Τηλέφωνο" value="{{ old('phone', $company->getPhone()) }}" />--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="form-group row mb-1 mt-1">--}}
{{--                    <label for="activity" class="col-md-2 col-form-label">Δραστηριότητα</label>--}}
{{--                    <div class="col-md-10">--}}
{{--                        <input type="text" name="activity" class="form-control" placeholder="{{ 'Δραστηριότητα' }}"--}}
{{--                            value="{{ old('activity', $company->getActivity()) }}" maxlength="255"/>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="form-group row mb-1 mt-1">
                    <label for="typeId" class="col-md-2 col-form-label">Κατηγορία Πελάτη <i class="fa fa-asterisk fa-2xs text-danger"></i></label>
                    <div class="col-md-10">
                        <select name="typeId" id="typeId" class="form-control select2" data-placeholder="Επιλέξτε κατηγορία πελάτη" data-allow-clear="true" required>
                            <option value=""></option>
                            @foreach($types ?? [] as $type)
                                <option value="{{$type->getId()}}" @if($company->getTypeId() == $type->getId()) selected @endif>{{ $type->getName() }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"> Η κατηγορία πελάτη είναι απαραίτητη. </div>
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="sector" class="col-md-2 col-form-label">Τομέας</label>
                    <div class="col-md-10">
                        <select name="sector" id="sector" class="form-control select2" data-placeholder="Επιλέξτε τομέα" data-allow-clear="true">
                            <option value=""></option>
                            @foreach($sectors ?? [] as $sector)
                                <option value="{{$sector->getId()}}" >{{$sector->getName()}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"> Η ανάθεση τομέα είναι απαραίτητη. </div>
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="sourceId" class="col-md-2 col-form-label">Source Channel <i class="fa fa-asterisk fa-2xs text-danger"></i></label>
                    <div class="col-md-10">
                        <select name="sourceId" id="sourceId" class="form-control select2" data-placeholder="Επιλέξτε πηγή" data-allow-clear="true" required>
                            <option value=""></option>
                            @foreach($sources ?? [] as $sourceChannel)
                                <option value="{{$sourceChannel->getId()}}" @if($company->getSourceId() == $sourceChannel->getId()) selected @endif >{{$sourceChannel->getName()}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"> Η επιλογή πηγής είναι απαραίτητη. </div>
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="countryId" class="col-md-2 col-form-label">Χώρα</label>
                    <div class="col-md-10">
                        <select name="countryId" id="countryId" class="form-control select2" data-placeholder="{{ 'Χώρα' }}" data-allow-clear="true">
                            <option></option>
                            @foreach($countries ?? [] as $country)
                                <option value="{{ $country->getId() }}" {{ $company->getCountryId() == $country->getId()  ? 'selected' : '' }}>
                                    {{ $country->getName() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="countryId" class="col-md-2 col-form-label">Πόλη</label>
                    <div class="col-md-10">
                        <input type="text" name="city" id="city" class="form-control" placeholder="Πόλη" value="{{ old('city', $company->getCity()) }}">
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="website" class="col-md-2 col-form-label">Website</label>
                    <div class="col-md-10">
                        <input type="text" name="website" class="form-control" placeholder="{{ 'Website' }}"
                               value="{{ old('website', $company->getWebsite()) }}" maxlength="255" />
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="website" class="col-md-2 col-form-label">Linkedin</label>
                    <div class="col-md-10">
                        <input type="text" name="linkedin" class="form-control" placeholder="{{ 'Linkedin' }}"
                               value="{{ old('website', $company->getLinkedin()) }}" maxlength="255" />
                    </div>
                </div>
                <div class="col-12 text-center mt-2 pt-50">
                    <button type="submit" class="btn btn-primary me-1">
                        Αποθήκευση <i class="ms-2 ti ti-check ti-xs"></i>
                    </button>
                </div>
            </div>
        </div>

    </form>


@endsection

@section('modals')

@endsection

@section('vendor-script')

@endsection

@section('page-script')
    <script type="module">
        let mySelect = $('.doy-select');
        mySelect.select2(
            {
                placeholder: 'Επιλέξτε ΔΟΥ',
                dropdownParent: mySelect.parent()
            }
        );
    </script>
@endsection
