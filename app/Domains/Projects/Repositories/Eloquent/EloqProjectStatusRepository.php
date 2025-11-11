<?php

namespace App\Domains\Projects\Repositories\Eloquent;

use App\Domains\Projects\Models\ProjectStatus;
use App\Domains\Projects\Repositories\ProjectStatusRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;

class EloqProjectStatusRepository implements ProjectStatusRepositoryInterface
{

    public function __construct(
        private Models\ProjectStatus $model)
    {}
    /**
     * @inheritDoc
     */
    public function get(): ?array
    {
        $status = $this->model::all();

        return ObjectSerializer::deserialize($status?->toJson() ?? "{}",  "array<". ProjectStatus::class .">", 'json');
    }

    /**
     * @inheritDoc
     */
    public function getVisible(): ?array
    {
        $status = $this->model::visible()->get();

        return ObjectSerializer::deserialize($status?->toJson() ?? "{}",  "array<". ProjectStatus::class .">", 'json');

    }

    /**
     * @inheritDoc
     */
    public function getById(string $id, bool $withRelations = true): ?ProjectStatus
    {
        $status = $this->model::find($id);

        return ObjectSerializer::deserialize($status?->toJson() ?? "{}",  ProjectStatus::class , 'json');

    }

    /**
     * @inheritDoc
     */
    public function getBySlug(string $slug, bool $withRelations = true): ?ProjectStatus
    {
        $status = $this->model::where('slug',$slug)->firstOrFail();

        return ObjectSerializer::deserialize($status?->toJson() ?? "{}",  ProjectStatus::class , 'json');

    }

    /**
     * @inheritDoc
     */
    public function store(CactusEntity|ProjectStatus $entity): ?ProjectStatus
    {
        // TODO: Implement store() method.
    }

    /**
     * @inheritDoc
     */
    public function update(CactusEntity|ProjectStatus $entity, string $id): ?ProjectStatus
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
