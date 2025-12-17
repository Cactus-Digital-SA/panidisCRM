<?php

namespace App\Domains\Sectors\Repositories;

use App\Domains\Sectors\Models\Sector;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;

interface SectorRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Sector[]
     */
    public function get(): array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return Sector|null
     */
    public function getById(string $id, bool $withRelations = true): ?Sector;

    /**
     * @param CactusEntity|Sector $entity
     * @return Sector|null
     */
    public function store(CactusEntity|Sector $entity): ?Sector;

    /**
     * @param CactusEntity|Sector $entity
     * @param string $id
     * @return Sector|null
     */
    public function update(CactusEntity|Sector $entity, string $id): ?Sector;

    /**
     * @param Sector $entity
     * @param string $erpId
     * @return Sector|null
     */
    public function createOrUpdateByErpId(Sector $entity, string $erpId): ?Sector;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;
}
