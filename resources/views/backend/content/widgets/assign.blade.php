@extends('backend.layouts.app')

@section('title', 'Widgets')

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Αρχική</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.widgets.index') }}">{{ __('Widgets') }}</a></li>
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
                            <h4 class="card-title">Ανάθεση Widget</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <form id="createWidgetForm" action="{{ route('admin.widgets.assign.store') }}"
                                      method="POST" class="row gy-1 pt-75" enctype="multipart/form-data">
                                    @csrf()
                                    {{--                                    <div class="row d-flex align-items-end mb-50 justify-content-center">--}}
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th width="20%">Role</th>
                                                <th>Widget</th>
                                                <th width="20%">Ενέργειες</th>
                                            </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                            @foreach($roles ?? [] as $role)
                                                <tr>
                                                    <td class="text-center">{{$role->getName() ?? ''}}</td>
                                                    <td>
                                                        <label for="widget_{{$role->getId()}}"></label>
                                                        <select name="widgets[{{$role->getId()}}][]" id="widget_{{$role->getId()}}" class="form-select select2" multiple>
                                                            @foreach($widgets as $widget)
                                                                <option value="{{$widget->getId()}}" {{ in_array($widget->getId(), $selectedData[$role->getId()] ?? []) ? 'selected' : '' }} >
                                                                    {{$widget->getDescription()}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td style="justify-items: center;">
                                                        <div class="form-check">
                                                            <input id="chk_{{$role->getId()}}" data-value="{{$role->getId()}}"
                                                                   type="checkbox"
                                                                   class="form-check-input chk_select"><label
                                                                class="form-check-label"
                                                                for="chk_{{$role->getId()}}">{{__('Select All')}}</label>
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
    @vite([])

    <script type="module">
        $('.select2').select2();

        $(".chk_select").click(function () {
            var id = $(this).attr("data-value");
            if ($(this).is(':checked')) {
                $("#widget_" + id + " > option").prop("selected", "selected");
                $("#widget_" + id).trigger("change");
            } else {
                $("#widget_" + id + " > option").removeAttr("selected");
                $("#widget_" + id).trigger("change");
            }
        });
    </script>
@endsection
