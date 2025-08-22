@extends('backend.layouts.app')

@section('title', 'AppSettings')

@section('vendor-style')
    @include('includes.datatable_styles')
    @vite([])
@endsection

@section('page-style')
    @vite([])
@endsection
@section('content-header')
@endsection

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.home')}}" >Dashboard</a>
    </li>
    <li class="breadcrumb-item active">AppSettings
    </li>
@endsection
@section('content')
    <div class="row">
        @foreach($appSettings as $key=>$group)
            <?php
                $collapseStr=str_replace(' ', '', $key);
            ?>
            <div class="col-md-6">
                <div class="card card-dark">
                    <div class="card-header">
                        <h4 class="card-title text-white">{{$key}}</h4>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li>
                                    <a data-bs-toggle="collapse" class="" aria-controls="collapse{{$collapseStr}}" href="#collapse{{$collapseStr}}">
                                        <i class="fa-solid fa-align-right text-white font-medium-2"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show" id="collapse{{$collapseStr}}">
                        <div class="card-body">
                            @foreach($group as $item)
                                @if($item->name!='-')
                                    <div class="row p-1">
                                        <div class="col-md-1 pr-0">
                                            <div class="position-relative d-inline-block">
                                                <span class="badge badge-center rounded-pill
                                                    @if($item->available)
                                                    bg-gradient-success
                                                    @else
                                                    bg-gradient-danger
                                                    @endif
                                                    badge-up" style="width: 1.3rem; height: 1.3rem;">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-5 pr-0 text-center">
                                            <h6 class="text-white">{{$item->name}}</h6>
                                        </div>
                                        @if(isset($item->url))
                                        <div class="col-md-3 pr-0 text-center">
                                            <a class="btn btn-small rounded-pill bg-white waves-effect waves-light text-black" href="{{$item->url}}">
                                                Refresh Cache
                                            </a>
                                        </div>
                                        @endif
                                        @if(isset($item->clear_url))
                                            <div class="col-md-3 pr-0 text-center">
                                                <a class="btn btn-small rounded-pill bg-white waves-effect waves-light text-black" href="{{$item->clear_url}}">
                                                    Clear Cache
                                                </a>
                                            </div>
                                        @endif
                                        @if(isset($item->cache_url) && $item->cache_url)
                                            <div class="col-md-3 pr-0 text-center">
                                                <a class="btn btn-small rounded-pill bg-white waves-effect waves-light text-black" href="{{$item->cache_url}}">
                                                    Enable Cache
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="row p-1">
                                        <div class="col-md-12 pr-0 text-center">
                                            <a class="btn btn-small rounded-pill bg-white waves-effect waves-light text-black" href="{{$item->url}}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Δημιουργία">
                                                Refresh Cache
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

