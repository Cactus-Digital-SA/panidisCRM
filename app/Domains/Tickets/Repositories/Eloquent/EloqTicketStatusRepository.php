<?php

namespace App\Domains\Tickets\Repositories\Eloquent;

use App\Domains\Tickets\Models\TicketStatus;
use App\Domains\Tickets\Repositories\TicketStatusRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;

class EloqTicketStatusRepository implements TicketStatusRepositoryInterface
{

    public function __construct(
        private  Models\TicketStatus $model)
    {}
    /**
     * @inheritDoc
     */
    public function get(): ?array
    {
        $status = $this->model::all();

        return ObjectSerializer::deserialize($status?->toJson() ?? "{}",  "array<". TicketStatus::class .">", 'json');
    }

    /**
     * @inheritDoc
     */
    public function getVisible(): ?array
    {
        $status = $this->model::visible()->get();

        return ObjectSerializer::deserialize($status?->toJson() ?? "{}",  "array<". TicketStatus::class .">", 'json');

    }

    /**
     * @inheritDoc
     */
    public function getById(string $id, bool $withRelations = true): ?TicketStatus
    {
        $status = $this->model::find($id);

        return ObjectSerializer::deserialize($status?->toJson() ?? "{}",  TicketStatus::class , 'json');

    }

    /**
     * @inheritDoc
     */
    public function store(CactusEntity|TicketStatus $entity): ?TicketStatus
    {
        // TODO: Implement store() method.
    }

    /**
     * @inheritDoc
     */
    public function update(CactusEntity|TicketStatus $entity, string $id): ?TicketStatus
    {
        // TODO: Implement update() method.
    }

    /**
     * @inheritDoc
     */
    public function deleteById(string $id): bool
    {
        // TODO: Implement deleteById() method.
    }
}
