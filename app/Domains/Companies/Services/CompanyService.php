<?php

namespace App\Domains\Companies\Services;

use App\Domains\Companies\Models\Company;
use App\Domains\Companies\Repositories\CompanyRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class CompanyService
{
    private CompanyRepositoryInterface $repository;

    /**
     * @param CompanyRepositoryInterface $repository
     */
    public function __construct(CompanyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Company[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $companyId
     * @param bool $withRelations
     * @return Company|null
     */
    public function getById(string $companyId, bool $withRelations = true): ?Company
    {
        return $this->repository->getById($companyId, $withRelations);
    }

    /**
     * Summary of createOrUpdateByCompanyId
     * @param \App\Domains\Companies\Models\Company $entity
     * @param mixed $companyId
     * @return Company|null
     */
    public function createOrUpdateByCompanyId(Company $entity, ?string $companyId): ?Company
    {
        return $this->repository->createOrUpdateByCompanyId($entity, $companyId);
    }

    /**
     * @param Company $company
     * @return Company
     */
    public function store(Company $company): Company
    {
        return $this->repository->store($company);
    }

    /**
     * @param Company $company
     * @param string $id
     * @return Company
     */
    public function update(Company $company, string $id): Company
    {
        return $this->repository->update($company, $id);
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
     * @param Company $entity
     * @param string $companyId
     * @return bool|null
     */
    public function storeContacts(Company $entity, string $companyId): ?bool
    {
        return $this->repository->storeContacts($entity, $companyId);
    }

    /**
     * @param int $userId
     * @param string $companyId
     * @return bool|null
     */
    public function deleteContactByUserId(int $userId, string $companyId): ?bool
    {
        return $this->repository->deleteContactByUserId($userId, $companyId);
    }

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableCompanies(array $filters = []): JsonResponse
    {
        return $this->repository->dataTableCompanies($filters);
    }

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableCompaniesContacts(array $filters = []): JsonResponse
    {
        return $this->repository->dataTableCompaniesContacts($filters);
    }

    /**
     * @return array
     */
    public function getContactsTableColumns() : array
    {
        return $this->repository->getContactsTableColumns();
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
}
