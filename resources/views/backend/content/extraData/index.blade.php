@php

@endphp

@extends('backend.layouts.app')

@section('title', 'Extra Data')

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Αρχική</a></li>
    <li class="breadcrumb-item">Extra Data</li>
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
            <a class="btn btn-success waves-effect waves-float waves-light me-2"
               href="{{route('admin.extraData.create')}}"><i
                    class="ti ti-user-plus ti-xs me-1"></i>
                {{ __("Create Extra Data") }}
            </a>
            <button class="btn btn-info btn-round waves-effect waves-float waves-light"
                    onclick="jQuery('#filters').toggle()">
                <i class="ti ti-filter"></i> {{ __('Filters') }}
            </button>
        </div>
    </div>
@endsection


@section('content')

    <div class="card overflow-hidden mt-2">
        <div class="card-body p-0 m-0">
            <div class="row">
                <section id="column-selectors">
                    <div class="table-responsive">
                        <table class="table datatable-extra-data dt-select-table">
                            <thead>
                            <tr class="text-center">
                                @foreach($columns as $column)
                                    <th> {{ __($column['name']) }}</th>
                                @endforeach
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody class="text-center">

                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
        <div class="card-footer align-items-center d-flex justify-content-between">
        </div>
    </div>

@endsection

@section('modals')
    @include('backend.components.delete_modal')
@endsection

@section('vendor-script')
    @include('includes.datatable_scripts')
@endsection


@section('page-script')
    @vite([])

    <script type="module">
        let dt_table = $('.datatable-extra-data');
        if (dt_table.length) {
            search();

            function search() {
                if ($.fn.DataTable.isDataTable('.datatable-extra-data')) {
                    dt_table.DataTable().destroy();
                }
                let export_columns = [0,1,2];
                dt_table.DataTable({
                    language: {
                        url: "{{ asset('datatable/el.json') }}",
                        paginate: {
                            previous: "{!! __('pagination.previous') !!}",
                            next: "{!! __('pagination.next') !!}"
                        },
                        "info": "Βλέπετε _START_ έως _END_ αποτελέσματα από _TOTAL_ εγγραφές"
                    },
                    scrollX: true, // Ενεργοποίηση οριζόντιας κύλισης
                    responsive: false,
                    deferRender: true,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    serverMethod: 'post',
                    ajax: {
                        url: "{{ route('api.internal.extra-data.datatable') }}",
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        data: function(data) {
                            data.filterName = $('#filter_name').val();
                            data.filterCategory = $('#filter_category').val();
                        }
                    },
                    pageLength: 15,
                    lengthMenu: [
                        [15, 30, 100],
                        ['15', '30', '100']
                    ],
                    columns: [
                            @foreach($columns as $key => $column)
                        {
                            data: '{{$key}}',
                            name: '{{$column['table']}}',
                            searchable: {{ $column['searchable'] }},
                            orderable: {{ $column['orderable'] }}
                        },
                            @endforeach
                        {data: 'actions', orderable: false, className: 'text-end'}
                    ],
                    columnDefs: [{
                        className: 'control',
                        orderable: false,
                        responsivePriority: 2,
                        targets: 0,
                        visible: false
                    }, ],
                    dom: '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"<"dt-action-buttons text-end"B>f>>t<"d-flex justify-content-between mx-0 row"<"d-flex justify-content-center col-12"i><"d-flex justify-content-center col-12"p>>',
                    "oLanguage": {
                        "sSearch": "Filter Data"
                    },
                    "iDisplayLength": -1,
                    "sPaginationType": "full_numbers",
                    buttons: [{
                        extend: 'collection',

                        className: 'btn btn-outline-secondary dropdown-toggle me-2',
                        text: '<i class="ti ti-logout rotate-n90 me-2"></i>' + '{{ __('locale.Export') }}',
                        buttons: [{
                            extend: 'print',
                            text: '<i class="ti ti-printer me-2" ></i>Print',
                            className: 'dropdown-item',
                            exportOptions: { columns: export_columns }
                        },
                            {
                                extend: 'csv',
                                text: '<i class="ti ti-file-text me-2" ></i>Csv',
                                className: 'dropdown-item',
                                charset: 'utf-8',
                                bom: true,
                                exportOptions: { columns: export_columns }
                            },
                            {
                                extend: 'excel',
                                text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
                                className: 'dropdown-item',
                                exportOptions: { columns: export_columns }
                            },
                            {
                                extend: 'pdf',
                                text: '<i class="ti ti-file-text me-2"></i>Pdf',
                                className: 'dropdown-item',
                                exportOptions: { columns: export_columns }
                            },
                            {
                                extend: 'copy',
                                text: '<i class="ti ti-copy me-1" ></i>Copy',
                                className: 'dropdown-item',
                                exportOptions: { columns: export_columns }
                            }
                        ],
                        init: function(api, node) {
                            $(node).removeClass('btn-secondary');
                            $(node).parent().removeClass('btn-group');
                            setTimeout(function() {
                                $(node).closest('.dt-buttons').removeClass('btn-group')
                                    .addClass('d-inline-flex');
                            }, 50);
                        }
                    }],
                });
            }
            $('#search').on("click", function() {
                search();
            });
        }
    </script>
@endsection
