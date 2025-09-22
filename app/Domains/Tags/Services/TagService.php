<?php

namespace App\Domains\Tags\Services;

use App\Domains\Tags\Enums\TagTypesEnum;
use App\Domains\Tags\Models\Tag;
use App\Domains\Tags\Repositories\TagRepositoryInterface;
use App\Models\CactusEntity;

class TagService
{
    private TagRepositoryInterface $repository;

    /**
     * @param TagRepositoryInterface $repository
     */
    public function __construct(TagRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Tag[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    public function getByType(string $tagTypeId): array
    {
        return $this->repository->getByType($tagTypeId);
    }

    /**
     * @param string $tagId
     * @return Tag|null
     */
    public function getById(string $tagId): ?Tag
    {
        return $this->repository->getById($tagId);
    }

    /**
     * @param CactusEntity|Tag $tag
     * @return Tag
     */
    public function store(CactusEntity|Tag $tag): Tag
    {
        return $this->repository->store($tag);
    }

    /**
     * @param CactusEntity|Tag $tag
     * @param string $id
     * @return Tag
     */
    public function update(CactusEntity|Tag $tag, string $id): Tag
    {
        return $this->repository->update($tag, $id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool
    {
        return $this->repository->deleteById($id);
    }

}
