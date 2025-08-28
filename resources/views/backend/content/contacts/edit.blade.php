@extends('backend.layouts.app')

@section('title', 'Επεξεργασία Επαφής')

@section('vendor-style')
    @vite([])
@endsection

@section('page-style')
    @vite([])
@endsection

@section('content-header')
    <div class="content-header-right text-md-end col-md-5 d-md-block d-none mb-2 header-btn">
        <div class="mb-1 breadcrumb-right">
            <div class="col-12 d-flex ms-auto justify-content-end p-0">

            </div>
        </div>
    </div>
@endsection

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Αρχική</a>
    </li>
    <li class="breadcrumb-item active">Επεξεργασία
    </li>
@endsection

@section('content')

    <div class="col-md-10 offset-md-1">

    <form id="form" method="POST" action="{{ route('admin.contacts.update', $user->getId()) }}" class="form-horizontal needs-validation"
          enctype="multipart/form-data" novalidate>
        @method('PATCH')
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="form-group row mb-1 mt-1">
                    <label for="name" class="col-md-2 col-form-label">Όνομα</label>
                    <div class="col-md-10">
                        <input type="text" name="firstName" class="form-control" placeholder="Όνομα" value="{{ $user->getUserDetails()->getFirstName()}}" maxlength="100" required />
                        <div class="invalid-feedback"> Το Όνομα είναι απαραίτητο. </div>
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="name" class="col-md-2 col-form-label">Επώνυμο</label>
                    <div class="col-md-10">
                        <input type="text" name="lastName" class="form-control" placeholder="Επώνυμο" value="{{ $user->getUserDetails()->getLastName() }}" maxlength="100" required />
                        <div class="invalid-feedback"> Το Επώνυμο είναι απαραίτητο. </div>
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="email" class="col-md-2 col-form-label">@lang('E-mail Address')</label>
                    <div class="col-md-10">
                        <input type="email" name="email" class="form-control" placeholder="{{ __('E-mail Address') }}" value="{{ $user->getEmail() }}" maxlength="255" required />
                        <div class="invalid-feedback"> Το Email είναι απαραίτητο. </div>
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="email" class="col-md-2 col-form-label">Τηλέφωνο</label>
                    <div class="col-md-10">
                        <input type="text" name="phone" class="form-control" placeholder="Τηλέφωνο" value="{{ $user->getUserDetails()->getPhone() }}" />
                    </div>
                </div>

                <div class="col-12 text-center mt-2 pt-50">
                    <button type="submit" class="btn btn-primary me-1">Αποθήκευση <i
                            class="ms-2 fa fa-save"></i></button>
                </div>
            </div>
        </div>
    </form>
    </div>


@endsection

@section('modals')

@endsection

@section('vendor-script')

@endsection

@section('page-script')
    <script type="module">
        $(".companies-select").select2({
            placeholder: 'Αναζήτηση...',
            allowClear: true,
            ajax: {
                type: 'POST',
                delay: 500,
                url: "{{ route('api.internal.companies.namesPaginated') }}",
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
                processResults: function (data, params) {
                    return {
                        results: $.map(data.results, function (obj) {
                            return {
                                id: obj.id,
                                text: obj.text + (obj.status ? ' (' + obj.status + ')' : '')
                            }; // Use id and name
                        }),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            }
        });
    </script>
@endsection
