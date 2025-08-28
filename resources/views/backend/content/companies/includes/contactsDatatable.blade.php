@section('after-style')
    @include('includes.datatable_styles')
@endsection

@if($contactsColumns)
    <div class="card">
        <div class="card-header border-bottom">
            <h4 class="card-title m-0">Επαφές</h4>
            <div class="align-self-center">
                <a class="btn btn-outline-primary waves-effect" data-bs-target="#assign-user" data-bs-toggle="modal"> <i class="fa fa-plus me-2"></i> Προσθήκη Επαφής</a>
            </div>
        </div>
        <div class="card-body text-center col-12 align-self-center p-0">
            <div class="table-responsive">
                <table class="datatables-basic datatable-contacts table">
                    <thead>
                        <tr class="text-center">
                            @foreach($contactsColumns as $column)
                                <th> {{ __($column['name']) }}</th>
                            @endforeach
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">

                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="assign-user" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <div class="d-flex justify-content-end">
                            <a data-bs-target="#add-user" data-bs-toggle="modal" class="btn btn-primary btn-sm text-white">Δημιουργία Επαφής</a>
                        </div>
                        <h2 class="mb-1">Επιλογή Επαφής</h2>
                    </div>
                    <form action="{{route('admin.companies.contacts.add',[$company->getId()])}}" method="post" class="row gy-1 pt-75">
                        @csrf()
                        <div class="col-12">
                            <label class="form-label" for="user">Επαφές</label>
                            <div>
                                <select name="userIds[]" id="user" class="form-select user-select select2" multiple data-allow-clear="true">
                                    <option value="" disabled="disabled">Επιλογή Επαφής</option>
                                    @foreach($users ?? [] as $user)
                                        @if(!in_array($user->getId(), array_map(fn($u) => $u->getId(), $company->getUsers())))
                                            <option value="{{$user->getId()}}">
                                                {{$user->getName()}}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1">Σύνδεση <i class="fa fa-check ms-2"></i></button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                Άκυρο
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="add-user" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-add-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h2 class="mb-1">Δημιουργία Επαφής</h2>
                    </div>
                    <form action="{{route('admin.companies.contacts.addNew',[$company->getId()])}}" method="post" class="row gy-1 pt-75">
                        @csrf()
                        <div class="col-12">
                            <div class="form-group row mb-1 mt-1">
                                <label for="name" class="col-md-2 col-form-label">Όνομα</label>
                                <div class="col-md-10">
                                    <input type="text" name="firstName" class="form-control" placeholder="Όνομα" value="{{ old('firstName') }}" maxlength="100" required />
                                    <div class="invalid-feedback"> Το Όνομα είναι απαραίτητο. </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1 mt-1">
                                <label for="name" class="col-md-2 col-form-label">Επώνυμο</label>
                                <div class="col-md-10">
                                    <input type="text" name="lastName" class="form-control" placeholder="Επώνυμο" value="{{ old('lastName') }}" maxlength="100" required />
                                    <div class="invalid-feedback"> Το Επώνυμο είναι απαραίτητο. </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1 mt-1">
                                <label for="email" class="col-md-2 col-form-label">@lang('E-mail Address')</label>
                                <div class="col-md-10">
                                    <input type="email" name="email" class="form-control" placeholder="{{ __('E-mail Address') }}" value="{{ old('email') }}" maxlength="255" required />
                                    <div class="invalid-feedback"> Το Email είναι απαραίτητο. </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1 mt-1">
                                <label for="email" class="col-md-2 col-form-label">Τηλέφωνο</label>
                                <div class="col-md-10">
                                    <input type="text" name="phone" class="form-control" placeholder="Τηλέφωνο" value="{{ old('phone') }}" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1">Προσθήκη <i class="fa fa-check ms-2"></i></button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                Άκυρο
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@push('extra-modals')
    @include('backend.components.delete_modal')
@endpush

@push('after-scripts')
    @include('includes.datatable_scripts')
    <script type="module">
        let dt_table = $('.datatable-contacts');
        let filters = [];

        if (dt_table.length) {
            search();
            function search() {
                if ($.fn.DataTable.isDataTable('.datatable-contacts')) {
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
                    // scrollX: true, // Ενεργοποίηση οριζόντιας κύλισης
                    responsive: false,
                    deferRender: true,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    serverMethod: 'post',
                    lengthChange: false,
                    ajax: {
                        url: "{{ route('api.internal.company.contacts.datatable', $company?->getId()) }}",
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        data: function(data) {
                            if (filters && typeof filters === 'object') {
                                Object.keys(filters).forEach(function (key) {
                                    data[key] = filters[key];
                                });
                            }
                        }
                    },
                    pageLength: 15,
                    lengthMenu: [
                        [15, 30, 100],
                        ['15', '30', '100']
                    ],
                    columns: [
                        @foreach($contactsColumns as $key => $column)
                        {
                            data: '{{$key}}',
                            name: '{{$column['table']}}',
                            searchable: '{{ $column['searchable'] }}',
                            orderable: '{{ $column['orderable'] }}'
                        },
                        @endforeach
                        {data: 'actions', orderable: false, className: 'text-end'}
                    ],
                    columnDefs: [
                        {
                            className: 'control',
                            orderable: false,
                            responsivePriority: 2,
                            targets: 0,
                            visible: false
                        },
                    ],
                    dom: '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"<"dt-action-buttons text-end"B>f>>t<"d-flex justify-content-between m-4 row"<"d-flex justify-content-center col-12"i><"d-flex justify-content-center col-12"p>>',
                    "oLanguage": {
                        "sSearch": "Filter Data"
                    },
                    "iDisplayLength": -1,
                    "sPaginationType": "full_numbers",
                    buttons: [

                    ],
                    initComplete: function () {
                        if ($('.dt-buttons').is(':empty')) {
                            $('.dt-action-buttons').hide();
                        }
                    },
                });
            }


            $('#search').on("click", function () {
                search();
            });
        }

        let userSelect = $('.user-select');
        userSelect.each(function () {
            let $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Αναζήτηση...',
                allowClear: true,
                dropdownParent: $this.parent(),
                ajax: {
                    type: 'POST',
                    delay: 500,
                    url: "{{ route('api.internal.contacts.namesPaginated') }}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    // processResults: function (data, params) {
                    //     return data
                    // },
                    processResults: function (data) {
                        return {
                            results: $.map(data.results, function (obj) {
                                return {id: obj.id, text: obj.text}; // Use id and name
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endpush
