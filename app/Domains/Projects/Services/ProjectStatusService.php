<?php

namespace App\Domains\Projects\Services;

use App\Domains\Projects\Models\ProjectStatus;
use App\Domains\Projects\Repositories\ProjectStatusRepositoryInterface;

class ProjectStatusService
{
    private ProjectStatusRepositoryInterface $repository;

    /**
     * @param ProjectStatusRepositoryInterface $repository
     */
    public function __construct(ProjectStatusRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return ProjectStatus[]|null
     */
    public function get() : ?array
    {
        return $this->repository->get();
    }

    /**
     * @return ProjectStatus[]|null
     */
    public function getVisible() : ?array
    {
        return $this->repository->getVisible();
    }

    /**
     * @param string $id
     * @param bool $withRelations
     * @return ProjectStatus
     */
    public function getById(string $id, bool $withRelations = true) : ProjectStatus
    {
        return $this->repository->getById($id,$withRelations);
    }

    /**
     * @param string $slug
     * @param bool $withRelations
     * @return ProjectStatus
     */
    public function getBySlug(string $slug, bool $withRelations = true) : ProjectStatus
    {
        return $this->repository->getBySlug($slug,$withRelations);
    }
}
