<?php

namespace App\Domains\ErpSales\Services;

use App\Domains\ErpSales\Models\Salesman;
use App\Domains\ErpSales\Repositories\SalesManRepositoryInterface;

class SalesmanService
{
    private SalesmanRepositoryInterface $repository;

    /**
     * @param SalesmanRepositoryInterface $repository
     */
    public function __construct(SalesmanRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Salesman[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $salesmanId
     * @param bool $withRelations
     * @return Salesman|null
     */
    public function getById(string $salesmanId, bool $withRelations = true): ?Salesman
    {
        return $this->repository->getById($salesmanId, $withRelations);
    }

    /**
     * @param Salesman $salesman
     * @return Salesman
     */
    public function store(Salesman $salesman): Salesman
    {
        $salesmanDTO = $this->repository->store($salesman);
        return $salesmanDTO;
    }

    /**
     * @param Salesman $salesman
     * @param string $id
     * @return Salesman
     */
    public function update(Salesman $salesman, string $id): Salesman
    {
        $salesmanDTO = $this->repository->update($salesman, $id);
        return $salesmanDTO;
    }

    public function createOrUpdateByErpId(Salesman $salesman, string $erpId): Salesman
    {
        $salesmanDTO = $this->repository->createOrUpdateByErpId($salesman, $erpId);
        return $salesmanDTO;
    }

    /**
     * @param string $id
     * @return bool
     *
     */
    public function deleteById(string $id): bool
    {
        return $this->repository->deleteById($id);
    }
}
