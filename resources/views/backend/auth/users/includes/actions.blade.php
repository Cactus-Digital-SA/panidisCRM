<div class="btn-group" role="group">
    @if(auth()->user()->can('view users') || auth()->user()->can('admin.access.user.show'))
{{--        <a href="{{ route('admin.users.show', $user) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang('buttons.general.crud.view')" data-bs-original-title="@lang('buttons.general.crud.view')"--}}
{{--           class="btn-icon btn btn-round waves-effect waves-float waves-light">--}}
{{--            <i class="fas fa-eye text-primary"></i>--}}
{{--        </a>--}}
    @endif
    @if(auth()->user()->can('view users')  || auth()->user()->can('admin.access.user.edit'))
        <a href="{{route('admin.users.edit',$user->id)}}" data-id="{{$user->id}}" class="btn btn-outline-warning btn-icon waves-light waves-effect waves-float p-1 border-0" >
            <i class="fas fa-pencil-alt " ></i>
        </a>
    @endif
    @if (auth()->user()->can('delete users')  || auth()->user()->can('admin.access.user.deactivate')  || auth()->user()->can('admin.access.user.reactivate'))
        <div class="btn-group btn-group-sm dropdown" role="group">
            <button id="userActions" type="button" class="btn btn-secondary dropdown-toggle mr-1 waves-light waves-effect waves-float " data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Περισσότερα
            </button>
            <div class="dropdown-menu" aria-labelledby="userActions">
                @if ($user->id !== auth()->id())
                    @switch($user->active)
                        @case(0)
                            <form action="{{ route('admin.users.mark', [$user, 1,]) }}" method="POST">
                                @method('PATCH')
                                @csrf
                                <div class="btn-group">
                                    <button type="submit" class="dropdown-item">
                                        Ενεργοποίηση
                                    </button>
                                </div>
                            </form>
                            @break
                        @case(1)
                            <form action="{{ route('admin.users.mark', [$user, 0]) }}" method="POST">
                                @method('PATCH')
                                @csrf
                                <div class="btn-group">
                                    <button type="submit" class="dropdown-item">
                                        Απενεργοποίηση
                                    </button>
                                </div>
                            </form>
                            @break
                    @endswitch
                @endif
                @if(auth()->user()->can('delete users')  || auth()->user()->can('admin.access.user.delete'))
                    @if ($user->id !== auth()->id())
                    <form class="delete-form" method="POST" action="{{route('admin.users.delete',$user->id)}}">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="{{csrf_token()}}" />
                        <div class="btn-group w-100">
                            <button type="submit" class="delete dropdown-item w-100">
                                Διαγραφή
                            </button>
                        </div>
                    </form>
                    @endif
                @endif
            </div>

        </div>
    @endif
</div>

