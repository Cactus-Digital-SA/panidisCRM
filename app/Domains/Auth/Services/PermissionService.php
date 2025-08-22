<?php

namespace App\Domains\Auth\Services;

use App\Domains\Auth\Models\Permission;
use App\Domains\Auth\Repositories\PermissionRepositoryInterface;

/**
 * Class PermissionService.
 */
class PermissionService
{
    private PermissionRepositoryInterface $repository;

    public function __construct(PermissionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     *@return Permission[]
     */
    public function getCategorizedPermissions(): array
    {
        return $this->repository->getCategorizedPermissions();
    }

    /**
     *@return Permission[]
     */
    public function getUncategorizedPermissions(): array
    {
        return $this->repository->getUncategorizedPermissions();
    }

    /**
     * @param $userId
     * @return array Array contains permissions ids by UserId.
     */
    function getUserPermissionId($userId): array
    {
        return $this->repository->getUserPermissionId($userId);
    }

    /**
     * @param $roleId
     * @return array Array contains permissions ids by RoleId.
     */
    function getPermissionIdByRoleId($roleId): array
    {
        return $this->repository->getPermissionIdByRoleId($roleId);
    }

    /**
     *@return Permission[]
     */
    public function getRolePermissions($roleId): array
    {
        return $this->repository->getRolePermissions($roleId);
    }
}
