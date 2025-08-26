<?php

namespace App\Domains\CountryCodes\Repositories;

use App\Domains\CountryCodes\Models\CountryCode;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;
use Illuminate\Http\JsonResponse;

interface CountryCodeRepositoryInterface extends RepositoryInterface
{
    /**
     * @return CountryCode[]
     */
    public function get(): array;

    /**
     * @param string $id
     * @return CountryCode|null
     */
    public function getById(string $id): ?CountryCode;

    /**
     * @param CactusEntity|CountryCode $entity
     * @return CountryCode|null
     */
    public function store(CactusEntity|CountryCode $entity): ?CountryCode;

    /**
     * @param CactusEntity|CountryCode $entity
     * @param string $id
     * @return CountryCode|null
     */
    public function update(CactusEntity|CountryCode $entity, string $id): ?CountryCode;

    /**
     * @param string $id
     * @return boolean
     */
    public function deleteById(string $id): bool;

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableCountryCodes(array $filters = []): JsonResponse;



}
