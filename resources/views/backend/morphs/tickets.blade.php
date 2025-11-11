@php use App\Domains\Tickets\Models\Ticket;use App\Models\CactusEntity; @endphp
@php
    /** @var CactusEntity $model */
    /** @var Ticket $ticket */

    $ticketService = new \App\Domains\Tickets\Services\TicketService((new \App\Domains\Tickets\Repositories\Eloquent\EloqTicketRepository(new \App\Domains\Tickets\Repositories\Eloquent\Models\Ticket())));
    $columns = $ticketService->getTableColumns();
@endphp
@push('after-styles')
    @include('includes.datatable_styles')
    @vite(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss'])
@endpush

<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <table class="datatables-basic tickets-datatable table">
                    <thead>
                    <tr>
                        @foreach($columns as $column)
                            <th> {{ __($column['name']) }}</th>
                        @endforeach
                        <th> {{ __('Actions') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>

@include('backend.content.projects.modals.create_ticket')


@push('after-scripts')
    @include('includes.datatable_scripts')
    <script type="module">
        $(function () {
            let dt_basic_table = $('.tickets-datatable');
            if (dt_basic_table.length) {
                loadColumnsState();

                let filters = [];
                checkUrlParamsAndSetInputs()

                filters = getFiltersFromInputs();

                mySearch(filters);

                function mySearch(filters) {
                    if ($.fn.DataTable.isDataTable('.tickets-datatable')) {
                        dt_basic_table.DataTable().destroy();
                    }

                    let currentFilters = filters;

                    let dt_basic = dt_basic_table.DataTable({
                        // scrollX: true,
                        responsive: false,
                        processing: true,
                        serverSide: true,
                        searching: false,
                        serverMethod: 'post',
                        ajax: {
                            url: "{{ route('admin.datatable.tickets') }}",
                            headers: {
                                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                            },
                            data: function (data) {
                                data.morphableType = '{{ lcfirst(class_basename($model).'s') }}';
                                data.morphableId = '{{ $model->getId() }}';
                                if (currentFilters && typeof currentFilters === 'object') {
                                    Object.keys(currentFilters).forEach(function (key) {
                                        data[key] = currentFilters[key];
                                    });
                                }
                            }
                        },
                        columns: [
                                @foreach($columns as $key => $column)
                            {
                                data: '{{$key}}',
                                name: '{{$column['table']}}',
                                searchable: '{{ $column['searchable'] }}',
                                sortable: '{{ $column['sortable'] ?? false }}'
                            },
                                @endforeach

                            {
                                data: 'actions', searchable: false, orderable: false
                            },
                        ],
                        columnDefs: [
                            {
                                // For Responsive
                                className: 'control',
                                orderable: false,
                                responsivePriority: 2,
                                targets: 0,
                                visible: false
                            }
                        ],
                        order: [[0, 'desc']],
                        dom: '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"<"dt-action-buttons text-end"B>f>>t<"d-flex justify-content-between mx-0 row"<"d-flex justify-content-center col-12"i><"d-flex justify-content-center col-12"p>>',
                        displayLength: 10,
                        lengthMenu: [10, 25, 50, 100],
                        buttons: [
                            {
                                text: '<i class="ti ti-plus me-md-1"></i><span class="d-md-inline-block d-none">{{ __('Create') }}',
                                className: 'create-new mx-1 btn btn-primary',
                                attr: {
                                    'data-bs-toggle': 'modal',
                                    'data-bs-target': '#createTicket'
                                },
                                init: function (api, node) {
                                    $(node).removeClass('btn-secondary');
                                }
                            },
                            {
                                extend: 'collection',
                                className: 'btn btn-outline-secondary dropdown-toggle me-2',
                                text: '<i class="ti ti-logout rotate-n90 me-2"></i>' + '{{ __('locale.Export')  }}',
                                {{--text: feather.icons['share'].toSvg({ class: 'font-small-4 me-50' }) + '{{ __('locale.Export')  }}',--}}
                                buttons: [
                                    {
                                        extend: 'print',
                                        text: '<i class="ti ti-printer me-2" ></i>Print',
                                        className: 'dropdown-item',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    },
                                    {
                                        extend: 'csv',
                                        text: '<i class="ti ti-file-text me-2" ></i>Csv',
                                        className: 'dropdown-item',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    },
                                    {
                                        extend: 'excel',
                                        text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
                                        className: 'dropdown-item',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    },
                                    {
                                        extend: 'pdf',
                                        text: '<i class="ti ti-file-text me-2"></i>Pdf',
                                        className: 'dropdown-item',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    },
                                    {
                                        extend: 'copy',
                                        text: '<i class="ti ti-copy me-1" ></i>Copy',
                                        className: 'dropdown-item',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    }
                                ],
                                init: function (api, node) {
                                    $(node).removeClass('btn-secondary');
                                    $(node).parent().removeClass('btn-group');
                                    setTimeout(function () {
                                        $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                                    }, 50);
                                }
                            },
                        ],
                        // responsive: {
                        //     details: {
                        //         display: $.fn.dataTable.Responsive.display.modal({
                        //             header: function (row) {
                        //                 var data = row.data();
                        //                 return 'Details of ' + data['name'];
                        //             }
                        //         }),
                        //         type: 'column',
                        //         renderer: function (api, rowIdx, columns) {
                        //             var data = $.map(columns, function (col) {
                        //                 return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                        //                     ? '<tr data-dt-row="' +
                        //                     col.rowIdx +
                        //                     '" data-dt-column="' +
                        //                     col.columnIndex +
                        //                     '">' +
                        //                     '<td>' +
                        //                     col.title +
                        //                     ':' +
                        //                     '</td> ' +
                        //                     '<td>' +
                        //                     col.data +
                        //                     '</td>' +
                        //                     '</tr>'
                        //                     : '';
                        //             }).join('');
                        //
                        //             return data ? $('<table class="table"/>').append('<tbody>' + data + '</tbody>') : false;
                        //         }
                        //     }
                        // },
                        language: {
                            paginate: {
                                next: '<i class="icon-base ti ti-chevron-right scaleX-n1-rtl icon-18px"></i>',
                                previous: '<i class="icon-base ti ti-chevron-left scaleX-n1-rtl icon-18px"></i>',
                                first: '<i class="icon-base ti ti-chevrons-left scaleX-n1-rtl icon-18px"></i>',
                                last: '<i class="icon-base ti ti-chevrons-right scaleX-n1-rtl icon-18px"></i>'
                            },
                            "lengthMenu": "{{__('locale.Show')}} _MENU_ {{__('locale.Entries')}}",
                            "zeroRecords": "{{__('locale.Nothing Found')}}",
                            "info": "{{__('locale.Showing')}} _START_ {{__('until')}} _END_ {{__('locale.Entries')}}",
                            "infoEmpty": "{{__('locale.Nothing Found')}}",
                            "loadingRecords": "{{ __('locale.Loading')  }}",
                            sProcessing: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>',
                            "search": "{{ __('locale.Search') }}",
                        },
                    });

                    //Initial
                    let columns = $('.column-toggle')

                    columns.each(function () {
                        const columnIndex = $(this).val();
                        const column = dt_basic.column(columnIndex);

                        if ($(this).is(':checked')) {
                            column.visible(true);
                        } else {
                            column.visible(false);
                        }

                        // Save the selected columns
                        saveColumnsState();
                    });

                    //OnChange
                    columns.on('change', function () {
                        const columnIndex = $(this).val();
                        const column = dt_basic.column(columnIndex);

                        if ($(this).is(':checked')) {
                            column.visible(true);
                        } else {
                            column.visible(false);
                        }

                        // Save the selected columns
                        saveColumnsState();
                    });
                }

                function setCookie(name, value, days) {
                    let expires = "";
                    if (days) {
                        const date = new Date();
                        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                        expires = "; expires=" + date.toUTCString();
                    }
                    document.cookie = name + "=" + (value || "") + expires + "; path=/";
                }

                function getCookie(name) {
                    const nameEQ = name + "=";
                    const ca = document.cookie.split(';');
                    for (let i = 0; i < ca.length; i++) {
                        let c = ca[i];
                        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
                    }
                    return null;
                }

                function saveColumnsState() {
                    let selectedColumns = [];
                    $('.column-toggle:checked').each(function () {
                        selectedColumns.push($(this).val());
                    });
                    setCookie('selectedColumns_{{ class_basename($model) }}_{{ $model->getId() }}', JSON.stringify(selectedColumns), 365); // Store for 7 days
                }

                function loadColumnsState() {
                    const selectedColumns = JSON.parse(getCookie('selectedColumns_{{ class_basename($model) }}_{{ $model->getId() }}'));
                    if (selectedColumns) {
                        selectedColumns.forEach(function (value) {
                            $('#toggleColumn' + value).prop('checked', true).trigger('change');
                        });
                    } else {
                        @foreach($columns as $column)
                        $('#toggleColumn{{$loop->index}}').prop('checked', true).trigger('change');
                        @endforeach
                    }
                }

                $('div.head-label').html('<h6 class="mb-0">{{ __('Projects') }}</h6>');


                $('#search').on("click", function () {

                    let filters = getFiltersFromInputs();

                    updateUrlWithFilters(filters)
                    mySearch(filters);
                });

                function getFiltersFromInputs(){
                    filters = [];
                    filters.filterName = $('#filter_name').val();
                    filters.filterOwner = $('#filter_owner').val();
                    filters.filterAssignees = $('#filter_assignees').val();
                    filters.filterStatus = $('#filter_status').val();
                    filters.filterPriority = $('#filter_priority').val();
                    filters.filterClient = $('#filter_client').val();

                    let start_date_start = $('#filter_start_date_start').val();
                    let start_date_end = $('#filter_start_date_end').val();
                    if (start_date_start || start_date_end) {
                        filters.filterStartDate = [start_date_start, start_date_end];
                    }


                    let deadline_start = $('#filter_deadline_start').val();
                    let deadline_end = $('#filter_deadline_end').val();
                    if (deadline_start || deadline_end) {
                        filters.filterDeadline = [deadline_start, deadline_end];
                    }

                    return filters;
                }

                // Function to update URL with filters as query parameters
                function updateUrlWithFilters(filters) {
                    let searchParams = new URLSearchParams(window.location.search);

                    // Append filters to the search parameters
                    Object.keys(filters).forEach(function (key) {
                        if (filters[key] && filters[key] != '') { // Only append if the filter is not empty or null
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

                    // Set the input values based on URL parameters if they exist
                    if (searchParams.has('filterOwner')) {
                        let ownerId = searchParams.get('filterOwner');
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('api.internal.users.getUserById') }}", // Your API endpoint to fetch owner details
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                id: ownerId
                            },
                            success: function (data) {
                                if (data) {
                                    let newOption = new Option(data.name, data.id, true, true);
                                    $('.filter_owner').append(newOption).trigger('change');
                                }
                            }
                        });
                    }
                    if (searchParams.has('filterAssignees')) {

                        let assignees = searchParams.get('filterAssignees').split(',');

                        $.each( assignees, function( index ,id ) {
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('api.internal.users.getUserById') }}", // Your API endpoint to fetch owner details
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    id: id
                                },
                                success: function (data) {
                                    if (data) {
                                        let newOption = new Option(data.name, data.id, true, true);
                                        $('#filter_assignees').append(newOption).trigger('change');
                                    }
                                }
                            });
                        });
                    }

                    if (searchParams.has('filterClient')) {
                        let clients = searchParams.get('filterClient').split(',');

                        $.each( clients, function( index ,id ) {
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('api.internal.clients.getClientWithCompanyByClientId') }}", // Your API endpoint to fetch owner details
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    id: id
                                },
                                success: function (data) {
                                    if (data) {
                                        let newOption = new Option(data.company.name, data.client.id, true, true);
                                        $('#filter_client').append(newOption).trigger('change');
                                    }
                                }
                            });
                        });
                    }

                    if (searchParams.has('filterName')) {
                        $('#filter_name').val(searchParams.get('filterName'));
                    }
                    if (searchParams.has('filterStatus')) {
                        $('#filter_status').val(searchParams.get('filterStatus')).trigger('change');
                    }
                    if (searchParams.has('filterPriority')) {
                        $('#filter_priority').val(searchParams.get('filterPriority')).trigger('change');
                    }

                    // Handle date filters (arrays)
                    if (searchParams.has('filterStartDate')) {
                        let startDates = searchParams.get('filterStartDate').split(',');
                        $('#filter_start_date_start').val(startDates[0]);
                        $('#filter_start_date_end').val(startDates[1]);
                    }
                    if (searchParams.has('filterDeadline')) {
                        let deadlineDates = searchParams.get('filterDeadline').split(',');
                        $('#filter_deadline_start').val(deadlineDates[0]);
                        $('#filter_deadline_end').val(deadlineDates[1]);
                    }
                }

                let elementsArray = document.querySelectorAll(".enter_filter");

                elementsArray.forEach(function (elem) {
                    elem.addEventListener("keypress", function () {
                        if (event.key === "Enter") {
                            let filters = getFiltersFromInputs();
                            mySearch(filters);
                        }
                    });
                });
            }
        });
    </script>
@endpush
