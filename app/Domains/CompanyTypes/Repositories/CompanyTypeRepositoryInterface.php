<?php

namespace App\Domains\CompanyTypes\Repositories;

use App\Domains\CompanyTypes\Models\CompanyType;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;

interface CompanyTypeRepositoryInterface extends RepositoryInterface
{
    /**
     * @return CompanyType[]
     */
    public function get(): array;

    /**
     * @param string $id
     * @return CompanyType|null
     */
    public function getById(string $id): ?CompanyType;

    /**
     * @param CactusEntity|CompanyType $entity
     * @return CompanyType
     */
    public function store(CactusEntity|CompanyType $entity): CompanyType;

    /**
     * @param CactusEntity|CompanyType $entity
     * @param string $id
     * @return CompanyType
     */
    public function update(CactusEntity|CompanyType $entity, string $id): CompanyType;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;


}
