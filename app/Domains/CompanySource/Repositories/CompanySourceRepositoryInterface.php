<?php

namespace App\Domains\CompanySource\Repositories;

use App\Domains\CompanySource\Models\CompanySource;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;

interface CompanySourceRepositoryInterface extends RepositoryInterface
{
    /**
     * @return CompanySource[]
     */
    public function get(): array;

    /**
     * @param string $id
     * @return CompanySource|null
     */
    public function getById(string $id): ?CompanySource;

    /**
     * @param CactusEntity|CompanySource $entity
     * @return CompanySource
     */
    public function store(CactusEntity|CompanySource $entity): CompanySource;

    /**
     * @param CactusEntity|CompanySource $entity
     * @param string $id
     * @return CompanySource
     */
    public function update(CactusEntity|CompanySource $entity, string $id): CompanySource;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;


}
