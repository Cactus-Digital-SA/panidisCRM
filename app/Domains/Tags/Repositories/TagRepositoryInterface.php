<?php

namespace App\Domains\Tags\Repositories;

use App\Domains\Tags\Enums\TagTypesEnum;
use App\Domains\Tags\Models\Tag;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;

interface TagRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Tag[]
     */
    public function get();

    /**
     * @param string $tagTypeId
     * @return Tag[]
     */
    public function getByType(string $tagTypeId): array;

    public function getById(string $id): ?Tag;

    public function store(CactusEntity|Tag $entity): ?Tag;

    public function update(CactusEntity|Tag $entity, string $id): ?Tag;

    public function deleteById(string $id): bool;

}
