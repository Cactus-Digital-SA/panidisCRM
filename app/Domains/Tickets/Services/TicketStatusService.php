<?php

namespace App\Domains\Tickets\Services;

use App\Domains\Tickets\Models\TicketStatus;
use App\Domains\Tickets\Repositories\TicketStatusRepositoryInterface;

readonly class TicketStatusService
{
    /**
     * @param TicketStatusRepositoryInterface $repository
     */
    public function __construct(private TicketStatusRepositoryInterface $repository)
    {}

    /**
     * @return TicketStatus[]|null
     */
    public function get() : ?array
    {
        return $this->repository->get();
    }

    /**
     * @return TicketStatus[]|null
     */
    public function getVisible() : ?array
    {
        return $this->repository->getVisible();
    }

    /**
     * @param string $id
     * @param bool $withRelations
     * @return TicketStatus
     */
    public function getById(string $id, bool $withRelations = true) : TicketStatus
    {
        return $this->repository->getById($id,$withRelations);
    }
}
