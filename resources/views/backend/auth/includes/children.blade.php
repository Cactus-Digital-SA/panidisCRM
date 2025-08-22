<ul class="list-unstyled ml-4 ms-1">
    @foreach($children as $permission)
        <li>
            <input class="child-permission" type="checkbox" name="permissions[]" {{ in_array($permission->getId(), $usedPermissions ?? [], true) ? 'checked' : '' }} value="{{ $permission->getId() }}" id="{{ $permission->getId() }}" />
            <label @if(isset($add_class)) class="{{$add_class}}" @endif for="{{ $permission->getId() }}">{{ $permission->getDescription() ?? $permission->getName() }}</label>

            @if(sizeof($permission->getChildren()) > 0)
                @include('backend.auth.includes.children', ['children' => $permission->getChildren(),'add_class'=>'fst-italic'])
            @endif
        </li>
    @endforeach
</ul>
