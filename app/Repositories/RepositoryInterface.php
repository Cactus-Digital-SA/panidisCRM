<?php

namespace App\Repositories;

use App\Models\CactusEntity;

/**
 * @template T of CactusEntity
 */
interface RepositoryInterface
{
    public function getById(string $id): ?CactusEntity;
    public function store(CactusEntity $entity): ?CactusEntity;
    public function update(CactusEntity $entity, string $id): ?CactusEntity;
    public function deleteById(string $id): bool;

}
