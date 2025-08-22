<ul class="list-unstyled ml-4 ms-1">
    @foreach($children as $permission)
        <li>
            <input class="child-permission" type="checkbox" name="permissions[]" {{ in_array($permission->id, $usedPermissions ?? [], true) ? 'checked' : '' }} value="{{ $permission->id }}" id="{{ $permission->id }}" />
            <label @if(isset($add_class)) class="{{$add_class}}" @endif for="{{ $permission->id }}">{{ $permission->description ?? $permission->name }}</label>

            @if($permission->children->count())
                @include('backend.auth.roles.includes.children', ['children' => $permission->children,'add_class'=>'fst-italic'])
            @endif
        </li>
    @endforeach
</ul>
