<?php

namespace App\Domains\Clients\Repositories;

use App\Domains\Clients\Models\ClientStatus;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;

interface ClientStatusRepositoryInterface extends RepositoryInterface
{
    /**
     * @return ClientStatus[]
     */
    public function get(): array;

    /**
     * @param string $id
     * @return ClientStatus|null
     */
    public function getById(string $id): ?ClientStatus;

    /**
     * @param CactusEntity|ClientStatus $entity
     * @return ClientStatus|null
     */
    public function store(CactusEntity|ClientStatus $entity): ?ClientStatus;

    /**
     * @param CactusEntity|ClientStatus $entity
     * @param string $id
     * @return ClientStatus|null
     */
    public function update(CactusEntity|ClientStatus $entity, string $id): ?ClientStatus;

    /**
     * @param string $id
     * @return boolean
     */
    public function deleteById(string $id): bool;
}
