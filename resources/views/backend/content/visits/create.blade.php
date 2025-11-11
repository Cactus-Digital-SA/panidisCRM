@php
    /**
     * @var \App\Domains\Tickets\Models\Ticket $ticket
     * @var \App\Domains\Companies\Models\Company $company
    **/
@endphp
@extends('backend.layouts.app')

@section('title', 'Δημιουργία Επίσκεψης')

@section('page-style')
    <style>

    </style>
@endsection

@section('content-header-breadcrumbs')
{{--    <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Αρχική</a></li>--}}
{{--    <li class="breadcrumb-item"><a href="{{ route('admin.visits.index') }}">Visits</a></li>--}}
{{--    <li class="breadcrumb-item active"> {{ __('Create') }}</li>--}}
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 container-p-y container-fluid">
            <form id="form" method="POST" action="{{ route('admin.visits.store') }}" class="form-horizontal needs-validation" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="card">
                    <div class="card-body">

                        <div class="form-group row mb-3 mt-1">
                            <div class="col-lg-6 ">
                                <label for="company_id" class="col-form-label">Θέμα επίσκεψης <small class="text-danger"> *</small></label>
                                <div class="col-md-12">
                                    <input type="text" name="name" class="form-control" placeholder="Θέμα επίσκεψης" maxlength="255" required/>
                                    <div class="invalid-feedback">Το θέμα επίσκεψης είναι απαραίτητο.</div>
                                </div>
                            </div>

                            <div class="col-lg-6 ">
                                <label for="company_id" class="col-form-label">Εταιρεία <small class="text-danger">*</small></label>
                                <div class="col-md-12">
                                    <select name="company_id" id="company_id" class="form-control companies_select" data-placeholder="{{ 'Εταιρεία' }}" required>
                                    </select>
                                    <div class="invalid-feedback">Η εταιρεία είναι απαραίτητη.</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <div class="col-lg-6">
                                <label for="visit_date" class="col-form-label">Ημ/νια επίσκεψης <small class="text-danger">*</small></label>
                                <div class="col-md-12">
                                    <input type="text" name="visit_date" id="visit_date" placeholder="dd-mm-yyyy" autocomplete="off" class="form-control datepicker" required>
                                    <div class="invalid-feedback">Ημ/νια επίσκεψης είναι απαραίτητη.</div>
                                </div>
                            </div>

                            <div class="col-lg-6 ">
                                <label for="visit_type" class="col-form-label">Τύπος επίσκεψης</label>
                                <div class="col-md-12">
                                    <select id="visit_type" name="visit_type" class="select2 form-select" data-placeholder="Τύπος επίσκεψης" data-allow-clear="true">
                                        <option></option>
                                        @foreach(\App\Domains\Visits\Enums\VisitTypeSourceEnum::cases() as $type)
                                            <option value="{{ $type->value }}"> {{ $type->value }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <div class="col-lg-6">
                                <label for="products_discussed" class="col-form-label">Προϊόν συζήτησης</label>
                                <div class="col-md-12">
                                    <select name="products_discussed" id="products_discussed" class="form-control select2" data-placeholder="Προϊόν συζήτησης">
                                        <option></option>
                                        @foreach(\App\Domains\Visits\Enums\VisitProductDiscussedSourceEnum::cases() as $productDiscussed)
                                            <option
                                                value="{{ $productDiscussed->value }}"> {{ $productDiscussed->value }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 ">
                                <label for="contacts" class="col-form-label">Επαφή επικοινωνίας</label>
                                <div class="col-md-12">
                                    <select id="contacts" name="contacts[]" class="form-select select2 select_contacts" data-placeholder="Επαφή επικοινωνίας" data-allow-clear="true" multiple>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <div class="col-lg-6">
                                <label for="outcome" class="col-form-label">Outcome</label>
                                <div class="col-md-12">
                                    <input type="text" name="outcome" id="outcome" autocomplete="off" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="next_action" class="col-form-label">Επόμενο Action</label>
                                <div class="col-md-12">
                                    <select name="next_action" id="next_action" class="form-control select2" data-placeholder="Επόμενο Action">
                                        <option></option>
                                        @foreach(\App\Domains\Visits\Enums\VisitNextActionSourceEnum::selectableCases() as $action)
                                            <option value="{{ $action->value }}"> {{ $action->value }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3 mt-1">
                            <div class="col-lg-6">
                                <label aria-label="Assignees" class="form-label">@lang('Assignees')</label>
                                <select type="text" name="assignees[]" class="form-control select2 filter_assignees" data-placeholder="{{ __('Assignees') }}" multiple>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3 pt-3 align-items-center">
                            <label for="note" class="col-md-2 col-form-label">Προσθήκη σχολίου</label>
                            <div class="col-md-12">
                                <textarea class="form-control autosize" name="note" id="note" rows="3" placeholder="Προσθήκη σχολίου"></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1 align-items-center">
                            <label for="note" class="col-md-2 col-form-label"><i class="ti ti-paperclip cursor-pointer me-1"></i> Επιλογή Αρχείου</label>
                            <div class="col-md-12">
                                <div id="file-upload-container" class="form-control">
                                    <div class="wrapper row ">
                                        <div class="col-auto px-1">
                                            <label for="file-upload" class="file-input-label">Επιλογή Αρχείου <i
                                                    class="ti ti-file"></i> </label>
                                            <input id="file-upload" type="file" name="files[]" multiple>
                                        </div>
                                        <div class="col-auto d-flex align-items-center justify-content-center px-1">
                                            <div id="file-name" class="file-name ">Δεν έχει επιλεχθεί αρχείο</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col text-end">
                                <button class="btn btn-primary float-right" type="submit">{{__('Save')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('vendor-script')
    @vite([
      'resources/assets/vendor/libs/autosize/autosize.js',
    ])
@endsection

@section('page-script')
    <script type="module">
        $(document).ready(function () {
            $('#visit_date').each(function (i, date) {
                date.flatpickr({
                    minDate: new Date(new Date().setDate(new Date().getDate() - 2)),
                    locale: 'gr',
                    altInput: true,
                    altFormat: 'd-m-Y',
                    dateFormat: 'Y-m-d',
                })
            });


            const fileInput = document.getElementById('file-upload');
            const fileNameDisplay = document.getElementById('file-name');

            fileInput.addEventListener('change', function () {
                if (fileInput.files.length > 0) {
                    fileNameDisplay.textContent = Array.from(fileInput.files).map(file => file.name).join(', ');
                } else {
                    fileNameDisplay.textContent = 'Δεν έχει επιλεχθεί αρχείο';
                }
            });

            const date = document.querySelector('#deadline');
            if (date) {
                date.flatpickr({
                    altInput: true,
                    altFormat: 'd-m-Y',
                    dateFormat: 'Y-m-d',
                    locale: {
                        ...flatpickr.l10ns.gr,
                        firstDayOfWeek: 1
                    }
                });
            }

            $(".filter_assignees").select2({
                placeholder: 'Assignees',
                allowClear: true,
                ajax: {
                    type: 'POST',
                    delay: 500,
                    url: "{{ route('api.internal.users.namesPaginated') }}",
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

            $(".companies_select").select2({
                placeholder: 'Αναζήτηση...',
                allowClear: true,
                ajax: {
                    type: 'POST',
                    delay: 500,
                    url: "{{ route('api.internal.companies.namesPaginated') }}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    processResults: function (data, params) {
                        return {
                            results: $.map(data.results, function (obj) {
                                return {
                                    id: obj.id,
                                    text: obj.text + (obj.status ? ' (' + obj.status + ')' : '')
                                }; // Use id and name
                            }),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                }
            });


            $('#company_id').on('change', function () {
                let companyId = $('#company_id').val();

                let contacts = $("#contacts");
                contacts.val(null).trigger('change');
                contacts.empty();

                let url = `{{ route('api.internal.companies.getContactsByCompanyId', ':companyId') }}`.replace(':companyId', companyId);
                $(".select_contacts").select2({
                    placeholder: 'Αναζήτηση...',
                    allowClear: true,
                    ajax: {
                        type: 'POST',
                        delay: 500,
                        url: url,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: function (params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1
                            }
                        },
                        processResults: function (data, params) {
                            return {
                                results: $.map(data.results, function (obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.text
                                    }; // Use id and name
                                }),
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: true
                    }
                });

            });

            $('#form').on('submit', function (e) {
                let visitDate = $('#visit_date').val();

                if (!visitDate) {
                    e.preventDefault();
                    $('#visit_date').addClass('is-invalid');
                    return false;
                } else {
                    $('#visit_date').removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
