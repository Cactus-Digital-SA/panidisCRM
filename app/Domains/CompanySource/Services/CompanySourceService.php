<?php

namespace App\Domains\CompanySource\Services;

use App\Domains\CompanySource\Models\CompanySource;
use App\Domains\CompanySource\Repositories\CompanySourceRepositoryInterface;
use App\Models\CactusEntity;

class CompanySourceService
{
    private CompanySourceRepositoryInterface $repository;

    /**
     * @param CompanySourceRepositoryInterface $repository
     */
    public function __construct(CompanySourceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return CompanySource[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $id
     * @return CompanySource|null
     */
    public function getById(string $id): ?CompanySource
    {
        return $this->repository->getById($id);
    }

    /**
     * @param CactusEntity|CompanySource $companyType
     * @return CompanySource|null
     */
    public function store(CactusEntity|CompanySource $companyType): ?CompanySource
    {
        return $this->repository->store($companyType);
    }

    /**
     * @param CactusEntity|CompanySource $companyType
     * @param string $id
     * @return CompanySource|null
     */
    public function update(CactusEntity|CompanySource $companyType, string $id): ?CompanySource
    {
        return $this->repository->update($companyType, $id);
    }

    /**
     * @param string $id
     * @return boolean
     */
    public function deleteById(string $id): bool
    {
        return $this->repository->deleteById($id);
    }

}
