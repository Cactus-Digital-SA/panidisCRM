<?php

namespace App\Domains\Items\Services;

use App\Domains\Items\Models\Item;
use App\Domains\Items\Repositories\ItemRepositoryInterface;
use Illuminate\Support\Collection;

class ItemService
{
    private ItemRepositoryInterface $repository;

    /**
     * @param ItemRepositoryInterface $repository
     */
    public function __construct(ItemRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Item[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $id
     * @return Item|null
     */
    public function getById(string $id): ?Item
    {
        return $this->repository->getById($id);
    }

    /**
     * @param Item $item
     * @return Item
     */
    public function store(Item $item): Item
    {
        return $this->repository->store($item);
    }

    public function storeOrUpdate(Item $item): Item
    {
        return $this->repository->storeOrUpdate($item);
    }

    /**
     * @param Item $item
     * @param string $id
     * @return Item
     */
    public function update(Item $item, string $id): Item
    {
        return $this->repository->update($item, $id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool
    {
        return $this->repository->deleteById($id);
    }

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function itemsPaginated(?string $searchTerm, int $offset, int $resultCount): array
    {
        return $this->repository->itemsPaginated($searchTerm, $offset, $resultCount);
    }

}
