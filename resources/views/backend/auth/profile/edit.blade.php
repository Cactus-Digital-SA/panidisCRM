@php
    /**
    * @var App\Domains\Auth\Models\User $user
    * */
@endphp
@extends('backend.layouts.app')

@section('title', 'Profile')

@section('vendor-style')
    @vite([])
@endsection

@section('page-style')
    @vite([])
@endsection
@section('content-header')

@endsection

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Αρχική</a>
    </li>
    <li class="breadcrumb-item active">Profile
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('backend.auth.users.includes.account-header')
            <form id="profile_edit" method="POST" action="{{ route('profile.update',$user->getId()) }}" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row mb-1 mt-1">
                            <label for="name" class="col-md-2 col-form-label">@lang('Name')</label>
                            <div class="col-md-10">
                                <input type="text" name="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') ?? $user->getName() }}" maxlength="100" required />
                            </div>
                        </div>
                        <div class="form-group row mb-1 mt-1">
                            <label for="email" class="col-md-2 col-form-label">@lang('E-mail Address')</label>
                            <div class="col-md-10">
                                <input type="email" name="email" class="form-control" placeholder="{{ __('E-mail Address') }}" value="{{ old('email') ?? $user->getEmail() }}" maxlength="255" required />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col text-end">
                                <button class="btn btn-primary float-right" type="submit">Αποθήκευση</button>
                            </div><!--row-->
                        </div><!--row-->
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('after-scripts')
    <script type="module">

    </script>
@endpush
