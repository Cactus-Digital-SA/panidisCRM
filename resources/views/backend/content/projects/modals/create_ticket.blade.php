@php use App\Domains\Tickets\Models\Ticket;use App\Models\CactusEntity; @endphp
@php
    /** @var CactusEntity $model */
    /** @var Ticket $ticket */

@endphp
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
<div class="modal fade" id="createTicket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl two-factor-auth-apps">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 mx-50">
                <div class="text-center mb-6">
                    <h4 class="modal-title">@lang('Create Ticket')</h4>
                    <p>Add all the following information and details to create a new ticket.</p>
                </div>
                <form id="form" method="POST" action="{{ route('admin.projects.assign.ticket', $model->getTypeId()) }}" class="row g-3"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="morphId" value="{{$model->getId()}}">

                    <div class="col-12 col-md-6">
                        <label class="form-label"> {{ __("Name") }} <i class="fa fa-asterisk fa-2xs text-danger"></i></label>
                        <input type="text" name="name" class="form-control" required/>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{__('Deadline') }}</label>
                        <input type="text" data-flatpickr="date" id="deadline" name="deadline" placeholder="{{__('Deadline')}}" autocomplete="off">
                    </div>


                    <div class="col-12 col-md-6 @if(isset($hideCompany) ?? false) d-none @endif">
                        <label class="form-label">{{__('Company') }}</label>
                        @php
                            if(!isset($company)){
                                $company = $model->getClient()?->getCompany();
                            }
                        @endphp
                        <select name="company_id" id="company_id" class="form-control select2 select_companies" data-placeholder="{{ __('Company Selection') }}" >
                            <option></option>
                            @if(isset($company) ?? false)
                                <option value="{{$company->getId()}}" selected> {{$company->getName()}}  </option>
                            @endif
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">{{__('Assignees') }}
                            {{--                            <i class="fa fa-asterisk fa-2xs text-danger"></i>--}}
                        </label>
                        <select multiple name="assignees[]" class="form-select select2 select_assignees" data-placeholder="{{ __('Assignees Selection') }}" data-allow-clear="true">
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="priority" class="form-label">@lang('Priority')<small class="text-danger"> *</small></label>
                        <select name="priority" id="priority" class="form-control select2 modalSelect2" data-placeholder="{{ __('Priority') }}">
                            @foreach(\App\Helpers\Enums\PriorityEnum::cases() as $priority)
                                <option value="{{ $priority->value }}" > {{ $priority->value }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="blocked_by_ids" class="form-label">@lang('Blocked by')</label>
                        <select name="blocked_by_ids[]" id="blocked_by_ids" class="form-control blocked-tickets" multiple>
                        </select>
                    </div>

                    <div class="row mt-2 mb-2">
                        <div class="col-12">
                            <label class="form-label">Προσθήκη σχολίου</label>
                            <textarea class="form-control autosize" name="note" id="note" rows="3" placeholder="Προσθήκη σχολίου"></textarea>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-bolder" for="files">
                            <i class="ti ti-paperclip cursor-pointer me-1"></i> Επιλογή Αρχείου
                        </label>

                        <div id="file-upload-container" class="form-control">
                            <div class="wrapper row ">
                                <div class="col-auto px-1">
                                    <label for="file-upload" class="file-input-label">Επιλογή Αρχείου <i
                                            class="ti ti-file"></i></label>
                                    <input id="file-upload" type="file" name="files[]" multiple>
                                </div>
                                <div class="col-auto d-flex align-items-center justify-content-center px-1">
                                    <div id="file-name" class="file-name ">Δεν έχει επιλεχθεί αρχείο</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col text-end">
                                <button class="btn btn-primary float-right" type="submit">{{__('Submit')}}</button>
                            </div><!--row-->
                        </div><!--row-->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('after-scripts')
    <script type="module">
        $('#deadline').flatpickr({
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'Y-m-d',
            minDate: new Date(),
            locale: {
                ...flatpickr.l10ns.gr,
                firstDayOfWeek: 1
            },
            static: true,
        });


        const fileInput = document.getElementById('file-upload');
        const fileNameDisplay = document.getElementById('file-name');

        fileInput.addEventListener('change', function () {
            // if (fileInput.files.length === 1) {
            //     fileNameDisplay.textContent = '1 αρχείο';
            // } else if (fileInput.files.length > 1) {
            //     fileNameDisplay.textContent = `${fileInput.files.length} αρχεία`;
            // } else {
            //     fileNameDisplay.textContent = 'Δεν έχει επιλεχθεί αρχείο';
            // }
            if (fileInput.files.length > 0) {
                const names = Array.from(fileInput.files).map(file => file.name).join(', ');
                fileNameDisplay.textContent = names;
            } else {
                fileNameDisplay.textContent = 'Δεν έχει επιλεχθεί αρχείο';
            }
        });
    </script>
    @include('backend.components.js.select')
@endpush
