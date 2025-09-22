@php use App\Domains\Visits\Models\Visit;use App\Domains\Visits\Models\VisitStatus; use Carbon\Carbon; use App\Domains\ExtraData\Enums\ExtraDataTypesEnum; use App\Models\Enums\EloqMorphEnum; @endphp
@php
    /**
    * @var Visit $visit
    * @var VisitStatus $visitStatuse
    * @var array<VisitStatus> $visitStatuses
    * */
@endphp
@extends('backend.layouts.app')

@section('title', 'Visit')

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
                @foreach($visit->getMorphables() as $morph)
                    <li class="nav-item">
                        <button type="button" class="nav-link {{ $morph->value === EloqMorphEnum::NOTES->value ? 'active' : '' }}" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-pills-{{$morph->value}}"
                                aria-controls="navs-pills-top-{{$morph->value}}"
                                aria-selected="false">{{ __(Str::ucfirst($morph->value))}}</button>
                    </li>
                @endforeach
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-top-details" aria-controls="navs-pills-top-details"
                            aria-selected="true">{{__('Details')}}</button>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Αρχική</a></li>
    <li class="breadcrumb-item"> <a href="{{ route('admin.visits.index') }}">{{ __('Visits') }}</a></li>
    <li class="breadcrumb-item active"> {{ $visit->getName() }}</li>
@endsection

@section('content')

    <div class="row mb-4">
        <div class="col-12 row">
            <div class="col-md-9 col-xs-12">
                <h4>{{$visit->getName()}} ( {{$visit->getOwner()->getName()}} )</h4>
            </div>
            <div class="col-md-3">
                <select name="visit_status" id="visit_status" class="form-control select2" data-placeholder="status">
                    <option value="">---</option>
                    @if($canEditStatus ?? false)
                        @foreach($visitStatuses ?? [] as $status)
                            <option value="{{$status->getSlug()}}" @if($status->getId() == $visit->getActiveStatus()?->getId()) selected @endif>{{$status->getName()}}</option>
                        @endforeach
                    @else
                        <option value="{{$visit->getActiveStatus()->getSlug()}}" selected >{{$visit->getActiveStatus()->getName()}}</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-12 text-center">
            <div class="card">
                <div class="card-header pb-2 pt-2 navbar navbar-expand-lg d-flex " style="background-color: #394263; !important;">
                    <div class="col-md-3">
                        <h6 class="pb-0 mb-0 text-white"><strong>Deadline</strong></h6>
                    </div>
                    <div class="col-md-3">
                        <h6 class="pb-0 mb-0 text-white"><strong>Πελάτης</strong></h6>
                    </div>
                    <div class="col-md-3">
                        <h6 class="pb-0 mb-0 text-white"><strong>Κατάσταση</strong></h6>
                    </div>
                    <div class="col-md-3">
                        <h6 class="pb-0 mb-0 text-white"><strong>Ανατέθηκε</strong></h6>
                    </div>
                </div>
                <div class="card-body pb-0 pt-2">
                    <div class="d-flex mb-3 align-items-center">
                        <div class="col-md-3">
                            <span class="h5 text-default">{{$visit->getDeadline()?->format('d-m-Y') ?? ' - '}}</span>
                        </div>
                        <div class="col-md-3">
                            @php
                                $url = '#';
                                if($visit->getCompany()?->getLead()){
                                    $url = route('admin.leads.show', $visit->getCompany()?->getLead()?->getId());
                                }
                                if($visit->getCompany()?->getClient()){
                                    $url = route('admin.clients.show', $visit->getCompany()?->getClient()?->getId());
                                }
                            @endphp
                            <a href="{{$url}}" class="font-medium-1"> {{$visit->getCompany()?->getName()  ?? ' - '}} </a>
                        </div>
                        <div class="col-md-3">
                            <span id="visit-status-{{ $visit->getId() }}" class="font-medium-1 text-{{$visit->getActiveStatus()?->getLabel()?->value}}"> <strong>{{$visit->getActiveStatus()?->getName() ?? ' - '}}</strong> </span>
                        </div>
                        <div class="col-md-3">
                            @foreach($visit->getAssignees() ?? [] as $assignee)
                                {{$assignee->getName()}} <br>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-content p-0">
        <div class="tab-pane fade" id="navs-pills-top-details" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title m-0 me-2 pt-1 mb-2 d-flex align-items-center"><i
                                        class="fa fa-table me-3"></i> {{ __('Details') }}</h5>
                                <div class="card-header-elements ms-auto">
                                    <a href="{{ route('admin.visits.edit', [$visit->getId()] ) }}"
                                       class="btn btn-xs btn-primary waves-effect waves-light">
                                        <span class="tf-icon fa fa-pen fa-xs me-1"></span>
                                        {{__('Edit')}}
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Name')}} </label>
                                        <span class="d-block font-small-4"> {{ $visit->getName() }} </span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Company')}} </label>
                                        @if($visit->getCompanyId())
                                            <a href="{{route('admin.companies.show', $visit->getCompanyId())}}"
                                           class="d-block font-small-4"> {{ $visit->getCompany()->getName() }} </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Deadline')}} </label>
                                        <span class="d-block font-small-4"> {{  $visit->getDeadline() ? Carbon::parse($visit->getDeadline())->format('d-m-Y') : '-' }} </span>
                                    </div>
                                    <div class="col-md-6">

                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"> Ημ/νια επίσκεψης </label>
                                        <span class="d-block font-small-4"> {{  $visit->getVisitDate()?->format('d-m-Y') ?? '-' }} </span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"> Τύπος επίσκεψης </label>
                                        <span class="d-block font-small-4"> {{  $visit->getVisitType()?->value ?? '-' }} </span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"> Outcome </label>
                                        <span class="d-block font-small-4"> {{  $visit->getOutcome() ?? '-' }} </span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"> Προϊόν συζήτησης </label>
                                        <span class="d-block font-small-4"> {{  $visit->getProductsDiscussed()?->value ?? '-' }} </span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"> Επαφές επικοινωνίας </label>
                                        @foreach($visit->getContacts() as $contact)
                                            <span class="d-block font-small-4"> {{ $contact->getName() ?? '-' }} </span>
                                        @endforeach
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"> Επόμενο Action </label>
                                        <span class="d-block font-small-4"> {{  $visit->getNextAction()?->value ?? '-' }} </span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Manager')}} </label>
                                        <a href="{{ route('admin.users.show', $visit->getOwnerId()) }}"
                                           class="d-block font-small-4"> {{ $visit->getOwner()->getName() ?: '-'}} </a>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"> {{__('Assignee')}} </label>
                                        @foreach($visit->getAssignees() ?? [] as $assignee)
                                            <span class="d-block font-small-4">  {{$assignee->getName()}} </span>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    @include('backend.content.companies.includes.showDetails', ['company' => $visit->getCompany()])

