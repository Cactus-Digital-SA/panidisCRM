@php
    /**
    * @var App\Domains\Auth\Models\Role $role
    * @var array<\App\Domains\Auth\Models\Role> $roles
    * */
@endphp
<div class="form-group row">
    <div class="col-md-2 mt-auto mb-auto">
        @lang('Roles')
    </div>

    <div class="col-md-10">
        <div class="table-responsive border rounded px-1 ">
            <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2"><i data-feather='lock'></i>
                @lang('Roles')
            </h6>
            <table class="table table-borderless" style="vertical-align: top;">
                <tbody>
                @if(count($roles))
                    <?php $i=0;?>
                    @foreach($roles as $role)
                        <?php
                        if($i%2==0) echo '<tr>';
                        $i++;
                        ?>
                            <td class="w-50">
                                <div class="custom-control custom-checkbox">
                                    @if( isset($userRoles) && in_array( $role->getId(), $userRoles ) )
                                        <input type="checkbox" id="role_{{ $role->getId() }}" name="roles[]" class="custom-control-input" value="{{ $role->getId() }}" checked >
                                    @else
                                        <input type="checkbox" id="role_{{ $role->getId() }}" name="roles[]" class="custom-control-input" value="{{ $role->getId() }}"  >
                                    @endif
                                    <label class="custom-control-input font-weight-bold" for="role_{{ $role->getId() }}"> <strong>{{ucwords($role->getName())}} </strong></label>
                                    @if ($role->getName() === 'super-admin' && auth()->user()->isMasterAdmin())
                                        <blockquote class="ms-3">
                                            <i data-feather='check-circle'></i> @lang('All Permissions')
                                        </blockquote>
                                    @else
                                        @if (sizeof($role->getPermissions()))
                                            <blockquote class="ms-3 font-small-2">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <?php $count = 0 ?>
                                                        @foreach ($role->getPermissions() as $permission)
                                                            @if($count == ceil(sizeof($role->getPermissions()) / 2))
                                                                </div>
                                                                <div class="col-md-6">
                                                            @endif
                                                            <div>
                                                                @if($permission->getParentId() == null)
                                                                    <div>
                                                                        <i class="ti ti-circle-check"></i> <span class="fw-bolder"> {{ $permission->getDescription() }} </span>
                                                                    </div>
                                                                @else
                                                                    <div class="ms-4 font-small-2">
                                                                        <i class="fa-regular fa-circle-check"></i> {{ $permission->getDescription() }}<br/>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                             <?php $count++ ?>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </blockquote>
                                        @else
                                            <blockquote class="ml-3">
                                                <i class="fa-solid fa-circle-minus"></i> @lang('No Permissions')
                                            </blockquote>
                                        @endif
                                    @endif
                                </div>
                            </td>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
