<?php

namespace App\Domains\Auth\Http\Controllers\Role;

use App\Domains\Auth\Http\Requests\Role\CreateRoleRequest;
use App\Domains\Auth\Http\Requests\Role\DeleteRoleRequest;
use App\Domains\Auth\Http\Requests\Role\EditRoleRequest;
use App\Domains\Auth\Http\Requests\Role\ManageRoleRequest;
use App\Domains\Auth\Http\Requests\Role\StoreRoleRequest;
use App\Domains\Auth\Http\Requests\Role\UpdateRoleRequest;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Services\PermissionService;
use App\Domains\Auth\Services\RoleService;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    protected RoleService $roleService;
    protected PermissionService $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    public function index(ManageRoleRequest $request){
        $breadcrumbs = [
            ['link' => "home", 'name' => __('locale.Home')], ['name' => __('Roles')]
        ];
        return view('backend.auth.roles.index',compact('breadcrumbs'));
    }

    public function show(ManageRoleRequest $request, string $roleId){
        return response()->json();
    }

    public function create(CreateRoleRequest $request)
    {
        return view('backend.auth.roles.create')
            ->withCategories($this->permissionService->getCategorizedPermissions())
            ->withGeneral($this->permissionService->getUncategorizedPermissions());
    }

    public function store(StoreRoleRequest $request)
    {
        $role_DTO = new Role();
        $role_DTO->setGuardName('web');
        $role_DTO->setName($request['name']);
        $role_DTO->setPermissions( $request['permissions'] ?? []);

        $this->roleService->store($role_DTO);

        return redirect()->route('admin.roles.index')->with('success','Ο ρόλος δημιουργήθηκε με επιτυχία.');
    }

    public function edit(EditRoleRequest $request, string $roleId)
    {
        $cactusRole = $this->roleService->getById($roleId);

        return view('backend.auth.roles.edit')
            ->withCategories($this->permissionService->getCategorizedPermissions())
            ->withGeneral($this->permissionService->getUncategorizedPermissions())
            ->withRole($cactusRole)
            ->withUsedPermissions($this->permissionService->getPermissionIdByRoleId($roleId));
    }

    public function update(UpdateRoleRequest $request, string $roleId){
        $role_DTO = new Role();
        $role_DTO->setName($request['name']);
        $role_DTO->setPermissions($request['permissions']);

        $this->roleService->update($role_DTO, $roleId);

        return redirect()->route('admin.roles.index')->with('success','Ο ρόλος ενημερώθηκε με επιτυχία.');
    }

    public function delete(DeleteRoleRequest $request, string $roleId){
        $response = $this->roleService->deleteById($roleId);

        if($response){
            return redirect()->route('admin.roles.index')->with('success','Ο ρόλος διαγράφτηκε με επιτυχία.');
        }

        return redirect()->route('admin.roles.index')->with('error','Ο ρόλος δεν μπόρεσε να διαγραφεί.');
    }


}
