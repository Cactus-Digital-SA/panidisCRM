@php
    use App\Domains\ExtraData\Enums\ExtraDataTypesEnum;
@endphp
@extends('backend.layouts.app')

@section('title', 'Extra Data')

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Αρχική</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.extraData.index') }}">{{ __('Extra Data') }}</a></li>
    <li class="breadcrumb-item active"> {{ __('Edit') }}</li>
@endsection

@section('vendor-style')
    @include('includes.datatable_styles')
@endsection

@section('content-header')
    <div class="col-md-5 content-header-right text-md-end col-md-auto d-md-block d-none mb-2">
        <div class="mb-1 breadcrumb-right">
            <a class="btn btn-success waves-effect waves-float waves-light me-2"
               href="{{route('admin.extraData.create')}}"><i
                    class="ti ti-user-plus ti-xs me-1"></i>
                {{ __("Create Extra Data") }}
            </a>
            <button class="btn btn-info btn-round waves-effect waves-float waves-light"
                    onclick="$('#filters').toggle()">
                <i class="ti ti-filter"></i> {{ __('Filters') }}
            </button>
        </div>
    </div>
@endsection


@section('content')

    <div class="tab-content pt-0">
        <div class="tab-pane fade show active" id="navs-pills-top-details" role="tabpanel">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="card mt-2 pb-4">
                        <div class="card-header p-4 justify-content-center">
                            <h4 class="card-title">Επεξεργασία πεδίου</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <form id="createExtraDataForm" action="{{ route('admin.extraData.update', $extraData->getId()) }}" method="POST" class="row gy-1 pt-75" enctype="multipart/form-data">
                                    @csrf()
                                    @method('PATCH')
                                    <div class="row d-flex align-items-end mb-50 justify-content-center">
                                        <div class="col-10 mb-2">
                                            <label for="name" class="form-label fw-bolder">Όνομα</label>
                                            <input type="text" id="name" name="name" placeholder="Όνομα" class="form-control" value="{{$extraData->getName()}}" required/>
                                        </div>
                                        <div class="col-10 mb-2">
                                            <label for="description" class="form-label fw-bolder">Περιγραφή</label>
                                            <input type="text" id="description" name="description" placeholder="Περιγραφή" class="form-control" value="{{$extraData->getDescription()}}"/>
                                        </div>
                                        <div class="col-10 mb-2">
                                            <label for="type" class="form-label fw-bolder">Τύπος</label>
                                            <select name="type" id="type" class="form-control select2" data-placeholder="" data-allow-clear="true" required>
                                                @foreach ($types ?? [] as $type)
                                                    <option value="{{$type}}" @if($extraData->getType()->value == $type) selected @endif> {{$type}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-10 mb-2 row">
                                            <div class="col-sm-2 col-md-1">
                                                <div id="addRow" class="avatar bg-light-success me-2 align-content-center" style="display:none;">
                                                    <div class="avatar-content">
                                                        <i class="ti ti-plus text-success"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-10 col-md-11" id="repeater">
                                                @foreach(json_decode($extraData->getOptions()) ?? [] as $key => $name)
                                                    <div class="mb-1 row extra-options">
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control" name="option_keys[]" value="{{ $key }}" placeholder="Value">
                                                        </div>
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control" name="option_values[]" value="{{ $name }}" placeholder="Name">
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="avatar bg-light-danger me-2 remove">
                                                                <div class="avatar-content">
                                                                    <i class="ti ti-x remove-row text-danger" style="cursor: pointer;"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-10 mb-2">
                                            <label for="required" class="form-label fw-bolder">{{__('Required') }} </label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" id="required" name="required" class="form-check-input float-start"  value="1" @if($extraData->getRequired()) checked @endif>
                                            </div>
                                        </div>
                                        <div class="col-10 mb-2">
                                            <label for="multiple" class="form-label fw-bolder">{{__('Multiple') }} </label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" id="multiple" name="multiple" class="form-check-input float-start"  value="1" @if($extraData->isMultiple()) checked @endif>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 text-center mt-2 pt-50">
                                        <button type="submit" class="btn btn-primary me-1">{{__('Save')}} <i class="fa fa-check ms-2"></i></button>
                                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                            {{__('Cancel')}}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            const extraOptionHtml = `
        <div class="mb-1 row extra-options">
             <div class="col-sm-5">
                <input type="text" class="form-control" name="option_keys[]" placeholder="Key Name">
            </div>
            <div class="col-sm-5">
                <input type="text" class="form-control" name="option_values[]" placeholder="Value">
            </div>

            <div class="col-sm-2 col-md-1">
                <div class="avatar bg-light-danger me-2 remove align-content-center ">
                    <div class="avatar-content">
                        <i class="ti ti-x remove-row text-danger" style="cursor: pointer;"></i>
                    </div>
                </div>
            </div>
        </div>
    `;

            $('#type').change(function () {
                const isSelectType = $('#type').val() === '{{ ExtraDataTypesEnum::SELECT->value }}';

                if (isSelectType) {
                    // Hide the main options input and show extra options
                    $('#options').attr('type', 'hidden');
                    $('#repeater').append(extraOptionHtml);
                    $('#addRow').show();

                    // Add click event to "Add Row" button
                    $('#addRow').off('click').on('click', function () {
                        $('#repeater').append(extraOptionHtml);
                    });
                } else {
                    // Show the main options input and remove extra options
                    $('#options').attr('type', 'text');
                    $('#addRow').hide();
                    $('.extra-options').remove();
                }
            });

            // Event delegation to handle dynamic "Remove" button clicks
            $('#repeater').on('click', '.remove-row', function () {
                $(this).closest('.extra-options').remove();
            });

            // Trigger change event on page load to set the correct initial state
            $('#type').trigger('change');
        });


    </script>
@endpush
