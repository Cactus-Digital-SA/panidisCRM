<div class="form-group row mb-1 mt-1 permissions-container">
    <div class="col-md-2 mt-auto mb-auto">
        @lang('Additional Permissions')
    </div>

    <div class="col-md-10">
        <div class="table-responsive border rounded px-1 ">
{{--            @include('backend.auth.role.includes.no-permissions-message')--}}

            @include('backend.auth.includes.permission-type')
        </div>
    </div>
</div>
