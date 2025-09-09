<?php

namespace App\Domains\Visits\Repositories\Eloquent;

use App\Domains\Visits\Models\VisitStatus;
use App\Domains\Visits\Repositories\Eloquent\Models;
use App\Domains\Visits\Repositories\VisitStatusRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;

class EloqVisitStatusRepository implements VisitStatusRepositoryInterface
{

    public function __construct(
        private  Models\VisitStatus $model)
    {}
    /**
     * @inheritDoc
     */
    public function get(): ?array
    {
        $status = $this->model::all();

        return ObjectSerializer::deserialize($status?->toJson() ?? "{}",  "array<". VisitStatus::class .">", 'json');
    }

    /**
     * @inheritDoc
     */
    public function getVisible(): ?array
    {
        $status = $this->model::visible()->get();

        return ObjectSerializer::deserialize($status?->toJson() ?? "{}",  "array<". VisitStatus::class .">", 'json');

    }

    /**
     * @inheritDoc
     */
    public function getById(string $id, bool $withRelations = true): ?VisitStatus
    {
        $status = $this->model::find($id);

        return ObjectSerializer::deserialize($status?->toJson() ?? "{}",  VisitStatus::class , 'json');

    }

    /**
     * @inheritDoc
     */
    public function store(CactusEntity|VisitStatus $entity): ?VisitStatus
    {
        // TODO: Implement store() method.
    }

    /**
     * @inheritDoc
     */
    public function update(CactusEntity|VisitStatus $entity, string $id): ?VisitStatus
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
