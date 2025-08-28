@extends('backend.layouts.app')

@section('title', 'Leads')

@section('vendor-style')
    @include('includes.datatable_styles')
    @vite([])
@endsection

@section('page-style')
    @vite([])
@endsection

@section('content-header')
    <div class="col-md-5 content-header-right text-md-end">
        <div class="d-flex flex-nowrap mb-md-2 breadcrumb-right d-flex justify-content-center justify-content-md-end">
            <a class="col-6 col-md-auto btn btn-success d-inline-flex align-items-center waves-effect waves-float waves-light me-2 mb-lg-0"
               href="{{ route('admin.leads.create') }}" >
                <i class="ti ti-square-plus me-1"></i> Δημιουργία
            </a>
            <button class="col-6 col-md-auto btn btn-info d-inline-flex align-items-center waves-effect waves-float waves-light mb-lg-0"
                onclick="jQuery('#filters').toggle()">
                <i class="ti ti-filter me-1"></i> Φίλτρα
            </button>
        </div>
    </div>
@endsection

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Αρχική</a>
    </li>
    <li class="breadcrumb-item active">Leads
    </li>
@endsection

@section('content')

    <!-- Search Bar -->
    <div class="col-12 mb-4 mt-md-2 mt-3">
        <div id="filters" class="col-12 card card-accent-info mt-card-accent" style="display: none;">
            <div class="card-body p-0">
                <div class="row justify-content-end card-header">
                    <div class="col-md-3 col-12 mb-md-0 mb-2">
                        <input type="text" class="form-control" placeholder="Όνομα" id="filterName" name="filterName"/>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="form-group row p-0 m-0">
                            <div class="col-md-12">
                                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                    <button style="width: 100%;" id="search" name="search"
                                        class="btn btn-success mb-1 waves-effect waves-light" data-toggle="tooltip">
                                        <i class="fa fa-search me-1"></i> Αναζήτηση
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Search Bar -->

    <div class="card overflow-hidden mt-2">
        <div class="card-body p-0 m-0">
            <div class="row">
                <section id="column-selectors">
                    <div class="table-responsive">
                        <table class="table datatable-leads dt-select-table">
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
    <script type="module">
        let dt_table = $('.datatable-leads');
        let filters = [];

        if (dt_table.length) {
            search();

            function search() {
                if ($.fn.DataTable.isDataTable('.datatable-leads')) {
                    dt_table.DataTable().destroy();
                }
                let export_columns = [0,1,2];
                dt_table.DataTable({
                    language: {
                        url: '{{ asset('datatable/el.json') }}',
                        paginate: {
                            previous: '{!! __('pagination.previous') !!}',
                            next: '{!! __('pagination.next') !!}'
                        },
                        'info': '{!! __('pagination.info') !!}'
                    },
                    scrollX: true, // Ενεργοποίηση οριζόντιας κύλισης
                    responsive: false,
                    deferRender: true,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    serverMethod: 'post',
                    ajax: {
                        url: "{{ route('api.internal.leads.datatable') }}",
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        data: function(data) {
                            if (filters && typeof filters === 'object') {
                                Object.keys(filters).forEach(function (key) {
                                    data[key] = filters[key];
                                });
                            }
                            @if(isset($status))  data.filterStatus = {{$status}} @endif;
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
                            searchable: '{{ $column['searchable'] }}',
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


            $('#search').on("click", function () {

                let filters = getFiltersFromInputs();

                updateUrlWithFilters(filters)
                search(filters);
            });

            function getFiltersFromInputs(){
                filters = [];
                filters.filterName = $('#filterName').val();
                filters.filterStatus = $('#filterStatus').val();
                @if(isset($status)) filters.filterStatus = {{$status}} @endif;

                return filters;
            }

            // Function to update URL with filters as query parameters
            function updateUrlWithFilters(filters) {
                let searchParams = new URLSearchParams(window.location.search);

                // Append filters to the search parameters
                Object.keys(filters).forEach(function (key) {
                    if (filters[key] && filters[key] !== '') { // Only append if the filter is not empty or null
                        searchParams.set(key, filters[key]);
                    } else {
                        searchParams.delete(key); // Remove empty filters from URL
                    }
                });

                // Update the URL without reloading the page
                const newUrl = window.location.pathname + '?' + searchParams.toString();
                history.pushState(null, '', newUrl);
            }

            function checkUrlParamsAndSetInputs() {
                let searchParams = new URLSearchParams(window.location.search);
                $('#filterName').val(searchParams?.get('filterName'));
                $('#filterTypeId').val(searchParams?.get('filterTypeId')).trigger('change');
                $('#filterDoyId').val(searchParams?.get('filterDoyId')).trigger('change');
            }

        }
    </script>
@endsection


@push('after-scripts')


@endpush
