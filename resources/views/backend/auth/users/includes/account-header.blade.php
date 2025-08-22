<ul class="nav nav-pills flex-column flex-md-row mb-4">
    @if(Auth::user()->hasRole('Administrator'))
        <li class="nav-item"><a class="nav-link {{Route::is('admin.users.edit', $user->getId()) ? 'active' : ''}}" href="{{route('admin.users.edit', $user->getId())}}"><i class="ti-xs ti ti-users me-1"></i> Account</a></li>
        <li class="nav-item"><a class="nav-link {{Route::is('admin.users.change-password', $user->getId()) ? 'active' : ''}}" href="{{route('admin.users.change-password',$user->getId())}}"><i class="ti-xs ti ti-lock me-1"></i> Security</a></li>
    @elseif(Auth::check())
        <li class="nav-item"><a class="nav-link {{Route::is('profile.edit', $user->getId()) ? 'active' : ''}}" href="{{route('profile.edit', $user->getId())}}"><i class="ti-xs ti ti-users me-1"></i> Account</a></li>
        <li class="nav-item"><a class="nav-link {{Route::is('profile.change-password', $user->getId()) ? 'active' : ''}}" href="{{route('profile.change-password',$user->getId())}}"><i class="ti-xs ti ti-lock me-1"></i> Security</a></li>
    @endif
</ul>
