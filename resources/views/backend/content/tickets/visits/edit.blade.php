@php
    /**
     * @var \App\Domains\Tickets\Models\Ticket $ticket
     * @var \App\Domains\Companies\Models\Company $company
    **/
@endphp
@extends('backend.layouts.app')

@section('title', 'Επεξεργασία Επίσκεψης')

@section('page-style')
    <style>
        .file-input-label {
            display: inline-block;
            padding: 7px 12px;
            cursor: pointer;
            border-right: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
            text-align: center;
            margin: 0 8px;
            text-wrap: nowrap;
            z-index: 3;
        }

        .file-input-label:hover {
            background-color: #eaeaea;
        }

        #file-upload {
            display: none;
        }

        .file-name {
            z-index: 1;
            font-size: 0.9rem;
            color: #555;
            max-width: 400px;
            /*white-space: nowrap;*/
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 998px) {
            .file-name, .file-input-label {
                text-align: start;
            }

            .file-input-label {
                padding: 7px;
            }
        }

        @media (max-width: 568px) {
            .file-name, .file-input-label {
                font-size: 12px;
                text-align: start;
            }
        }
    </style>
@endsection

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Αρχική</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.visits.index') }}">Visits</a></li>
    <li class="breadcrumb-item active"> {{ __('Edit') }}</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-10 container-p-y container-fluid">
            <form id="form" method="POST" action="{{ route('admin.visits.update', $ticket->getId()) }}" class="form-horizontal needs-validation" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PATCH')
                <div class="card">
                    <div class="card-body">

                        <div class="form-group row mb-3 mt-1">
                            <label for="company_id" class="col-md-2 col-form-label">Τίτλος επίσκεψης <small class="text-danger"> *</small></label>
                            <div class="col-md-10">
                                <input type="text" name="name" class="form-control" placeholder="Τίτλος επίσκεψης" maxlength="255" value="{{ $ticket->getName() }}" required/>
                                <div class="invalid-feedback">Ο τίτλος είναι απαραίτητος.</div>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <label for="company_id" class="col-md-2 col-form-label">Εταιρεία <small class="text-danger"> *</small></label>
                            <div class="col-md-10">
                                <select name="company_id" id="company_id" class="form-control companies_select" data-placeholder="{{ 'Εταιρεία' }}" required>
                                    <option value="{{ $ticket->getCompany()?->getId() }}">{{ $ticket->getCompany()?->getName() }}</option>
                                </select>
                                <div class="invalid-feedback">Η εταιρεία είναι απαραίτητη.</div>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <label for="visit_date" class="col-md-2 col-form-label">Ημ/νια επίσκεψης</label>
                            <div class="col-md-10">
                                <input type="text" name="visit_date" id="visit_date" placeholder="dd-mm-yyyy" autocomplete="off" class="form-control datepicker" value="{{ $ticket->getVisitDate()->format('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <label for="visit_type" class="col-md-2 col-form-label">Τύπος επίσκεψης</label>
                            <div class="col-md-10">
                                <select id="visit_type" name="visit_type" class="select2 form-select" data-placeholder="Τύπος επίσκεψης" data-allow-clear="true">
                                    <option></option>
                                    @foreach(\App\Domains\Tickets\Enums\VisitTypeSourceEnum::cases() as $type)
                                        <option value="{{ $type->value }}" {{ $ticket->getVisitType()?->value == $type->value ? 'selected' : '' }} > {{ $type->value }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <label for="outcome" class="col-md-2 col-form-label">Outcome</label>
                            <div class="col-md-10">
                                <input type="number" name="outcome" id="outcome" autocomplete="off" class="form-control" value="{{ $ticket->getOutcome() }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <label for="products_discussed" class="col-md-2 col-form-label">Προϊόν συζήτησης</label>
                            <div class="col-md-10">
                                <select name="products_discussed" id="products_discussed" class="form-control select2" data-placeholder="Προϊόν συζήτησης">
                                    <option></option>
                                    @foreach(\App\Domains\Tickets\Enums\VisitProductDiscussedSourceEnum::cases() as $productDiscussed)
                                        <option value="{{ $productDiscussed->value }}" {{ $ticket->getProductsDiscussed()?->value == $productDiscussed->value ? 'selected' : '' }} > {{ $productDiscussed->value }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <label for="contacts" class="col-md-2 col-form-label">Επαφή επικοινωνίας</label>
                            <div class="col-md-10">
                                <select name="contacts[]" class="form-select select2 select_contacts" data-placeholder="Επαφή επικοινωνίας" data-allow-clear="true" multiple>
                                    @foreach($companyContacts ?? [] as $contact)
                                        <option value="{{ $contact->getId() }}" {{ in_array($contact->getId(), $ticketContactsIds) ? 'selected' : '' }}>{{ $contact->getName() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <label for="next_action" class="col-md-2 col-form-label">Επόμενο Action</label>
                            <div class="col-md-10">
                                <select name="next_action" id="next_action" class="form-control select2" data-placeholder="Επόμενο Action">
                                    <option></option>
                                    @foreach(\App\Domains\Tickets\Enums\VisitNextActionSourceEnum::cases() as $action)
                                        <option value="{{ $action->value }}" {{ $ticket->getNextAction()?->value == $action->value ? 'selected' : '' }} > {{ $action->value }} </option>
                                    @endforeach
                                </select>
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

        $('#visit_date').each(function (i, date) {
            date.flatpickr({
                minDate: new Date(new Date().setDate(new Date().getDate() - 2)),
                locale: 'gr',
                altInput: true,
                altFormat: 'd-m-Y',
                dateFormat: 'Y-m-d',
            })
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
            glet companyId = $('#company_id').val();
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

    </script>
@endsection
