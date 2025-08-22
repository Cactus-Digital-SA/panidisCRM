@extends('backend.layouts.app')

@section('title', 'Δημιουργία Ρόλου')
@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"> <a href="{{route('admin.home')}}" class="">Dashboard</a> </li>
    <li class="breadcrumb-item active">Δημιουργία Ρόλου </li>
@endsection
@section('content-header')

@endsection

@section('content')
    <form id="form" method="POST" action="{{ route('admin.roles.store') }}" class="form-horizontal mt-4">
        @csrf
        <div class="card">
            <div class="card-header">
                <div></div>
                <div class="card-header-actions">
                    <a class="card-header-action btn btn-warning"
                       href="{{route('admin.roles.index')}}">
                        <i data-feather='arrow-left'></i> {{__('Cancel')}}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row mb-4 mt-1">
                    <label for="name" class="col-md-2 col-form-label">@lang('Name')</label>
                    <div class="col-md-10">
                        <input type="text"  name="name" id="name" class="form-control" placeholder="{{ __('Name') }}" value="" maxlength="100" required />
                    </div>
                </div>

                @include('backend.auth.includes.permissions')
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col text-end">
                        <button class="btn btn-primary float-right" type="submit">Αποθήκευση Ρόλου</button>
                    </div><!--row-->
                </div><!--row-->
            </div>
        </div>
    </form>

@endsection

@push('after-scripts')
    <script>

    </script>
@endpush
