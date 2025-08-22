@php
    /**
    * @var App\Domains\Auth\Models\User $user
    * @var App\Domains\Auth\Models\User $authUser
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
    <li class="breadcrumb-item active">Ασφάλεια
    </li>
@endsection

@section('content')
    <?php
        $changePasswordUrl = route('profile.change-password.update', $user->getId());
        if(Auth::user()->hasRole('Administrator'))
            $changePasswordUrl = route('admin.users.change-password.update', $user->getId());
    ?>
    <div class="row">
        @include('backend.auth.users.includes.account-header')
        <div class="col-md-6">
            @include('backend.auth.users.includes.change-password', ['changePasswordUrl' => $changePasswordUrl])
        </div>
        <div class="col-md-6">
            @include('backend.auth.users.includes.2fa_settings')
        </div>
    </div>

@endsection
