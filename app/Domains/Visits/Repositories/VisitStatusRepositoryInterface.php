<?php

namespace App\Domains\Visits\Repositories;

use App\Domains\Visits\Models\VisitStatus;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;

interface VisitStatusRepositoryInterface extends RepositoryInterface
{
    /**
     * @return VisitStatus[]|null
     */
    public function get(): ?array;

    /**
     * @return VisitStatus[]|null
     */
    public function getVisible(): ?array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return VisitStatus|null
     */
    public function getById(string $id, bool $withRelations = true): ?VisitStatus;


    /**
     * @param CactusEntity|VisitStatus $entity
     * @return VisitStatus|null
     */
    public function store(CactusEntity|VisitStatus $entity): ?VisitStatus;

    /**
     * @param CactusEntity|VisitStatus $entity
     * @param string $id
     * @return VisitStatus|null
     */
    public function update(CactusEntity|VisitStatus $entity, string $id): ?VisitStatus;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;
}
