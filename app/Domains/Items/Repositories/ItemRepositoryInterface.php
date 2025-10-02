<?php

namespace App\Domains\Items\Repositories;

use App\Domains\Items\Models\Item;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;
use Illuminate\Http\JsonResponse;

interface ItemRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Item[]
     */
    public function get(): array;

    /**
     * @param string $id
     * @return Item|null
     */
    public function getById(string $id): ?Item;

    /**
     * @param CactusEntity|Item $entity
     * @return Item|null
     */
    public function store(CactusEntity|Item $entity): ?Item;

    /**
     * @param CactusEntity|Item $entity
     * @return Item|null
     */
    public function storeOrUpdate(CactusEntity|Item $entity): ?Item;

    /**
     * @param CactusEntity|Item $entity
     * @param string $id
     * @return Item|null
     */
    public function update(CactusEntity|Item $entity, string $id): ?Item;

    /**
     * @param string $id
     * @return boolean
     */
    public function deleteById(string $id): bool;

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableItems(array $filters = []): JsonResponse;
}
