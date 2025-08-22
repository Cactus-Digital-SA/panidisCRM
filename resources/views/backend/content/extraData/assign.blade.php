@php use App\Domains\ExtraData\Enums\ExtraDataModelsEnum;use App\Domains\ExtraData\Models\ExtraData;
    /**
     *  @var array<ExtraData> $extraData
     * @var ExtraData $data
    */
@endphp
@extends('backend.layouts.app')

@section('title', 'Extra Data')

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Αρχική</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.extraData.index') }}">{{ __('Extra Data') }}</a></li>
    <li class="breadcrumb-item active"> {{ __('Assign') }}</li>
@endsection

@section('vendor-style')
    @include('includes.datatable_styles')
    <style>
        table > thead > tr > th{
            text-align: center !important;
        }
    </style>
@endsection

@section('content-header')
    <div class="col-md-5 content-header-right text-md-end col-md-auto d-md-block d-none mb-2">
        <div class="mb-1 breadcrumb-right">

        </div>
    </div>
@endsection


@section('content')

    <div class="tab-content pt-0">
        <div class="tab-pane fade show active" id="navs-pills-top-details" role="tabpanel">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mt-2 pb-4">
                        <div class="card-header p-4 justify-content-center">
                            <h4 class="card-title">Ανάθεση Extra Data</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <form id="createExtraDataForm" action="{{ route('admin.extraData.assign.store') }}"
                                      method="POST" class="row gy-1 pt-75" enctype="multipart/form-data">
                                    @csrf()
                                    {{--                                    <div class="row d-flex align-items-end mb-50 justify-content-center">--}}
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th width="20%">Model</th>
                                                <th>Δικαιώματα</th>
                                                <th width="20%">Ενέργειες</th>
                                            </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                            @foreach(ExtraDataModelsEnum::values() ?? [] as $model)
                                                <tr>
                                                    <td class="text-center">{{ExtraDataModelsEnum::from($model)->label() ?? ''}}</td>
                                                    <td>
                                                        <label for="extraData_{{$model}}"></label>
                                                        <select name="extraData[{{$model}}][]" id="extraData_{{$model}}"
                                                                class="form-select select2" multiple>
                                                            @foreach($extraData as $data)
                                                                <option value="{{$data->getId()}}"
                                                                    {{ in_array($data->getId(), $selectedData[$model] ?? []) ? 'selected' : '' }}>
                                                                    {{$data->getDescription()}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td style="justify-items: center;">
                                                        <div class="form-check">
                                                            <input id="chk_{{$model}}" data-value="{{$model}}"
                                                                   type="checkbox"
                                                                   class="form-check-input chk_select"><label
                                                                class="form-check-label"
                                                                for="chk_{{$model}}">{{__('Select All')}}</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach


                                            </tbody>
                                        </table>
                                        {{--                                        </div>--}}
                                    </div>

                                    <div class="col-12 text-center mt-2 pt-50">
                                        <button type="submit" class="btn btn-primary me-1">{{__('Save')}} <i
                                                class="fa fa-check ms-2"></i></button>
                                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                                aria-label="Close">
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



@section('page-script')
    <script type="module">
        $('.select2').select2();

        $(".chk_select").click(function () {
            var id = $(this).attr("data-value");
            if ($(this).is(':checked')) {
                $("#extraData_" + id + " > option").prop("selected", "selected");
                $("#extraData_" + id).trigger("change");
            } else {
                $("#extraData_" + id + " > option").removeAttr("selected");
                $("#extraData_" + id).trigger("change");
            }
        });
    </script>

@endsection
