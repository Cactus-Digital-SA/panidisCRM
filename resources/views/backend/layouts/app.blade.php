@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
$configData = Helper::appClasses();
@endphp

@isset($configData["layout"])
    @if(( $configData["layout"] === 'horizontal'))
        @include('backend.layouts.horizontalLayout')
    @elseif($configData["layout"] === 'blank'))
        @include('backend.layouts.blankLayout')
    @else
        @include('backend.layouts.contentNavbarLayout')
    @endif
@endisset
