<?php

namespace App\Domains\Visits\Services;

use App\Domains\Visits\Models\Visit;
use App\Domains\Visits\Models\VisitsStatusesPivot;
use App\Domains\Visits\Repositories\VisitRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class VisitService
{
    /**
     * @param VisitRepositoryInterface $repository
     */
    public function __construct(
        private VisitRepositoryInterface $repository,
    )
    {}

    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $statusId
     * @return Visit[]
     */
    public function getByStatus(string $statusId): array
    {
        return $this->repository->getByStatus($statusId);
    }

    /**
     * @param string $id
     * @param bool $withRelations
     * @return Visit|null
     */
    public function getById(string $id, bool $withRelations = true) : ?Visit
    {
        return $this->repository->getById($id, $withRelations);
    }

    /**
     * @param Visit $ticket
     * @return Visit|null
     */
    public function store(Visit $ticket): ?Visit
    {
        $ticket = $this->repository->store($ticket);
        return $ticket;
    }

    /**
     * @param Visit $entity
     * @param string $ticketId
     * @return bool|null
     */
    public function storeContacts(Visit $entity, string $ticketId): ?bool
    {
        return $this->repository->storeContacts($entity, $ticketId);
    }

    /**
     * @param Visit $ticket
     * @return Visit|null
     */
    public function update(Visit $ticket, string $id): ?Visit
    {
        return $this->repository->update($ticket, $id);
    }

    public function updatePivotPositionAndStatus(VisitsStatusesPivot $pivot, string $visitId): ?Visit
    {
        $visit = $this->repository->updatePivotPositionAndStatus($pivot, $visitId);

        return $visit;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id) : bool
    {
        return $this->repository->deleteById($id);
    }

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableVisits(array $filters) : JsonResponse
    {
        return $this->repository->dataTableVisits($filters);
    }

    /**
     * @return array
     */
    public function getTableColumns() : array
    {
        return $this->repository->getTableColumns();
    }

    public function getDashboardTableColumns() : array
    {
        return $this->repository->getDashboardTableColumns();
    }

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function searchPaginated(?string $searchTerm, int $offset, int $resultCount): array
    {
        return $this->repository->searchPaginated($searchTerm, $offset, $resultCount);
    }

    /**
     * @param string $id
     * @param array $morphs
     * @return Visit
     */
    public function getByIdWithMorphs(string $id, array $morphs = []): Visit
    {
        return $this->repository->getByIdWithMorphs($id, $morphs);
    }


    /**
     * @param string $id
     * @param array $morphs
     * @param array $relations
     * @return Visit
     */
    public function getByIdWithMorphsAndRelations(string $id, array $morphs = [], array $relations = []): Visit
    {
        return $this->repository->getByIdWithMorphsAndRelations($id, $morphs, $relations);
    }


}
