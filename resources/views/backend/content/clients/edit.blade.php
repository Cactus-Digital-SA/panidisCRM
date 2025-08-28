@extends('backend.layouts.app')

@section('title', 'Επεξεργασία Client')

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
    <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">Clients</a>
    </li>
    <li class="breadcrumb-item active">Επεξεργασία
    </li>
@endsection

@section('content')


    <form id="form" method="POST" action="{{ route('admin.clients.update', $client->getId()) }}" class="form-horizontal needs-validation"
          enctype="multipart/form-data" novalidate>
        @method('PATCH')
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="form-group row mb-1 mt-1">
                    <label for="clientCompanyId" class="col-md-2 col-form-label">Εταιρεία</label>
                    <div class="col-md-10">
                        <select name="clientCompanyId" id="clientCompanyId" class="form-control companies-select select2" data-placeholder="{{ 'Εταιρεία' }}" data-allow-clear="true" required>
                            @if($client->getCompanyId())
                                <option value="{{$client->getCompanyId()}}" selected> {{$client?->getCompany()?->getName()}}  </option>
                            @endif
                        </select>
                        <div class="invalid-feedback">Η εταιρεία είναι απαραίτητη. </div>
                    </div>
                </div>
                <div class="form-group row mb-1 mt-1">
                    <label for="doyId" class="col-md-2 col-form-label">Κατάσταση</label>
                    <div class="col-md-10">
                        <select name="statusId" id="statusId" class="form-control doy-select select2" data-placeholder="{{ 'Κατάσταση' }}" data-allow-clear="true">
                            @if(isset($statuses))
                                @foreach($statuses as $status)
                                    <option value="{{$status->getId()}}" {{ $client->getStatusId() == $status->getId() ? 'selected' : '' }}>{{$status->getName()}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-12 text-center mt-2 pt-50">
                    <button type="submit" class="btn btn-primary me-1">Αποθήκευση <i
                            class="ms-2 fa fa-save"></i></button>
                </div>
            </div>
        </div>

    </form>


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
