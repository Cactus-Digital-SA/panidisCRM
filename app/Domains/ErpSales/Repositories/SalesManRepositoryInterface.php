<?php

namespace App\Domains\ErpSales\Repositories;

use App\Domains\ErpSales\Models\Salesman;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;

interface SalesManRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Salesman[]
     */
    public function get(): array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return Salesman|null
     */
    public function getById(string $id, bool $withRelations = true): ?Salesman;

    /**
     * @param CactusEntity|Salesman $entity
     * @return Salesman|null
     */
    public function store(CactusEntity|Salesman $entity): ?Salesman;

    /**
     * @param CactusEntity|Salesman $entity
     * @param string $id
     * @return Salesman|null
     */
    public function update(CactusEntity|Salesman $entity, string $id): ?Salesman;

    /**
     * @param CactusEntity|Salesman $entity
     * @param string $erpId
     * @return Salesman|null
     */
    public function createOrUpdateByErpId(CactusEntity|Salesman $entity, string $erpId): ?Salesman;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;


}
