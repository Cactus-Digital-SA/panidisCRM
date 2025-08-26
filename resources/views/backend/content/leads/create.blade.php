@extends('backend.layouts.app')

@section('title', 'Δημιουργία Lead')

<!-- Vendor Style -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/tagify/tagify.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss'])
@endsection

@section('page-style')
    @vite(['resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss'])
@endsection

@push('after-styles')
    <style>
    </style>
@endpush

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"> <a href="{{ route('admin.home') }}" class="">Αρχική</a> </li>
    <li class="breadcrumb-item"> <a href="{{ route('admin.clients.index') }}" class="">Πελάτες</a> </li>
    <li class="breadcrumb-item active">Δημιουργία</li>
@endsection

@section('content')
        <form id="leadsForm" method="POST" action="{{ route('admin.leads.store') }}" class="form-horizontal needs-validation" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="card">
                <div class="card">
                    <div class="card-body">
                        <div class="row form-group mb-1 mt-1">
                            <div class="col-md-12">
                                <label for="companyName" class="form-label">Όνομα <i class="fa fa-asterisk fa-2xs text-danger"></i></label>
                                <input name="companyName" id="companyName" class="form-control" placeholder="Όνομα εταιρείας" required>
                                <div class="invalid-feedback"> Το Όνομα είναι απαραίτητο. </div>
                            </div>
                        </div>
                        <div class="form-group row mb-4 mt-4">
                            <div class="col-md-6">
                                <label for="typeId" class="form-label">Κατηγορία Πελάτη <i class="fa fa-asterisk fa-2xs text-danger"></i></label>
                                <select name="typeId" id="typeId" class="form-control select2" data-placeholder="Επιλέξτε κατηγορία πελάτη" data-allow-clear="true" required>
                                    <option value=""></option>
                                    @foreach($types ?? [] as $type)
                                        <option value="{{$type->getId()}}" >{{$type->getName()}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"> Η κατηγορία πελάτη είναι απαραίτητη. </div>
                            </div>
                            <div class="col-md-6">
                                <label for="salesPersonId" class="form-label">Ανάθεση πωλητή <i class="fa fa-asterisk fa-2xs text-danger"></i></label>
                                <select name="salesPersonId" id="salesPersonId" class="form-control select2 salesPersons" data-placeholder="Επιλέξτε πωλητή" data-allow-clear="true" required>
                                    <option value=""></option>
                                    @foreach($salesPersons ?? [] as $salesPerson)
                                        <option value="{{$salesPerson->getId()}}" >{{$salesPerson->getName()}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"> Η ανάθεση πωλητή είναι απαραίτητη. </div>
                            </div>

                        </div>
                        <div class="form-group row mb-4 mt-4">
                            <div class="col-md-6">
                                <label for="sector" class="form-label">Τομέας</label>
                                <select name="sector" id="sector" class="form-control select2" data-placeholder="Επιλέξτε τομέα" data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach($sectors ?? [] as $sector)
                                        <option value="{{$sector->getId()}}" >{{$sector->getName()}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"> Η ανάθεση τομέα είναι απαραίτητη. </div>
                            </div>
                            <div class="col-md-6">
                                <label for="sourceId" class="form-label">Source Channel <i class="fa fa-asterisk fa-2xs text-danger"></i></label>
                                <select name="sourceId" id="sourceId" class="form-control select2" data-placeholder="Επιλέξτε πηγή" data-allow-clear="true" required>
                                    <option value=""></option>
                                    @foreach($sources ?? [] as $sourceChannel)
                                        <option value="{{$sourceChannel->getId()}}" >{{$sourceChannel->getName()}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"> Η επιλογή πηγής είναι απαραίτητη. </div>
                            </div>
                        </div>
                        <div class="form-group row mb-4 mt-4">
                            <div class="col-md-6">
                                <label for="countryId" class="form-label">Χώρα <i class="fa fa-asterisk fa-2xs text-danger"></i></label>
                                <select name="countryId" id="countryId" class="form-control select2" data-placeholder="Επιλέξτε Χώρα" data-allow-clear="true" required>
                                    <option></option>
                                    @foreach($countries ?? [] as $country)
                                        <option value="{{ $country->getId() }}">
                                            {{ $country->getName() }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"> Η επιλογή χώρας είναι απαραίτητη. </div>
                            </div>

                            <div class="col-md-6">
                                <label for="city" class="form-label">Πόλη</label>
                                <input type="text" name="city" id="city" class="form-control" placeholder="Πόλη">
                            </div>
                        </div>
                        <div class="form-group row mb-4 mt-4">
                            <div class="col-md-6">
                                <label for="website" class="form-label">Website</label>
                                <input type="text" name="website" id="website" class="form-control" placeholder="Website" value="{{ old('website') }}" maxlength="255" />
                            </div>
                            <div class="col-md-6">
                                <label for="linkedin" class="form-label">Linkedin</label>
                                <input type="text" name="linkedin" id="linkedin" class="form-control" placeholder="Linkedin" value="{{ old('linkedin') }}" maxlength="255" />
                            </div>
                        </div>
                        <div class="form-group row mb-3 mt-3 align-items-end">
                            <div class="col-md-6">
                                <label for="typeId" class="form-label">Tags</label>
                                <select name="productTags[]" id="productTags" class="form-control select2" data-placeholder="Επιλέξτε tags" data-allow-clear="true" multiple>
                                    <option value=""></option>
                                    @foreach($productTags ?? [] as $productTag)
                                        <option value="{{$productTag->getId()}}" >{{$productTag->getName()}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-end">
                                <label for="user" class="form-label">Επαφές</label>
                                <a data-bs-target="#add-user" data-bs-toggle="modal" class="btn btn-sm btn-primary text-white">Δημιουργία Επαφής</a>
                                </div>
                                <select name="userIds[]" id="user" class="form-select select2 user-select" multiple data-allow-clear="true">
                                    <option value="" disabled="disabled">Επιλογή Επαφής</option>
                                    @foreach($users ?? [] as $user)
                                        <option value="{{$user->getId()}}">
                                            {{$user->getName()}}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                        </div>

                        <div class="col-12 text-center mt-5 pt-50">
                            <button type="submit" class="btn btn-primary me-1">Προσθήκη <i class="fa fa-check ms-2"></i></button>
                        </div>
                    </div>
                </div>
            </div>
    </form>

@endsection

@section('modals')
    <div class="modal fade" id="add-user" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-add-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h3 class="mb-1">Δημιουργία Επαφής</h3>
                    </div>
                    <form action="{{route('admin.companies.contacts.create')}}" id="add-user-form" method="post" class="row gy-1 pt-75">
                        @csrf()
                        <div class="col-12">
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
                                    <div class="invalid-feedback" id="duplicate-email"> Το Email είναι απαραίτητο. </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1 mt-1">
                                <label for="email" class="col-md-2 col-form-label">Τηλέφωνο</label>
                                <div class="col-md-10">
                                    <input type="text" name="phone" class="form-control" placeholder="Τηλέφωνο" value="{{ old('phone') }}" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1">Προσθήκη <i class="fa fa-check ms-2"></i></button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                Άκυρο
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Vendor Script -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/tagify/tagify.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js'])
@endsection

<!-- Page Script -->
@section('page-script')
    @vite(['resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js'])
    <script type="module">
        $(document).ready(function () {
            $('.enable-tag').select2({
                tags: true,
            });

            let userSelect = $('.user-select');
            userSelect.each(function () {
                let $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Αναζήτηση...',
                    allowClear: true,
                    dropdownParent: $this.parent(),
                    ajax: {
                        type: 'POST',
                        delay: 500,
                        url: "{{ route('api.internal.contacts.namesPaginated') }}",
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        data: function (params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1
                            }
                        },
                        // processResults: function (data, params) {
                        //     return data
                        // },
                        processResults: function (data) {
                            return {
                                results: $.map(data.results, function (obj) {
                                    return {id: obj.id, text: obj.text}; // Use id and name
                                })
                            };
                        },
                        cache: true
                    }
                });
            });
        });
    </script>
@endsection

