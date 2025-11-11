<?php

namespace App\Domains\Projects\Repositories;
use App\Domains\Projects\Models\ProjectStatus;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;

interface ProjectStatusRepositoryInterface extends RepositoryInterface
{
    /**
     * @return ProjectStatus[]|null
     */
    public function get(): ?array;

    /**
     * @return ProjectStatus[]|null
     */
    public function getVisible(): ?array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return ProjectStatus|null
     */
    public function getById(string $id, bool $withRelations = true): ?ProjectStatus;

    /**
     * @param string $slug
     * @param bool $withRelations
     * @return ProjectStatus|null
     */
    public function getBySlug(string $slug, bool $withRelations = true): ?ProjectStatus;


    /**
     * @param CactusEntity|ProjectStatus $entity
     * @return ProjectStatus|null
     */
    public function store(CactusEntity|ProjectStatus $entity): ?ProjectStatus;

    /**
     * @param CactusEntity|ProjectStatus $entityx
     * @param string $id
     * @return ProjectStatus|null
     */
    public function update(CactusEntity|ProjectStatus $entity, string $id): ?ProjectStatus;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;
}
