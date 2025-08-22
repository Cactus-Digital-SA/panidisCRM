<?php

namespace App\Domains\Auth\Repositories\Eloquent;

use App\Domains\Auth\Models\Permission;
use App\Domains\Auth\Repositories\Eloquent\Models\Permission as EloquentPermission;
use App\Domains\Auth\Repositories\Eloquent\Models\Role as EloquentRole;
use App\Domains\Auth\Repositories\Eloquent\Models\User as EloquentUser;
use App\Domains\Auth\Repositories\PermissionRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;

class EloqPermissionRepository implements PermissionRepositoryInterface
{
    private EloquentPermission $model;

    public function __construct(EloquentPermission $permission)
    {
        $this->model = $permission;
    }

    /**
     * @param $userId
     * @return array Array contains permissions ids by UserId.
     */
    public function getUserPermissionId($userId): array
    {
        $user = EloquentUser::find($userId);

        return $user->permissions->modelKeys();
    }

    /**
     * @param $roleId
     * @return array Array contains permissions ids by RoleId.
     */
    public function getPermissionIdByRoleId($roleId): array
    {
        $role = EloquentRole::find($roleId);

        return $role->permissions->modelKeys();
    }

    /**
     *@return Permission[]
     */
    public function getCategorizedPermissions(): array
    {
        $permissions = $this->model::isMaster()
            ->with('children')
            ->get();

        return ObjectSerializer::deserialize($permissions->toJson() ?? "{}",'array<' . Permission::class . '>', 'json');
    }

    /**
     *@return Permission[]
     */
    public function getUncategorizedPermissions(): array
    {
        $permissions = $this->model::singular()
            ->orderBy('sort', 'asc')
            ->get();

        return ObjectSerializer::deserialize($permissions->toJson() ?? "{}",'array<' . Permission::class . '>', 'json');
    }

    /**
     *@return Permission[]
     */
    public function getRolePermissions($roleId): array
    {
        $role = EloquentRole::find($roleId);

        return ObjectSerializer::deserialize($role->permissions->toJson() ?? "{}",'array<' . Permission::class . '>', 'json');
    }

    public function getById(string $id): ?CactusEntity
    {
        // TODO: Implement getById() method.
    }

    public function store(CactusEntity $entity): ?CactusEntity
    {
        // TODO: Implement store() method.
    }

    public function update($entity, string $id): ?CactusEntity
    {
        // TODO: Implement update() method.
    }

    public function deleteById(string $id): bool
    {
        // TODO: Implement deleteById() method.
    }
}
