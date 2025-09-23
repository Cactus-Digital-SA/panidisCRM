@extends('backend.layouts.app')

@section('title', 'Dashboard - CRM')

@section('vendor-style')
    @include('includes.datatable_styles')
@endsection

@section('content-header')

@endsection

@section('content')

    <div class="row g-7 mb-4">
        <!-- Follow Ups -->
        <div class="col-xxl-3 col-md-3 col-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="badge p-2 bg-label-danger mb-3 rounded"><i class="icon-base ti ti-clipboard-text icon-28px"></i>
                    </div>
                    <h5 class="card-title mb-1"> Follow Ups Due</h5>
                    <p class="card-subtitle "></p>
                    <p class="text-heading mb-3 mt-1">0</p>
                </div>
            </div>
        </div>

        <!-- Hot Deals -->
        <div class="col-xxl-3 col-md-3 col-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="badge p-2 bg-label-warning mb-3 rounded"><i class="icon-base ti ti-alert-triangle icon-28px"></i>
                    </div>
                    <h5 class="card-title mb-1">Hot Deals</h5>
                    <p class="card-subtitle ">Quoting</p>
                    <p class="text-heading mb-3 mt-1">0</p>
                </div>
            </div>
        </div>

        <!-- Quotes Pending -->
        <div class="col-xxl-3 col-md-3 col-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="badge p-2 bg-label-success mb-3 rounded"><i class="icon-base ti ti-clipboard-text icon-28px"></i>
                    </div>
                    <h5 class="card-title mb-1">Quotes Pending</h5>
                    <p class="card-subtitle "></p>
                    <p class="text-heading mb-3 mt-1">0</p>
                </div>
            </div>
        </div>

        <!-- Follow Ups -->
        <div class="col-xxl-3 col-md-3 col-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="badge p-2 bg-label-danger mb-3 rounded"><i class="icon-base ti ti-clipboard-text icon-28px"></i>
                    </div>
                    <h5 class="card-title mb-1"> Follow Ups Due</h5>
                    <p class="card-subtitle "></p>
                    <p class="text-heading mb-3 mt-1">0</p>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-6 mb-4">
        <!-- Today's Scheduled Visits -->
        <div class="col-md-6 col-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 me-2">Today's Scheduled Visits</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless border-top datatable-visits">
                        <thead class="border-bottom">
                            <tr>
                                @foreach($visitsColumns ?? [] as $column)
                                    <th> {{ __($column['name']) }}</th>
                                @endforeach
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/ Today's Scheduled Visits -->

        <!-- Follow Up's Reminders -->
        <div class="col-md-6 col-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 me-2">Follow Up's Reminders</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless border-top datatable-visits-follow-up">
                        <thead class="border-bottom">
                        <tr>
                            @foreach($visitsColumns ?? [] as $column)
                                <th> {{ __($column['name']) }}</th>
                            @endforeach
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/ Last Transaction -->
    </div>

@endsection

@section('vendor-script')
{{--        @vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])--}}
    @include('includes.datatable_scripts')

@endsection

@section('page-script')
    <script type="module">
        let filters = [];
        let dtVisitsOpen;
        let dtVisitsFollowUp;

        function initVisitsDatatable(selector, url) {
            let export_columns = [0,1,2];
            return $(selector).DataTable({
                language: {
                    url: '{{ asset('datatable/el.json') }}',
                    paginate: {
                        previous: '{!! __('pagination.previous') !!}',
                        next: '{!! __('pagination.next') !!}'
                    },
                    'info': '{!! __('pagination.info') !!}'
                },
                scrollX: true,
                responsive: false,
                deferRender: true,
                processing: true,
                serverSide: true,
                searching: false,
                serverMethod: 'post',
                ajax: {
                    url: url,
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
                pageLength: 10,
                lengthMenu: [
                    [10, 30, 100],
                    ['10', '30', '100']
                ],
                columns: [
                    @foreach($visitsColumns ?? [] as $key => $column)
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

        $(document).ready(function() {
            dtVisitsOpen = initVisitsDatatable('.datatable-visits', "{{ route('api.internal.visits.datatable.dashboard') }}");
            dtVisitsFollowUp = initVisitsDatatable('.datatable-visits-follow-up', "{{ route('api.internal.visits.datatable.followup') }}");

            $('#search').on("click", function () {
                filters = getFiltersFromInputs();
                dtVisitsOpen.ajax.reload();
                dtVisitsFollowUp.ajax.reload();
            });

            function getFiltersFromInputs(){
                let newFilters = [];
                newFilters.filterName = $('#filterName').val();
                newFilters.filterStatus = $('#filterStatus').val();
                newFilters.filterType = $('#filterType').val();
                newFilters.filterNextAction = $('#filterNextAction').val();

                @if(isset($status)) newFilters.filterStatus = {{$status}} @endif;

                return newFilters;
            }
        });
    </script>

@endsection
