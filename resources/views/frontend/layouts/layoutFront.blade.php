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

{{--@include('frontend/layouts/sections/footer/footer-front')--}}
@endsection
