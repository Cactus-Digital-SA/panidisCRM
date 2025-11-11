<?php

namespace App\Domains\Projects\Services;

use App\Domains\Projects\Models\ProjectType;
use App\Domains\Projects\Repositories\ProjectTypeRepositoryInterface;

class ProjectTypeService
{

    private ProjectTypeRepositoryInterface $repository;

    /**
     * @param ProjectTypeRepositoryInterface $repository
     */
    public function __construct(ProjectTypeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param string $slug
     * @return ProjectType
     */
    public function getBySlug(string $slug) : ProjectType
    {
        return $this->repository->getBySlug($slug);
    }

    /**
     * @return ProjectType[]
     */
    public function get() : array
    {
        return $this->repository->get();
    }

}
