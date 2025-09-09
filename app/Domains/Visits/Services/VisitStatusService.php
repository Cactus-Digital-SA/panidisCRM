<?php

namespace App\Domains\Visits\Services;

use App\Domains\Visits\Models\VisitStatus;
use App\Domains\Visits\Repositories\VisitStatusRepositoryInterface;

class VisitStatusService
{
    /**
     * @param VisitStatusRepositoryInterface $repository
     */
    public function __construct(private VisitStatusRepositoryInterface $repository)
    {}

    /**
     * @return VisitStatus[]|null
     */
    public function get() : ?array
    {
        return $this->repository->get();
    }

    /**
     * @return VisitStatus[]|null
     */
    public function getVisible() : ?array
    {
        return $this->repository->getVisible();
    }

    /**
     * @param string $id
     * @param bool $withRelations
     * @return VisitStatus
     */
    public function getById(string $id, bool $withRelations = true) : VisitStatus
    {
        return $this->repository->getById($id,$withRelations);
    }
}
