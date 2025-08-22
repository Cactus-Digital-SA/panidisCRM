<div class="content-header row">
    <div class="content-header-left col-md-7 col-12 mb-3 mr-auto">
        <div class="row breadcrumbs-top">
            <div class="col-12">
{{--                @include('includes.partials.logged-in-as')--}}
                <h4 class="content-header-title float-start mb-0">@yield('title-icon') @yield('title')</h4>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        @yield('content-header-breadcrumbs')
                        @if(@isset($breadcrumbs))
                            {{-- this will load breadcrumbs dynamically from controller --}}
                            @foreach ($breadcrumbs as $breadcrumb)
                                <li class="breadcrumb-item @if(!isset($breadcrumb['link'])) active @endif" >
                                    @if(isset($breadcrumb['link']))
                                        <a href="{{ $breadcrumb['link'] == 'javascript:void(0)' ? $breadcrumb['link']:url($breadcrumb['link']) }}">
                                            @endif
                                            {{$breadcrumb['name']}}
                                            @if(isset($breadcrumb['link']))
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        @endisset
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @yield('content-header')
</div>
