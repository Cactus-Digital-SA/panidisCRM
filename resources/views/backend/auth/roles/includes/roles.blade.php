<div class="form-group row">
    <div class="col-md-2 mt-auto mb-auto">
        @lang('Roles')
    </div>

    <div class="col-md-10">
        <div class="table-responsive border rounded px-1 ">
            <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2"><i data-feather='lock'></i>
                @lang('Roles')
            </h6>
            <table class="table table-borderless">
                <tbody>
                @if(count($roles))
                    <?php $i=0;?>
                    @foreach($roles as $role)
                        <?php
                        if($i%2==0) echo '<tr>';
                        $i++;
                        ?>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    @if( isset($userRoles) && in_array( $role->id, $userRoles ) )
                                        <input type="checkbox" id="role_{{ $role->id }}" name="roles[]" class="custom-control-input" value="{{ $role->id }}" checked >
                                    @else
                                        <input type="checkbox" id="role_{{ $role->id }}" name="roles[]" class="custom-control-input" value="{{ $role->id }}"  >
                                    @endif
                                    <label class="custom-control-input font-weight-bold" for="role_{{ $role->id }}"> <strong>{{ucwords($role->name)}} </strong></label>
                                    @if ($role->isAdmin() && auth()->user()->isMasterAdmin())
                                        <blockquote class="ml-3">
                                            <i data-feather='check-circle'></i> @lang('All Permissions')
                                        </blockquote>
                                    @else
                                        @if ($role->permissions->count())
                                            <blockquote class="ml-3">
                                                @foreach ($role->permissions as $permission)
                                                    <i data-feather='check-circle'></i> {{ $permission->description }}<br/>
                                                @endforeach
                                            </blockquote>
                                        @else
                                            <blockquote class="ml-3">
                                                <i data-feather='minus-circle'></i> @lang('No Permissions')
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
