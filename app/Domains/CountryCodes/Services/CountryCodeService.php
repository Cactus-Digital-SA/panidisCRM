<?php

namespace App\Domains\CountryCodes\Services;

use App\Domains\CountryCodes\Models\CountryCode;
use App\Domains\CountryCodes\Repositories\CountryCodeRepositoryInterface;
use App\Models\CactusEntity;
use Illuminate\Http\JsonResponse;

class CountryCodeService
{
    /**
     * @var CountryCodeRepositoryInterface
     */
    private CountryCodeRepositoryInterface $repository;

    /**
     * @param CountryCodeRepositoryInterface $repository
     */
    public function __construct(CountryCodeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return CountryCode[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $id
     * @return CountryCode|null
     */
    public function getById(string $id): ?CountryCode
    {
        return $this->repository->getById($id);
    }

    /**
     * @param CactusEntity|CountryCode $CountryCode
     * @return CountryCode|null
     */
    public function store(CactusEntity|CountryCode $CountryCode): ?CountryCode
    {
        return $this->repository->store($CountryCode);
    }

    /**
     * @param CactusEntity|CountryCode $CountryCode
     * @param string $id
     * @return CountryCode|null
     */
    public function update(CactusEntity|CountryCode $CountryCode, string $id): ?CountryCode
    {
        return $this->repository->update($CountryCode, $id);
    }

    /**
     * @param string $id
     * @return boolean
     */
    public function deleteById(string $id): bool
    {
        return $this->repository->deleteById($id);
    }

    public function dataTableCountryCodes(array $filters = []): JsonResponse
    {
        return $this->repository->dataTableCountryCodes($filters);
    }



}
