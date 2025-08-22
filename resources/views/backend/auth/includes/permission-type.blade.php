@php
    /**
    * @var App\Domains\Auth\Models\Permission $permission
    * @var array<\App\Domains\Auth\Models\Permission> $categories
    * */
@endphp
@if (sizeof($general) > 0)
    <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2"><i data-feather='lock'></i>
        Γενικά δικαιώματα
    </h6>
    <table class="table table-borderless">
        <tbody>
            <td>
                <div class="col">
                    @foreach($general as $permission)
                        <span class="d-block">
                            <input type="checkbox" name="permissions[]" {{ in_array($permission->getId(), $usedPermissions ?? [], true) ? 'checked' : '' }} value="{{ $permission->getId() }}" id="{{ $permission->getId() }}" />
                            <label for="{{ $permission->getId() }}">{{ $permission->getDescription() ?? $permission->getName() }}</label>
                        </span>
                    @endforeach
                </div><!--col-->
            </td>
        </tbody>
    </table>

@endif

@if (sizeof($general) > 0 && sizeof($categories) > 0)
    <hr/>
@endif

@if (sizeof($categories) > 0)
    <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2"><i data-feather='lock'></i>
        Κατηγορίες αδειών
    </h6>
    <table class="table table-borderless"  style="vertical-align:top; ">
        <tbody>
            <?php $counter=0;?>
            @foreach($categories as $permission)
                @if ( $counter % 3 == 0 )
                    <tr>
                @endif
                <td>

                    <input class="parent-permission" type="checkbox" name="permissions[]" {{ in_array($permission->getId(), $usedPermissions ?? [], true) ? 'checked' : '' }} value="{{ $permission->getId() }}" id="{{ $permission->getId() }}" />
                    <label class="fw-bolder" for="{{ $permission->getId() }}">{{ $permission->getDescription() ?? $permission->getName() }}</label>

                    @if(sizeof($permission->getChildren()) > 0)
                        @include('backend.auth.includes.children', ['children' => $permission->getChildren()])
                    @endif

                </td>
{{--                @if ( $counter%3 == 0 )--}}
{{--                    </tr>--}}
{{--                @endif--}}
                <?php $counter++;?>
            @endforeach
        </tbody>
    </table>
@endif

@if (sizeof($general) == 0 && sizeof($categories) == 0)
    <p class="mb-0"><em>@lang('There are no additional permissions to choose from for this type.')</em></p>
@endif

@push('after-scripts')
<script type="module">
    $('.parent-permission').change(function() {
        if ($(this).is(':checked')) {
            $(this).parent().find('.child-permission').prop( "checked", true );
        } else {
            $(this).parent().find('.child-permission').prop( "checked", false );
        }
    });
</script>
@endpush
