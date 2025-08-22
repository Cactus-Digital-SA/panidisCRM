@php
    /**
    * @var App\Domains\Auth\Models\User $user
    * @var App\Domains\Auth\Models\User $authUser
    * @var App\Domains\Auth\Models\Token $token;
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
    <div class="row">
        @include('backend.auth.users.includes.account-header')
        <div class="col-12">
            <!-- security -->
            <div class="card">
                <div class="card-header border-bottom pb-0">
                    <h4 class="card-title">Διαχείριση Κωδικών API για τον χρήστη : {{$user->getName()}}</h4>
                </div>
                <div class="card-body pt-3">
                    @foreach($user->getTokens() as $token)
                        <div class="col-6">
                            <div class="bg-light-secondary position-relative rounded p-2">
                                <div class="d-flex align-items-center flex-wrap ">
                                    <h4 class="mb-1 font-small-4"> Recovery Key {{$token->getName()}}</h4>
                                </div>
                                <h6 class="d-flex align-items-center fw-bolder">
                                    <span id="key_{{$token->getId()}}" class="me-50">{{ $token->getToken() }}</span>
                                </h6>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
