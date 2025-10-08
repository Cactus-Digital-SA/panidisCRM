<?php

namespace App\Domains\Companies\Repositories;

use App\Domains\Companies\Models\Company;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

interface CompanyRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Company[]
     */
    public function get(): array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return Company|null
     */
    public function getById(string $id, bool $withRelations = true): ?Company;

    /**
     * @param CactusEntity|Company $entity
     * @return Company|null
     */
    public function store(CactusEntity|Company $entity): ?Company;

    /**
     * @param Company $entity
     * @return Company|null
     */
    public function storeOrUpdate(Company $entity): ?Company;

    /**
     * @param CactusEntity|Company $entity
     * @param string $id
     * @return Company|null
     */
    public function update(CactusEntity|Company $entity, string $id): ?Company;

    /**
     * @param CactusEntity|Company $entity
     * @param string $id
     * @return Company|null
     */
    public function updateErpData(CactusEntity|Company $entity, string $companyId): ?Company;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;

    /**
     * @param Company $entity
     * @param string $companyId
     * @return bool
     */
    public function storeContacts(Company $entity, string $companyId): bool;

    /**
     * @param int $userId
     * @param string $companyId
     * @return bool|null
     */
    public function deleteContactByUserId(int $userId, string $companyId): ?bool;

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableCompanies(array $filters = []): JsonResponse;

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableCompaniesContacts(array $filters = []): JsonResponse;

    /**
     * @return array|null
     */
    public function getContactsTableColumns():?array;

    /**
     * Summary of createOrUpdateByCompanyId
     * @param \App\Domains\Companies\Models\Company|\App\Models\CactusEntity $entity|null
     * @param string $companyId
     * @return void
     */
    public function createOrUpdateByCompanyId(Company|CactusEntity $entity, ?string $companyId): ?Company;

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function namesPaginated(?string $searchTerm, int $offset, int $resultCount, ?string $type = null): array;

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function getContactsPaginatedByCompanyId(?string $searchTerm, int $offset, int $resultCount, int $companyId): array;

    /**
     * @param array $tagIds
     * @param string $companyId
     * @return bool|null
     */
    public function storeTags(array $tagIds, string $companyId): ?bool;
}
