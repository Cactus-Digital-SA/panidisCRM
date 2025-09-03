<?php

namespace App\Domains\Tickets\Repositories;
use App\Domains\Tickets\Models\TicketStatus;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;

interface TicketStatusRepositoryInterface extends RepositoryInterface
{
    /**
     * @return TicketStatus[]|null
     */
    public function get(): ?array;

    /**
     * @return TicketStatus[]|null
     */
    public function getVisible(): ?array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return TicketStatus|null
     */
    public function getById(string $id, bool $withRelations = true): ?TicketStatus;


    /**
     * @param CactusEntity|TicketStatus $entity
     * @return TicketStatus|null
     */
    public function store(CactusEntity|TicketStatus $entity): ?TicketStatus;

    /**
     * @param CactusEntity|TicketStatus $entity
     * @param string $id
     * @return TicketStatus|null
     */
    public function update(CactusEntity|TicketStatus $entity, string $id): ?TicketStatus;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;
}
