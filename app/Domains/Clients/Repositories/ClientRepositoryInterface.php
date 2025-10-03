<?php

namespace App\Domains\Clients\Repositories;

use App\Domains\Clients\Models\Client;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

interface ClientRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Client[]
     */
    public function get(): array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return Client|null
     */
    public function getById(string $id, bool $withRelations = true): ?Client;

    /**
     * @param CactusEntity|Client $entity
     * @return Client|null
     */
    public function createOrUpdate(CactusEntity|Client $entity): ?Client;

    /**
     * @param CactusEntity|Client $entity
     * @return Client|null
     */
    public function store(CactusEntity|Client $entity): ?Client;

    /**
     * @param CactusEntity|Client $entity
     * @return Client|null
     */
    public function storeOrUpdate(CactusEntity|Client $entity): ?Client;

    /**
     * @param CactusEntity|Client $entity
     * @param string $id
     * @return Client|null
     */
    public function update(CactusEntity|Client $entity, string $id): ?Client;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableClients(array $filters = []): JsonResponse;

    /**
     * @return array|null
     */
    public function getTableColumns():?array;

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function namesPaginated(?string $searchTerm, int $offset, int $resultCount): array;

    /**
     * @param string $modelId
     * @param array $morphs
     * @return Client|null
     */
    public function getByIdWithMorphsAndRelations(string $modelId, array $morphs = [], array $relations = []): ?Client;
}
