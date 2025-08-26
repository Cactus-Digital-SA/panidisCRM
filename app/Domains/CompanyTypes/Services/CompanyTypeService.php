<?php

namespace App\Domains\CompanyTypes\Services;

use App\Domains\CompanyTypes\Models\CompanyType;
use App\Domains\CompanyTypes\Repositories\CompanyTypeRepositoryInterface;
use App\Models\CactusEntity;

class CompanyTypeService
{
    private CompanyTypeRepositoryInterface $repository;

    /**
     * @param CompanyTypeRepositoryInterface $repository
     */
    public function __construct(CompanyTypeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return CompanyType[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $id
     * @return CompanyType|null
     */
    public function getById(string $id): ?CompanyType
    {
        return $this->repository->getById($id);
    }

    /**
     * @param CactusEntity|CompanyType $companyType
     * @return CompanyType|null
     */
    public function store(CactusEntity|CompanyType $companyType): ?CompanyType
    {
        return $this->repository->store($companyType);
    }

    /**
     * @param CactusEntity|CompanyType $companyType
     * @param string $id
     * @return CompanyType|null
     */
    public function update(CactusEntity|CompanyType $companyType, string $id): ?CompanyType
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
