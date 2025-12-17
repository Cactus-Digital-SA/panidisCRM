@php
$configData = Helper::appClasses();
$isFront = true;
@endphp

@section('layoutContent')

@extends('frontend/layouts/commonMaster' )

{{--@include('frontend/layouts/sections/navbar/navbar-front')--}}

<!-- Sections:Start -->
@yield('content')
<!-- / Sections:End -->

<div class="container d-flex flex-wrap flex-center flex-md-column flex-column text-center text-md-start">
    <div class="mb-2 mb-md-0">
        <span class="footer-text">S/N: {{ config('app.serial_number') }} </span>
    </div>
</div>

@endsection
