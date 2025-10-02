<?php

namespace App\Domains\Clients\Services;

use App\Domains\Clients\Models\Client;
use App\Domains\Clients\Repositories\ClientRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class ClientService
{
    private ClientRepositoryInterface $repository;

    /**
     * @param ClientRepositoryInterface $repository
     */
    public function __construct(ClientRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Client[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $clientId
     * @param bool $withRelations
     * @return Client|null
     */
    public function getById(string $clientId, bool $withRelations = true): ?Client
    {
        return $this->repository->getById($clientId, $withRelations);
    }

    public function createOrUpdate(Client $client): Client
    {
        return $this->repository->createOrUpdate($client);
    }

    /**
     * @param Client $client
     * @return Client
     */
    public function store(Client $client): Client
    {
        return $this->repository->store($client);
    }

    public function storeOrUpdate(Client $client): Client
    {
        return $this->repository->storeOrUpdate($client);
    }

    /**
     * @param Client $client
     * @param string $id
     * @return Client
     */
    public function update(Client $client, string $id): Client
    {
        return $this->repository->update($client, $id);
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
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableClients(array $filters = []): JsonResponse
    {
        return $this->repository->dataTableClients($filters);
    }

    /**
     * @return array
     */
    public function getTableColumns() : array
    {
        return $this->repository->getTableColumns();
    }

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function namesPaginated(?string $searchTerm, int $offset, int $resultCount): array
    {
        return $this->repository->namesPaginated($searchTerm, $offset, $resultCount);
    }

    /**
     * @param string $id
     * @param array $morphs
     * @param array $relations
     * @return Client
     */
    public function getByIdWithMorphsAndRelations(string $id, array $morphs = [], array $relations = []): Client
    {
        return $this->repository->getByIdWithMorphsAndRelations($id, $morphs, $relations);
    }
}
