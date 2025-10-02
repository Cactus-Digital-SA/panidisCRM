@php
    /**
    * @var App\Domains\Auth\Models\Role $role
    * @var array<\App\Domains\Auth\Models\Role> $roles
    * */
@endphp

@extends('backend.layouts.app')

@section('title', __('locale.Users'))

@section('vendor-style')
    @include('includes.datatable_styles')
    @vite([])
    <style>
        table.dataTable th, table.dataTable td{
            min-width: 150px !important;
            max-width: 150px !important;
            vertical-align: middle;
        }
    </style>
@endsection

@section('content-header')
    <div class="col-md-8">
        <ul class="nav nav-pills flex-column flex-sm-row mb-2">
            <li class="nav-item"><a class="nav-link {{ activeClass(request()->is('admin/users'),'active') }}" href="{{route('admin.users.index')}}"><i class="ti ti-user-check ti-xs me-1"></i> Όλοι</a></li>
            <li class="nav-item"><a class="nav-link {{ activeClass(request()->is('admin/users/deactivated'),'active') }}" href="{{route('admin.users.deactivated')}}"><i class="ti ti-user-off ti-xs me-1"></i> Απενεργοποιημένοι</a></li>
            <li class="nav-item"><a class="nav-link {{ activeClass(request()->is('admin/users/deleted'),'active') }}" href="{{route('admin.users.deleted')}}"><i class="ti ti-user-x ti-xs me-1"></i> Διαγραμμένοι</a></li>
        </ul>
    </div>

    <div class="col-md-4 content-header-right text-md-end col-md-auto d-md-block d-none mb-2">
        <div class="mb-1 breadcrumb-right">
            <a class="btn btn-success waves-effect waves-float waves-light me-2" href="{{route('admin.users.create')}}"><i class="ti ti-user-plus ti-xs me-1"></i> Δημιουργία Χρήστη</a>
            <button class="btn btn-info btn-round waves-effect waves-float waves-light" onclick="jQuery('#filters').toggle()">
                <i class="ti ti-filter"></i> Φίλτρα
            </button>
        </div>
    </div>
@endsection

