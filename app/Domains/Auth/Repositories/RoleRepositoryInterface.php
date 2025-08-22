<?php

namespace App\Domains\Auth\Repositories;

use App\Domains\Auth\Models\Role;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;
use Illuminate\Http\JsonResponse;

interface RoleRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Role[]
     */
    public function get(): array;

    /**
     * @param string $userId
     * @return int[] Array contains role ids by userId.
     */
    public function getRolesIdByUserId(string $userId): array;

    public function getById(string $id): ?Role;
    public function getByName(string $roleName): ?Role;

    public function store(Role|CactusEntity $entity): ?Role;

    public function update(Role|CactusEntity $entity, string $id): ?CactusEntity;

    public function deleteById(string $id): bool;

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function rolesDatatable(array $filters = []): JsonResponse;

}
