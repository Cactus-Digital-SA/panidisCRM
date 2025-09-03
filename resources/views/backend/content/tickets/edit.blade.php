@php
    /**
     * @var \App\Domains\Tickets\Models\Ticket $ticket
     * @var \App\Domains\Companies\Models\Company $company
    **/
@endphp
@extends('backend.layouts.app')

@section('title', __('Edit Ticket'))

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Αρχική</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">{{ __('Tickets') }}</a></li>
    <li class="breadcrumb-item active"> {{ __('Edit') }}</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-10 container-p-y container-fluid">
            <form id="form" method="POST" action="{{ route('admin.tickets.update', $ticket->getId()) }}"
                  class="form-horizontal">
                @csrf
                @method('PATCH')
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row mb-3 mt-1">
                            <label for="name" class="col-md-2 col-form-label">@lang('Name') <small class="text-danger">
                                    *</small></label>
                            <div class="col-md-10">
                                <input type="text" name="name" class="form-control" placeholder="{{ __('Name') }}"
                                       value="{{ $ticket->getName() }}" maxlength="100" required/>
                            </div>
                        </div>
                        <div class="form-group row mb-3 mt-1">
                            <label for="company_id" class="col-md-2 col-form-label">{{ 'Company' }}
{{--                                <small class="text-danger"> *</small>--}}
                            </label>
                            <div class="col-md-10">
                                <select name="company_id" id="company_id" class="form-control companies_select"
                                        data-placeholder="{{ 'Εταιρεία' }}" >
                                    @if($ticket->getCompanyId())
                                        <option value="{{$ticket->getCompanyId()}}"
                                                selected> {{$ticket?->getCompany()?->getName()}}  </option>
                                    @endif
                                </select>
                                <div class="invalid-feedback">Η εταιρεία είναι απαραίτητη.</div>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <label for="sales_cost" class="col-md-2 col-form-label">@lang('DeadLine')</label>
                            <div class="col-md-10">
                                <input type="text" name="deadline" id="deadline" placeholder="dd-mm-yyyy"
                                       autocomplete="off" value="{{$ticket->getDeadline()?->format('Y-m-d')}}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <label for="sales_cost" class="col-md-2 col-form-label">@lang('Status')</label>
                            <div class="col-md-10">
                                <select id="status_id" name="status_id" class="select2 form-select"
                                        data-placeholder="{{__('Status')}}" data-allow-clear="true" required>
                                    @if(isset($ticketStatus))
                                        @if($canEditStatus ?? false)
                                            @foreach($ticketStatus as $key => $status)
                                                <option value="{{$status->getId()}}"
                                                        @if($ticket->getTicketStatuses()[0]?->getId() == $status->getId()) selected @endif> {{$status->getName()}}</option>
                                            @endforeach
                                        @else
                                            <option value="{{$ticket->getActiveStatus()->getId()}}" selected >{{$ticket->getActiveStatus()->getName()}}</option>
                                        @endif
                                @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <label for="priority" class="col-md-2 col-form-label">@lang('Priority')<small class="text-danger"> *</small></label>
                            <div class="col-md-10">
                                <select name="priority" id="priority" class="form-control select2" data-placeholder="{{ __('Priority') }}">
                                    @foreach(\App\Helpers\Enums\PriorityEnum::cases() as $priority)
                                        <option value="{{ $priority->value }}" @if($ticket->getPriority()?->value == $priority?->value ?? false) selected @endif> {{ $priority->value }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mt-1">
                            <label for="blocked_by_ids" class="col-md-2 col-form-label">@lang('Blocked by')</label>
                            <div class="col-md-10">
                                <select name="blocked_by_ids[]" id="blocked_by_ids" class="form-control blocked-tickets" multiple>
                                    @foreach($ticket->getBlockedByTickets() ?? [] as $blockedTicket)
                                        <option value="{{ $blockedTicket->getId() }}" selected>
                                            #{{ $blockedTicket->getId() }} - {{ $blockedTicket->getName() }}
                                        </option>
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
        $(document).ready(function () {

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

            $(".blocked-tickets").select2({
                placeholder: 'Επιλέξτε tickets...',
                allowClear: true,
                ajax: {
                    type: 'POST',
                    url: "{{ route('api.internal.tickets.search-paginated') }}",
                    dataType: 'json',
                    delay: 250,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: $.map(data.results, function (obj) {
                                return {
                                    id: obj.id,
                                    text: '#' + obj.id + ' - ' + obj.text
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