@section('content')
    <!-- Search Bar -->
    <div class="col-12 mb-4">
        <div id="filters" class="col-12 card card-accent-info mt-card-accent">
            <div class="card-body p-0">
                <div class="row justify-content-end card-header">
                    <div class="col-md-2 col-12">
                        <input type="text" class="form-control" placeholder="Όνομα" id="filter_name"/>
                    </div>
                    <div class="col-md-2-5 col-12">
                        <select name="filter_user_email" id="filter_user_email" class="form-control select2 filter_user_email" data-placeholder="Email Χρήστη">
                        </select>
                    </div>
                    <div class="col-md-2-5 col-12">
                        <select name="filter_role" id="filter_role" class="form-control select2" data-placeholder="Ρόλος">
                            <option value="">---</option>
                            @foreach($roles as $role)
                                <option value="{{$role->getId()}}" @if(isset($selectedRole) && $selectedRole == $role->getName()) selected @endif>{{$role->getName()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2-5 col-12">
                        <div class="form-group row p-0 m-0">
                            <div class="col-md-12">
                                <div class="ButtonToolbar" style="position:relative; top:10%;" role="toolbar" aria-label="Toolbar with button groups">
                                    <button style="width: 90%;" id="search" name="search" class="btn btn-success mr-1 mb-1 waves-effect waves-light" data-toggle="tooltip"><i class="fa fa-search me-2" ></i> Αναζήτηση</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Search Bar -->


    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="datatables-basic users-datatable table">
                            <thead>
                            <tr>
    {{--                            <th></th>--}}
    {{--                            <th></th>--}}
                                <th>id</th>
                                <th>{{ __('locale.Name') }}</th>
                                <th>Email</th>
                                <th>{{__('Role')}}</th>
                                <th>Κατάσταση</th>
                                <th>Τελευταία Σύνδεση</th>
                                @if($status == 1)
                                    <th>Επαναφορά</th>
                                @else
                                    <th class="text-end">Λειτουργίες</th>
                                @endif
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


@section('vendor-script')
    @vite([])
@endsection

@section('page-script')
    @include('includes.datatable_scripts')
    @vite([])

    <script type="module">
        $(document).ready(function () {


        var dt_basic_table = $('.users-datatable');
        if (dt_basic_table.length) {
            mySearch();
            function mySearch() {
                if ( $.fn.DataTable.isDataTable('.users-datatable') ) {
                    $('.users-datatable').DataTable().destroy();
                }
                let export_columns = [1,2,3];

                var dt_basic = dt_basic_table.DataTable({
                    scrollX: true, // Ενεργοποίηση οριζόντιας κύλισης
                    responsive: false,
                    deferRender: true,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    serverMethod: 'post',
                    ajax: {
                        url: "{{ route('admin.datatable.users') }}",
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        data: function (data) {
                            data.filterName = $('#filter_name').val();
                            data.filterUserEmail = $('#filter_user_email').val();
                            data.filterRole = $('#filter_role').val();

                            @if(isset($active) && $active==0)
                                data.active = {{$active}};
                            @else
                                data.status = {{$status}};
                                data.active = 1;
                            @endif
                        }
                    },
                    columns: [
                        { data: 'id', name: 'users.id' },
                        // { data: 'id' },
                        // { data: 'id' }, // used for sorting so will hide this column
                        { data: 'name' , name: 'users.name'},
                        { data: 'email' , name: 'users.email'},
                        { data: 'roles' , name: 'roles.name', orderable: false },
                        { data: 'online_status' },
                        { data: 'last_login' , name: 'users.last_login_at'},
                            @if($status == 1)
                        {
                            data: 'restore',
                            visible: {{ $status == 1 ? 1 : 0 }},
                            orderable: false,
                            className: 'text-end'
                        },
                            @else
                        {
                            data: 'more',
                            visible: {{ $status == 0 ? 1 : 0 }},
                            orderable: false,
                            className: 'text-end'
                        },
                        @endif
                    ],
                    columnDefs: [
                        {
                            // For Responsive
                            className: 'control',
                            orderable: true,
                            responsivePriority: 2,
                            targets: 0
                        },
                        // {
                        //     // For Checkboxes
                        //     targets: 1,
                        //     orderable: false,
                        //     responsivePriority: 3,
                        //     render: function (data, type, full, meta) {
                        //         return (
                        //             '<div class="form-check"> <input class="form-check-input dt-checkboxes" type="checkbox" value="" id="checkbox' +
                        //             data +
                        //             '" /><label class="form-check-label" for="checkbox' +
                        //             data +
                        //             '"></label></div>'
                        //         );
                        //     },
                        //     checkboxes: {
                        //         selectAllRender:
                        //             '<div class="form-check"> <input class="form-check-input" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>'
                        //     }
                        // },
                        // {
                        //     targets: 2,
                        //     visible: false
                        // },
                        {
                            responsivePriority: 1,
                            targets: 4
                        }
                    ],
                    order: [[0, 'desc']],
                    // dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
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
                    displayLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    // responsive: {
                    //     details: {
                    //         display: $.fn.dataTable.Responsive.display.modal({
                    //             header: function (row) {
                    //                 var data = row.data();
                    //                 return 'Details of ' + data['full_name'];
                    //             }
                    //         }),
                    //         type: 'column',
                    //         renderer: function (api, rowIdx, columns) {
                    //             var data = $.map(columns, function (col, i) {
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
            }
            $('div.head-label').html('<h6 class="mb-0">Χρήστες</h6>');


            $('#search').on("click", function () {
                mySearch();
            });

        }

        $(".filter_user_email").select2({
            placeholder: 'Αναζήτηση...',
            allowClear: true,
            ajax: {
                type: 'POST',
                delay: 500,
                url: "{{ route('api.internal.users.emailsPaginated') }}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    }
                },
                // processResults: function (data, params) {
                //     return data
                // },
                processResults: function (data, params) {
                    return {
                        results: $.map(data.results, function(obj) {
                            return { id: obj.text, text: obj.text }; // Use email as both id and text
                        })
                    };
                },
                cache: true
            }
        });

        });
    </script>
@endsection

@push('after-scripts')

@endpush
