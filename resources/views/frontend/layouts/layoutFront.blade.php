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

<div class="small text-muted">S/N: {{ config('app.serial_number') }}</div>
@endsection
