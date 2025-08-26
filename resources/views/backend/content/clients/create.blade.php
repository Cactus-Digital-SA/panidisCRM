@extends('backend.layouts.app')

@section('title', 'Δημιουργία Client')

<!-- Vendor Style -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/tagify/tagify.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss'])
@endsection

@section('page-style')
    @vite(['resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss'])
@endsection

@push('after-styles')
    <style>
    </style>
@endpush

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"> <a href="{{ route('admin.home') }}" class="">Αρχική</a> </li>
    <li class="breadcrumb-item"> <a href="{{ route('admin.clients.index') }}" class="">Πελάτες</a> </li>
    <li class="breadcrumb-item active">Δημιουργία</li>
@endsection

@section('content')

@endsection

<!-- Vendor Script -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/tagify/tagify.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js'])
@endsection

<!-- Page Script -->
@section('page-script')
    @vite(['resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js'])
@endsection


@push('after-scripts')
    <script type="module">

    </script>
@endpush
