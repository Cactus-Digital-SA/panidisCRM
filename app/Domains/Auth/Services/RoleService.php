<?php

namespace App\Domains\Auth\Services;

use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Repositories\RoleRepositoryInterface;
use App\Models\CactusEntity;
use Illuminate\Http\JsonResponse;

/**
 * Class RoleService.
 */
class RoleService
{
    private RoleRepositoryInterface $repository;

    /**
     * @param RoleRepositoryInterface $repository
     */
    public function __construct(RoleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getById(string $roleId): ?Role
    {
        return $this->repository->getById($roleId);
    }

    public function getByName(string $roleName): ?Role
    {
        return $this->repository->getByName($roleName);
    }

    /**
     * @return Role[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $userId
     * @return int[] Array contains role ids by userId.
     */
    public function getRolesIdByUserId(string $userId): array
    {
        return $this->repository->getRolesIdByUserId($userId);
    }

    public function store(CactusEntity $role): CactusEntity
    {
        return $this->repository->store($role);
    }

    public function update($role, string $id): CactusEntity
    {
        return $this->repository->update($role, $id);
    }

    public function deleteById($id): bool
    {
        return $this->repository->deleteById($id);
    }

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function rolesDatatable(array $filters = []): JsonResponse
    {
        return $this->repository->rolesDatatable($filters);
    }
}
