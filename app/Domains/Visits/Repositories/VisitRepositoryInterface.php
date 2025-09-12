<?php

namespace App\Domains\Visits\Repositories;

use App\Domains\Visits\Models\Visit;
use App\Domains\Visits\Models\VisitsStatusesPivot;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

interface VisitRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Visit[]
     */
    public function get(): array;

    /**
     * @return Visit[]
     */
    public function getByStatus(string $statusId): array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return Visit|null
     */
    public function getById(string $id, bool $withRelations = true): ?Visit;

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function searchPaginated(?string $searchTerm, int $offset, int $resultCount): array;

    /**
     * @param string $modelId
     * @param array $morphs
     * @return Visit|null
     */
    public function getByIdWithMorphs(string $modelId, array $morphs = []): ?Visit;

    /**
     * @param string $modelId
     * @param array $morphs
     * @param array $relations
     * @return Visit|null
     */
    public function getByIdWithMorphsAndRelations(string $modelId, array $morphs = [], array $relations = []): ?Visit;

    /**
     * @param CactusEntity|Visit $entity
     * @return Visit|null
     */
    public function store(CactusEntity|Visit $entity): ?Visit;

    /**
     * @param Visit $entity
     * @param string $visitId
     * @return bool
     */
    public function storeContacts(Visit $entity, string $visitId): bool;

    /**
     * @param CactusEntity|Visit $entity
     * @param string $id
     * @return Visit|null
     */
    public function update(CactusEntity|Visit $entity, string $id): ?Visit;

    /**
     * @param CactusEntity|VisitsStatusesPivot $entity
     * @param string $visitId
     * @return Visit|null
     */
    public function updatePivotPositionAndStatus(CactusEntity|VisitsStatusesPivot $entity, string $visitId): ?Visit;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableVisits(array $filters = []): JsonResponse;

    /**
     * @return array|null
     */
    public function getTableColumns():?array;

    /**
     * @return array|null
     */
    public function getDashboardTableColumns():?array;
}
