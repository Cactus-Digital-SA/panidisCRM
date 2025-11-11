<?php

namespace App\Domains\Projects\Repositories\Eloquent;

use App\Domains\Projects\Models\ProjectType;
use App\Domains\Projects\Repositories\ProjectTypeRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;

class EloqProjectTypeRepository implements ProjectTypeRepositoryInterface
{

    private $model;

    /**
     * @param Models\ProjectType $projectType
     */
    public function __construct(Models\ProjectType $projectType)
    {
        $this->model = $projectType;
    }


    /**
     * @inheritDoc
     */
    public function get(): array
    {
        $type = $this->model::all();

        return ObjectSerializer::deserialize($type?->toJson() ?? "{}",  "array<". ProjectType::class .">", 'json');
    }

    /**
     * @inheritDoc
     */
    public function getVisible(): array
    {
        $type = $this->model::visible()->get();

        return ObjectSerializer::deserialize($type?->toJson() ?? "{}",  "array<". ProjectType::class .">", 'json');
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id, bool $withRelations = true): ?ProjectType
    {
        // TODO: Implement getById() method.
    }

    /**
     * @inheritDoc
     */
    public function getBySlug(string $slug, bool $withRelations = true): ?ProjectType
    {
        $type = $this->model::where('slug', $slug)->first();

        return ObjectSerializer::deserialize($type?->toJson() ?? "{}",  ProjectType::class  , 'json');
    }

    /**
     * @inheritDoc
     */
    public function store(ProjectType|CactusEntity $entity): ?ProjectType
    {
        // TODO: Implement store() method.
    }

    /**
     * @inheritDoc
     */
    public function update(CactusEntity|ProjectType $entity, string $id): ?ProjectType
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
