<?php

namespace App\Domains\Auth\Repositories;

use App\Domains\Auth\Models\Permission;
use App\Repositories\RepositoryInterface;

interface PermissionRepositoryInterface extends RepositoryInterface
{
    /**
     * @param $userId
     * @return array Array contains permissions ids by UserId.
     */
    public function getUserPermissionId($userId): array;

    /**
     * @param $roleId
     * @return array Array contains permissions ids by RoleId.
     */
    public function getPermissionIdByRoleId($roleId): array;

    /**
     *@return array<Permission>
     */
    public function getCategorizedPermissions(): array;

    /**
     *@return array<Permission>
     */
    public function getUncategorizedPermissions(): array;

    /**
     *@return array<Permission>
     */
    public function getRolePermissions($roleId): array;
}