{{--                    <div class="card mt-2">--}}
{{--                        <div class="card-header d-flex justify-content-between">--}}
{{--                            <h5 class="card-title m-0 me-2 pt-1 mb-2 d-flex align-items-center">--}}
{{--                                <i class="ti ti-list-details me-3"></i> {{ __('Activity Timeline') }}--}}
{{--                            </h5>--}}
{{--                        </div>--}}
{{--                        <div class="card-body pb-xxl-0">--}}
{{--                            <ul class="timeline mb-0">--}}
{{--                                @foreach($visit->getVisitStatuses() as $status)--}}
{{--                                    <li class="timeline-item timeline-item-transparent">--}}
{{--                                        <span class="timeline-point timeline-point-{{ $status->getLabel() }}"></span>--}}
{{--                                        <div class="timeline-event">--}}
{{--                                            <div class="timeline-header mb-1">--}}
{{--                                                <h6 class="mb-0"> {{__('Status changed to ')}}--}}
{{--                                                    "{{ $status->getName() }}"</h6>--}}
{{--                                                <small class="text-muted"> {{ $status->getPivotDate() }}</small>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </li>--}}
{{--                                @endforeach--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
        @foreach($visit->getMorphables() as $morph)
            <div class="tab-pane fade {{ $morph->value === EloqMorphEnum::NOTES->value ? 'active show' : '' }}" id="navs-pills-{{$morph->value}}" role="tabpanel">
                <div class="pb-3">
                    <x-morphs.morph morph="{{ $morph->value }}" :model="$visit"/>
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

            $('#visit_status').on('change', function() { //remove margin for the placeholder when the select option is not empty
                let apiUrl = `{{ route('admin.visits.update-status', ':visitId') }}`.replace(':visitId', '{{ $visit->getId() }}');
                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: apiUrl,
                    data: {
                        ajax: true,
                        visit_status: $(this).val()
                    },
                    success: function(response) {
                        // console.log('Visit updated successfully', response);
                        if(response.status === 200){
                            const visitId = response.data.visitId;
                            const newLabelClass = response.data.labelClass;
                            const newLabelName = response.data.labelName;

                            $(`#visit-status-${visitId}`)
                                .removeClass(function (index, className) {
                                    return (className.match(/(^|\s)text-\S+/g) || []).join(' ');
                                })
                                .addClass(`text-${newLabelClass}`)
                                .find('strong').text(newLabelName);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating visit:', error);  // Handle error
                    }
                });

            });
        });
    </script>
@endsection


@push('after-scripts')


@endpush
