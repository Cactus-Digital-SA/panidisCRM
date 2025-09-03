@php
    $configData = Helper::appClasses();

    $logoPath = '';
    try {
        $logoPath = Vite::asset('resources/images/logo/PANIDIS-LOGO-BLACK-RED.png');
    } catch (\Exception $e) {

    }
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    @if(!isset($navbarFull))
        <div class="app-brand demo">
            <a href="{{url('/')}}" class="app-brand-link">
              <span class="app-brand-logo mt-3" style="width: 90%; height:100%">
                  <img src="{{ $logoPath }}" style="width: 100%;" alt class="h-auto">
              </span>
                {{--      <span class="app-brand-text demo menu-text fw-bold">{{config('appVariables.templateName')}}</span>--}}
            </a>
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="icon-base ti ti-circle-dot d-none d-xl-block"></i>
                <i class="icon-base ti tabler-x d-block d-xl-none"></i>
            </a>
        </div>
    @endif



    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text"> Διαχείριση </span>
        </li>

        <li class="menu-item {{ Route::currentRouteName() === 'admin.home' ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.home') }}">
                <i class="menu-icon tf-icons ti ti-home"></i>
                <div>
                    <span class="menu-title" >Dashboard</span>
                </div>
            </a>
        </li>
        <li class="menu-item {{ Route::currentRouteName() === 'admin.leads.index' ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.leads.index') }}">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div>
                    <span class="menu-title" >Leads</span>
                </div>
            </a>
        </li>
        <li class="menu-item {{ Route::currentRouteName() === 'admin.clients.index' ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.clients.index') }}">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div>
                    <span class="menu-title" >Clients</span>
                </div>
            </a>
        </li>
        <li class="menu-item {{ Route::currentRouteName() === 'admin.visits.index' ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.visits.index') }}">
                <i class="menu-icon tf-icons ti ti-users-group"></i>
                <div>
                    <span class="menu-title" >Visits</span>
                </div>
            </a>
        </li>
        <li class="menu-item {{ Route::currentRouteName() === 'home' ? 'active' : '' }}">
            <a class="menu-link" href="#">
                <i class="menu-icon tf-icons ti ti-align-box-bottom-center"></i>
                <div>
                    <span class="menu-title" >Quotes</span>
                </div>
            </a>
        </li>
        <li class="menu-item {{ Route::currentRouteName() === 'home' ? 'active' : '' }}">
            <a class="menu-link" href="#">
                <i class="menu-icon tf-icons ti ti-flask"></i>
                <div>
                    <span class="menu-title" >R&D</span>
                </div>
            </a>
        </li>

        <li class="menu-item {{ Route::currentRouteName() === 'home' ? 'active' : '' }}">
            <a class="menu-link" href="#">
                <i class="menu-icon tf-icons ti ti-checklist"></i>
                <div>
                    <span class="menu-title" >Tasks</span>
                </div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text"> {{__('Settings')}} </span>
        </li>


        @if(Auth::user()->can('settings.view'))
        <li class="menu-item {{ activeClass(request()->is('admin/extraData*'),'open active') }}">
            <a class="menu-link menu-toggle" href="#">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div>
                    <span class="menu-title"> {{'Extra Data'}}</span>
                </div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ activeClass(request()->is('admin/extraData'),'active') }}">
                    <a class="menu-link" href="{{ route('admin.extraData.index') }}">
                        <span class="menu-title" >{{'Extra Data'}}</span>
                    </a>
                </li>
                <li class="menu-item {{ activeClass(request()->is('admin/extraData/assign'),'active') }}">
                    <a class="menu-link" href="{{ route('admin.extraData.assign') }}">
                        <span class="menu-title" >{{'Assign Extra Data'}}</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        @if(Auth::user()->can('admin.access.user'))
            <li class="menu-item {{ activeClass(request()->is('admin/users*'),'open active') }}">
                <a class="menu-link menu-toggle" href="#">
                    <i class="menu-icon tf-icons ti ti-user"></i>
                    <div>
                        <span class="menu-title">Διαχείριση Χρηστών</span>
                    </div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ activeClass(request()->is('admin/users'),'active') }}">
                        <a class="menu-link" href="{{ route('admin.users.index') }}">
                            <span class="menu-title" >Ενεργοί Χρήστες</span>
                        </a>
                    </li>
                    <li class="menu-item {{ activeClass(request()->is('admin/users/deactivated'),'active') }}">
                        <a class="menu-link" href="{{ route('admin.users.deactivated') }}">
                            <span class="menu-title" >Απενεργοποιημένοι Χρήστες</span>
                        </a>
                    </li>
                    <li class="menu-item {{ activeClass(request()->is('admin/users/deleted'),'active') }}">
                        <a class="menu-link" href="{{ route('admin.users.deleted') }}">
                            <span class="menu-title" >Διαγραμμένοι Χρήστες</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif
        @if(Auth::user()->can('crud roles'))
            <li class="menu-item {{ activeClass(request()->is('admin/roles*'),'active') }}">
                <a class="menu-link" href="{{ route('admin.roles.index') }}">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div>
                    <span class="menu-title">Ρόλοι Χρηστών</span>
                    </div>
                </a>
            </li>
        @endif

        @if(Auth::user()->can('settings.view'))
            <li class="menu-item has-sub {{ activeClass(request()->is('admin/app-settings*'),'open') }}">
                <a class="menu-link menu-toggle" href="#">
                    <i class="menu-icon tf-icons ti ti-settings"></i>
                    <div>
                    <span class="menu-title">Ρυθμίσεις Διαχειριστή</span>
                    </div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ activeClass(request()->is('admin/app-settings'),'active') }}">
                        <a class="menu-link" href="{{ route('admin.setting.index') }}">
                            <span class="menu-title">Cache & Optimizations</span>
                        </a>
                    </li>
                </ul>
                @if(Auth::user()->hasRole(\App\Domains\Auth\Models\RolesEnum::Administrator->value) || Auth::user()->hasRole(\App\Domains\Auth\Models\RolesEnum::SuperAdmin->value))
                    <ul class="menu-sub">
                        <li class="menu-item {{ activeClass(request()->is('log-viewer*'),'active') }}">
                            <a class="menu-link" href="{{ route('log-viewer.index') }}">
                                <span class="menu-title" >Admin Logs</span>
                            </a>
                        </li>
                    </ul>
                @endif
            </li>
        @endif
    </ul>

</aside>
