@php use App\Domains\Projects\Models\ProjectStatus;use App\Domains\Projects\Models\ProjectType; @endphp
@php
    /**
    * @var ProjectType $projectType
    * @var array<ProjectStatus> $projectStatus
    * */
@endphp
@extends('backend.layouts.app')

@section('title', 'Create Project')

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Αρχική</a></li>
    <li class="breadcrumb-item">{{ __($projectType->getName()) }}</li>
    <li class="breadcrumb-item active"> <a href="{{route('admin.projects.create', $projectType->getSlug())}}">{{ __('Create') }}</a></li>
@endsection

<!-- Vendor Styles -->
@section('vendor-style')
    @vite([
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    ])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite([
      'resources/assets/vendor/libs/autosize/autosize.js',
      'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    ])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/forms-extras.js'])
    @include('backend.content.projects.js.select')
@endsection

@section('content')

    <div class="row">

        <div class="col-lg-10 container-p-y container-fluid">
            <form id="form" method="POST" action="{{ route('admin.projects.store', $projectType->getSlug()) }}" class="form-horizontal">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">@lang('Create Project')</h5>
                        <div class="card-header-actions">
                            <a class="card-header-action btn btn-warning"
                               href="{{route('admin.projects.index', $projectType->getSlug() )}}">
                                <i data-feather='arrow-left'></i> {{__('Cancel')}}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-4">
                                <label class="form-label">{{__('Category') }}<small class="text-danger"> *</small></label>
                                <select name="category" id="category" class="form-select select2 projectCategory" data-placeholder="{{ __('Category') }}" data-allow-clear="true" required>
                                    <option></option>
                                    @foreach(\App\Domains\Projects\Enums\ProjectCategoryEnum::cases() as $option)
                                        <option value="{{ $option->value }}"
                                                @if(\App\Domains\Projects\Enums\ProjectCategoryEnum::INTERNAL->value == $option->value) selected @endif>{{ $option->value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">{{__('Project Type') }}<small class="text-danger"> *</small></label>
                                <select name="category_status" id="category_status" class="form-select select2" data-placeholder="{{ __('Project Type') }}" data-allow-clear="true" required>
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <label for="name" class="col-md-2 col-form-label">@lang('Name')<small class="text-danger"> *</small></label>
                            <div class="col-md-10">
                                <input type="text" name="name" class="form-control" placeholder="{{ __('Name') }}"
                                       value="{{ old('name') }}" maxlength="100" required/>
                            </div>
                        </div>
                        <div class="form-group row mb-3 mt-1">
                            <label for="description" class="col-md-2 col-form-label">@lang('Description')</label>
                            <div class="col-md-10">
                                <textarea id="autosize-demo" name="description" rows="2" class="form-control" placeholder="{{ __('Description') }}">{{ old('description') }}</textarea>
                            </div>
                        </div>

{{--                        <div class="form-group row mb-3 mt-1" id="clientElement">--}}
{{--                            <label aria-label="Client" class="col-md-2 col-form-label">@lang('Client')<small class="text-danger"> *</small></label>--}}
{{--                            <div class="col-md-10">--}}
{{--                                <select name="client_id" id="client_id" class="form-control select2 filter_clients"--}}
{{--                                        data-placeholder="{{ __('Client Selection') }}">--}}
{{--                                    <option></option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="form-group row mb-3 mt-1" id="clientElement">
                            <label aria-label="Client" class="col-md-2 col-form-label">@lang('Client')<small class="text-danger"> *</small></label>
                            <div class="col-md-10">
                                <select name="company_id" id="company_id" class="form-control select2 companies-select"
                                        data-placeholder="{{ __('Client Selection') }}">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <label for="sales_cost" class="col-md-2 col-form-label">@lang('Sales Cost')</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="sales_cost" placeholder="Amount" aria-label="Amount (to the nearest euro)">
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-5 mt-1">
                            <label for="google_drive" class="col-md-2 col-form-label">@lang('Google Drive')</label>
                            <div class="col-md-10">
                                <input type="text" name="google_drive" class="form-control" placeholder="{{ __('Google Drive') }}"
                                       value="{{ old('google_drive') }}"/>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col">
                                <div class="form-group row mb-1 mt-1">
                                    <div class="col-md-12">
                                        <label for="priority" class="form-label">@lang('Priority')<small class="text-danger"> *</small></label>
                                        <select name="priority" id="priority" class="form-control select2" data-placeholder="{{ __('Priority') }}">
                                            @foreach(\App\Domains\Projects\Enums\ProjectPriorityEnum::cases() as $priority)
                                                <option value="{{ $priority->value }}"> {{ $priority->value }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
{{--                                <div class="form-group row mb-1 mt-1">--}}
{{--                                       <div class="col-md-12">--}}
{{--                                           <label for="status" class="form-label">@lang('Status')<small class="text-danger"> *</small></label>--}}
{{--                                           <select name="status" id="filter_status" class="form-control select2" data-placeholder="{{ __('Status Selection') }}">--}}
{{--                                            @foreach($projectStatus as $status)--}}
{{--                                                <option value="{{ $status->getId() }}" @if($status->getSlug() == 'in-progress') selected @endif> {{$status->getName()}} </option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                            </div>

                        </div>

                        <div class="row mb-1 mt-1">
                            <div class="col">
                                <div class="form-group row mb-1 mt-1">
                                    <div class="col-md-12">
                                        <label aria-label="Manager" class="form-label">@lang('Manager')<small class="text-danger"> *</small></label>
                                        <select name="owner_id" class="form-control select2 filter_owner"
                                                data-placeholder="Manager" required>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group row mb-1 mt-1">
                                    <div class="col-md-12">
                                        <label aria-label="Κοινοποίηση (CC)" class="form-label">Κοινοποίηση (CC)</label>
                                        <select type="text" name="assignees[]" class="form-control select2 filter_assignees"
                                                data-placeholder="{{ __('Κοινοποίηση (CC)') }}" multiple>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-1 mt-1">
                            <div class="col">
                                <div class="form-group row mb-1 mt-1">
                                    <div class="col-md-12">
                                        <label for="start_date" class="form-label">@lang('Start Date')</label>
                                        <input type="text" name="start_date" id="start_date" placeholder="dd/mm/yyyy" autocomplete="off" value="{{ old('start_date') }}" class="form-control" data-flatpickr="date">
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group row mb-1 mt-1">
                                    <div class="col-md-12">
                                        <label for="deadline" class="form-label">@lang('Deadline')</label>
                                        <input type="text" name="deadline" id="deadline" placeholder="dd/mm/yyyy" autocomplete="off" value="{{ old('deadline') }}" class="form-control" data-flatpickr="date">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col text-end">
                                <button class="btn btn-primary float-right" type="submit">{{__('Create Project')}}</button>
                            </div><!--row-->
                        </div><!--row-->
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@push('after-scripts')
    <script type="module">
        $('.projectCategory').on("select2:select", function () {
            let categoryId = $(this).val();
            addOptions(categoryId);
        });

        const $categoryStatus = $('#category_status');

        let categoryId = $('#category').val();
        addOptions(categoryId);

        function addOptions(categoryId) {
            if (!categoryId) return;

            $categoryStatus.empty();
            $categoryStatus.append(new Option('Φόρτωση...', ''));

            let url = `{{ route('api.internal.projects.get-category-options', ':id') }}`.replace(':id', categoryId);

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error("Invalid category");
                    return response.json();
                })
                .then(data => {
                    $categoryStatus.empty();

                    if (Array.isArray(data) && data.length) {
                        data.forEach(option => {
                            $categoryStatus.append(new Option(option.label, option.value));
                        });
                    } else {
                        $categoryStatus.append(new Option('Δεν υπάρχουν διαθέσιμες επιλογές', ''));
                    }

                    $categoryStatus.trigger('change');
                })
                .catch(error => {
                    console.error('API error:', error);
                    $categoryStatus.empty().append(new Option('Σφάλμα φόρτωσης', ''));
                });
        }

        const category = $('#category');
        const client = $('#client_id');
        const clientElement = $('#clientElement');

        function toggleCompanyRequirement(categoryValue) {
            if (categoryValue === 'Internal') {
                client.prop('required', false);
                clientElement.addClass('d-none');
            } else {
                client.prop('required', true);
                clientElement.removeClass('d-none');
            }
        }

        toggleCompanyRequirement(category.val());

        category.on('change', function () {
            toggleCompanyRequirement($(this).val());
        });
    </script>

    <script type="module">
        flatpickr("#start_date", {
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "d/m/Y",
            locale: { ...flatpickr.l10ns.gr, firstDayOfWeek: 1 }
        });
        flatpickr("#deadline", {
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "d/m/Y",
            locale: { ...flatpickr.l10ns.gr, firstDayOfWeek: 1 }
        });
    </script>
@endpush
