@php
    /**
     * @var
    */
@endphp
@extends('backend.layouts.app')

@section('title', '')

@section('vendor-style')
    @include('includes.datatable_styles')
    @vite([])
@endsection

@section('page-style')
    @vite([])
@endsection

@section('content-header')
    <div class="content-header-right text-md-end col-md-5 d-md-block d-none mb-2 header-btn">
        <div class="mb-1 breadcrumb-right">
            <div class="col-12 d-flex ms-auto justify-content-end p-0">
{{--                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">--}}
{{--                    <i class="ti ti-square-plus me-1"></i> Δημιουργία--}}
{{--                </button>--}}
{{--                <button class="btn btn-info waves-effect waves-float waves-light ms-2" onclick="jQuery('#filters').toggle()">--}}
{{--                    <i class="ti ti-filter me-1"></i> Φίλτρα--}}
{{--                </button>--}}
            </div>
        </div>
    </div>
@endsection

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('home')}}">Αρχική</a>
    </li>
    <li class="breadcrumb-item active">
    </li>
@endsection

@section('content')





@endsection

@section('modals')

@endsection

@section('vendor-script')
    @include('includes.datatable_scripts')
@endsection

@section('page-script')
    <script type="module">

    </script>
@endsection
