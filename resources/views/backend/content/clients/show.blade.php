@php
    /** @var \App\Domains\Clients\Models\Client $client */
@endphp
@extends('backend.layouts.app')

@section('title', $client->getCompany()->getName())

@section('vendor-style')
    @vite([])
@endsection

@section('page-style')
    @vite([])
@endsection

@section('content-header')
    <div class="col-xl-12">
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-top-details" aria-controls="navs-pills-top-details"
                            aria-selected="true">{{__('Details')}}</button>
                </li>
                @foreach($company->getMorphables() ?? [] as $morph)
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-pills-{{$morph->value}}"
                                aria-controls="navs-pills-top-{{$morph->value}}"
                                aria-selected="false">{{ __(Str::ucfirst($morph->value))}}</button>
                    </li>
                @endforeach

                <li class="nav-item">
                    {{--                    <button data-bs-target="#createTicket" data-bs-toggle="modal" class="nav-link  me-2">--}}
                    {{--                        {{ __("Create Ticket") }}--}}
                    {{--                    </button>--}}
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Αρχική</a>
    </li>
    <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">Clients</a>
    </li>
    <li class="breadcrumb-item active">{{$client->getCompany()->getName()}}
    </li>
@endsection
@section('content')
    <div class="tab-content p-0">
        <div class="tab-pane fade show active" id="navs-pills-top-details" role="tabpanel">

            <div class="row">
                <div class="col-lg-6">
                    <form id="form" method="POST" action="{{ route('admin.clients.update', $client->getId()) }}" class="form-horizontal needs-validation"
                          enctype="multipart/form-data" novalidate>
                        @method('PATCH')
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row mb-3 mt-1 align-items-center">
                                    <label for="clientCompanyId" class="col-form-label col-md-4">Εταιρεία</label>
                                    <div class="col-md-8">
                                        <select name="clientCompanyId" id="clientCompanyId" class="form-control companies-select select2" data-placeholder="{{ 'Εταιρεία' }}" data-allow-clear="true" required>
                                            @if($client->getCompanyId())
                                                <option value="{{$client->getCompanyId()}}" selected> {{$client?->getCompany()?->getName()}}  </option>
                                            @endif
                                        </select>
                                        <div class="invalid-feedback">Η εταιρεία είναι απαραίτητη. </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3 mt-1 align-items-center">
                                    <label for="salesPersonId" class="col-form-label col-md-4">Ανάθεση πωλητή </label>
                                    <div class="col-md-8">
                                        <select name="salesPersonId" id="salesPersonId" class="form-control select2 salesPersons" data-placeholder="Επιλέξτε πωλητή" data-allow-clear="true">
                                            <option value=""></option>
                                            @foreach($salesPersons ?? [] as $salesPerson)
                                                <option value="{{$salesPerson->getId()}}" @if($client->getSalesPersonId() == $salesPerson->getId()) selected @endif >{{$salesPerson->getName()}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"> Η ανάθεση πωλητή είναι απαραίτητη. </div>
                                    </div>
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-primary me-1">
                                        Αποθήκευση <i class="ms-2 ti ti-check ti-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="col mt-2">
                        @include('backend.content.companies.includes.contactsDatatable',
                                        [
                                            'company' => $company,
                                            'contactsColumns' => $contactsColumns ?? [],
                                            'labelCol' => 'col-md-2',
                                            'fieldCol' => 'col-md-10'
                                        ])
                    </div>
                </div>

                <div class="col-lg-6">
                    @include('backend.content.companies.includes.showDetails', ['company' => $client->getCompany()])
                </div>
            </div>
        </div>
        @foreach($company->getMorphables() ?? [] as $morph)
            <div class="tab-pane fade" id="navs-pills-{{$morph->value}}" role="tabpanel">
                <div class="pb-3">
                    <x-morphs.morph morph="{{ $morph->value }}" :model="$company"/>
                </div>
            </div>
        @endforeach
    </div>

@endsection

@section('modals')
    <div class="modal fade" id="show-user" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-show-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h2 class="mb-1">Προβολή Επαφής</h2>
                    </div>
                    <div class="col-12">
                        <form action="#" id="show-user-form" method="post" class="row gy-1 pt-75">
                            <div class="form-group row mb-1 mt-1">
                                <label for="name" class="col-md-2 col-form-label fw-bold">Όνομα</label>
                                <div class="col-md-10">
                                    <p name="firstName" class="form-control"></p>
                                    <div class="invalid-feedback"> Το Όνομα είναι απαραίτητο. </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1 mt-1">
                                <label for="name" class="col-md-2 col-form-label fw-bold">Επώνυμο</label>
                                <div class="col-md-10">
                                    <p name="lastName" class="form-control"></p>
                                    <div class="invalid-feedback"> Το Επώνυμο είναι απαραίτητο. </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1 mt-1">
                                <label for="email" class="col-md-2 col-form-label fw-bold">@lang('E-mail Address')</label>
                                <div class="col-md-10">
                                    <p name="email" class="form-control"></p>
                                    <div class="invalid-feedback"> Το Email είναι απαραίτητο. </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1 mt-1">
                                <label for="email" class="col-md-2 col-form-label fw-bold">Τηλέφωνο</label>
                                <div class="col-md-10">
                                    <p name="phone" class="form-control"></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    @include('includes.datatable_scripts')
@endsection

@section('page-script')
    @parent
    <script type="module">
        const date = document.querySelector('#estClosingDate');

        if(date){
            date.flatpickr({
                altInput: true,
                altFormat: 'd-m-Y',
                dateFormat: 'Y-m-d',
                locale: {
                    ...flatpickr.l10ns.gr, // Merge Greek locale settings
                    firstDayOfWeek: 1
                },
            });
        }

        $(".companies-select").select2({
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
                data: function(params) {
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


        // Display subsection containers and Tags containers
        $('.section-select').each(function () {
            let sectionId = $(this).data('section-id');
            let selectedOption = $(this).find('option:selected');
            let hasSubSections = selectedOption.data('has-subsections');
            let allowTags = selectedOption.data('allow-tag');
            let tagContainer = $(`#tag-container-${sectionId}`);
            let subsectionContainer = $(`#subsection-container-${sectionId}`);
            let allowComment = selectedOption.data('allow-comment');
            let commentContainer = $(`#comment-container-${sectionId}`);

            if (hasSubSections) {
                subsectionContainer.show();
                let selectedOptionId = selectedOption.val();

                $(`#subsection_${sectionId} option`).each(function () {
                    $(this).toggle($(this).data('option-id') == selectedOptionId);
                });
            } else {
                subsectionContainer.hide();
            }

            if (allowTags) {
                tagContainer.show();
            } else {
                tagContainer.hide();
            }

            if (allowComment) {
                commentContainer.show();
            } else {
                commentContainer.hide();
            }
        });

        // Event listener for section options
        $('.section-select').on('change', function () {
            let sectionId = $(this).data('section-id');
            let selectedOption = $(this).find('option:selected');
            let hasSubSections = selectedOption.data('has-subsections');
            let subsectionContainer = $(`#subsection-container-${sectionId}`);
            let subsectionSelect = $(`#subsection_${sectionId}`);
            let allowTags = selectedOption.data('allow-tag');
            let tagContainer = $(`#tag-container-${sectionId}`);
            let allowComment = selectedOption.data('allow-comment');
            let commentContainer = $(`#comment-container-${sectionId}`);

            if (hasSubSections) {
                // display subsections for the selected option
                let selectedOptionId = selectedOption.val();

                subsectionSelect.find('option').hide();
                subsectionSelect.find(`option[data-option-id="${selectedOptionId}"]`).show(); // Show only relevant subsections

                subsectionContainer.show();
                subsectionSelect.val('').trigger('change');
            } else {
                subsectionContainer.hide();
                subsectionSelect.val('').trigger('change'); // Reset select
            }

            if (allowTags) {
                tagContainer.show();
            } else {
                tagContainer.hide();
                $(`#tag_${sectionId}`).val(''); // Clear the tag field if hidden
            }

            if (allowComment) {
                commentContainer.show();
            } else {
                commentContainer.hide();
                $(`#comment_${sectionId}`).val(''); // Clear the comment field if hidden
            }

            /// Display subsection containers and tag containers for preselected subsections
            $('.subsection-select').each(function () {
                let subsectionContainer = $(this).closest('.form-group');
                if ($(this).val()) {
                    subsectionContainer.show();
                }
            });
        });

        $('.enable-tag').select2({
            tags: true,
        });
    </script>

    <script>
        function fetchContact(contactId)
        {
            let form = $('#show-user-form');
            let action_url = '{{ route('api.internal.contacts.getContact',':id') }}';
            action_url = action_url.replace(':id', contactId,);
            $.ajax({
                url: action_url,
            })
                .done(function(response) {
                    form.find('[name="firstName"]').text(response.data.firstName || '-');
                    form.find('[name="lastName"]').text(response.data.lastName || '-');
                    form.find('[name="email"]').text(response.data.email || '-');
                    form.find('[name="phone"]').text(response.data.phone || '-');

                    let decisionMakerCheckbox = form.find('[name="decisionMaker"]');
                    if (response.data.decisionMaker) {
                        decisionMakerCheckbox.prop('checked', true);
                    } else {
                        decisionMakerCheckbox.prop('checked', false);
                    }
                    if (response.data.extraData) {
                        populateExtraData(response.data.extraData);
                    }

                });

        }

        function populateExtraData(extraData) {
            extraData.forEach(function(item) {
                let field = $(`[name="extra_data[${item.id}]"]`);
                field.text(item.value);
            });
        }


    </script>

    @include('backend.components.js.select')
@endsection
