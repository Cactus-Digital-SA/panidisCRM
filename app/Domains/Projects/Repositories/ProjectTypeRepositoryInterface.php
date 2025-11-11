<?php

namespace App\Domains\Projects\Repositories;

use App\Domains\Projects\Models\ProjectType;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;

interface ProjectTypeRepositoryInterface extends RepositoryInterface
{
    /**
     * @return ProjectType[]
     */
    public function get(): array;

    /**
     * @return ProjectType[]
     */
    public function getVisible(): array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return ProjectType|null
     */
    public function getById(string $id, bool $withRelations = true): ?ProjectType;

    /**
     * @param string $slug
     * @param bool $withRelations
     * @return ProjectType|null
     */
    public function getBySlug(string $slug, bool $withRelations = true): ?ProjectType;


    /**
     * @param CactusEntity|ProjectType $entity
     * @return ProjectType|null
     */
    public function store(CactusEntity|ProjectType $entity): ?ProjectType;

    /**
     * @param CactusEntity|ProjectType $entityx
     * @param string $id
     * @return ProjectType|null
     */
    public function update(CactusEntity|ProjectType $entity, string $id): ?ProjectType;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;
}
