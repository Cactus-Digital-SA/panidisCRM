@php use App\Domains\Projects\Models\Project;use App\Domains\Projects\Models\ProjectStatus;use App\Domains\Projects\Models\ProjectType;use Carbon\Carbon; @endphp
@php
    /**
    * @var Project $project
    * @var ProjectType $projectType
    * @var array<ProjectStatus> $projectStatus
    * */
@endphp
@extends('backend.layouts.app')

@section('title', 'Project')

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
                    <button type="button"
                            class="nav-link {{ $defaultTab === 'navs-pills-top-details' ? 'active' : '' }}"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#navs-pills-top-details"
                            aria-controls="navs-pills-top-details"
                            aria-selected="{{ $defaultTab === 'navs-pills-top-details' ? 'true' : 'false' }}">
                        {{ __('Details') }}
                    </button>
                </li>
                @foreach($project->getMorphables() as $morph)
                    <li class="nav-item">
                        <button type="button"
                                class="nav-link {{ $defaultTab === 'navs-pills-' . $morph->value ? 'active' : '' }}"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-pills-{{ $morph->value }}"
                                aria-controls="navs-pills-{{ $morph->value }}"
                                aria-selected="{{ $defaultTab === 'navs-pills-' . $morph->value ? 'true' : 'false' }}">
                            {{ __(Str::ucfirst($morph->value)) }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Αρχική</a></li>
    <li class="breadcrumb-item"> {{ __('Projects') }}</li>
    <li class="breadcrumb-item active"> {{ $project->getName() }}</li>
@endsection

@section('content')

    <div class="tab-content p-0">
        <div class="tab-pane fade {{ $defaultTab === 'navs-pills-top-details' ? 'show active' : '' }}" id="navs-pills-top-details" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title m-0 me-2 pt-1 mb-2 d-flex align-items-center"><i
                                        class="fa fa-table me-3"></i> {{ __('Details') }}</h5>
                                @if($project->getCreatedByUser()) <span> (Δημιουργήθηκε από {{ $project->getCreatedByUser()->getName() }}) </span> @endif
                                <div class="card-header-elements ms-auto">
                                    <a href="{{ route('admin.projects.edit', [$project->getType()->getSlug(), $project->getId()] ) }}" class="btn btn-xs btn-primary waves-effect waves-light"><span class="tf-icon fa fa-pen fa-xs me-1"></span>{{__('Edit')}}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Name')}} </label>
                                        <span class="d-block font-small-4"> {{ $project->getName() }} </span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Client')}} </label>
                                        @if($project->getClientId())
                                        <a href="{{route('admin.clients.show', $project->getClientId())}}"
                                           class="d-block font-small-4"> {{ $project->getClient()->getCompany()->getName() }} </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Start Date')}} </label>
                                        <span
                                            class="d-block font-small-4"> {{ $project->getStartDate() ? Carbon::parse($project->getStartDate())->format('d-m-Y') : '-' }} </span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Deadline')}} </label>
                                        <span
                                            class="d-block font-small-4"> {{  $project->getDeadline() ? Carbon::parse($project->getDeadline())->format('d-m-Y') : '-' }} </span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Status')}} </label>
                                        <span class="d-block font-small-4"> {{ $project->getActiveStatus()->getName() ?: '-'  }} </span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Estimation Date')}} </label>
                                        <span class="d-block font-small-4"> {{  $project->getEstDate() ? Carbon::parse($project->getEstDate())->format('d-m-Y') : '-' }} </span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Sales Cost')}} </label>
                                        <span class="d-block font-small-4"> {{ $project->getSalesCost() ?: '-'}} </span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Priority')}} </label>
                                        <span class="d-block font-small-4"> {{  $project->getPriority() ?: '-' }} </span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Manager')}} </label>
                                        <a href="{{ route('admin.users.show', $project->getOwnerId()) }}" class="d-block font-small-4"> {{ $project->getOwner()->getName() ?: '-'}} </a>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Google Drive')}} </label>
                                        <a href="{{ $project->getGoogleDrive() ?: '' }}" target="_blank" class="d-block font-small-4"> {{  $project->getGoogleDrive() ?: '-' }} </a>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label"> {{__('Description')}} </label>
                                        <span class="d-block font-small-4"> {{ $project->getDescription() ?: '-'}} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    @include('backend.content.companies.includes.showDetails', ['company' => $project->getClient()?->getCompany()])

                    <div class="card mt-2">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title m-0 me-2 pt-1 mb-2 d-flex align-items-center"><i
                                    class="ti ti-list-details me-3"></i> {{ __('Activity Timeline') }}</h5>
                        </div>
                        <div class="card-body pb-xxl-0">
                            <ul class="timeline mb-0">
                                @foreach($project->getProjectStatus() as $status)
                                    <li class="timeline-item timeline-item-transparent">
                                            <span
                                                class="timeline-point timeline-point-{{ $status->getLabel() }}"></span>
                                        <div class="timeline-event">
                                            <div class="timeline-header mb-1">
                                                <h6 class="mb-0"> {{__('Status changed to ')}}
                                                    "{{ $status->getName() }}"</h6>
                                                <small class="text-muted"> {{ $status->getPivotDate() }}</small>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @foreach($project->getMorphables() as $morph)
            <div class="tab-pane fade {{ $defaultTab === 'navs-pills-' . $morph->value ? 'show active' : '' }}"
                 id="navs-pills-{{ $morph->value }}"
                 role="tabpanel">
                <div class="pb-3">
                    <x-morphs.morph morph="{{ $morph->value }}" :model="$project"/>
                </div>
            </div>
        @endforeach
    </div>

@endsection

@section('modals')
    @include('backend.components.delete_modal')
@endsection

@section('vendor-script')


@endsection

@section('page-script')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.nav-link');

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    const target = tab.getAttribute('data-bs-target').substring(1); // Get the tab target ID without '#'

                    // Update the URL with the tab parameter
                    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + target;
                    window.history.pushState({path: newUrl}, '', newUrl);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab'); // Get the tab parameter from the URL

            if (tab) {
                const targetTab = document.querySelector(`[data-bs-target="#${tab}"]`);
                if (targetTab) {
                    // Deactivate all tabs
                    document.querySelectorAll('.nav-link').forEach(function (tab) {
                        tab.classList.remove('active');
                    });

                    // Activate the tab from the URL parameter
                    targetTab.classList.add('active');

                    // Show the corresponding tab content
                    const tabContent = document.querySelector('.tab-pane.active');
                    if (tabContent) {
                        tabContent.classList.remove('active', 'show');
                    }
                    document.querySelector(`#${tab}`).classList.add('active', 'show');
                }
            }
        });
    </script>
@endsection


@push('after-scripts')


@endpush
