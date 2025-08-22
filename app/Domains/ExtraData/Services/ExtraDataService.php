<?php

namespace App\Domains\ExtraData\Services;

use App\Domains\ExtraData\Enums\ExtraDataModelsEnum;
use App\Domains\ExtraData\Models\ExtraData;
use App\Domains\ExtraData\Repositories\ExtraDataRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ExtraDataService
{
    public function __construct(private ExtraDataRepositoryInterface $repository)
    {
    }

    public function get(): array
    {
        return $this->repository->get();
    }

    public function getByModel(ExtraDataModelsEnum $model): array
    {
        return $this->repository->getByModel($model);
    }

    public function getById(string $id): ?ExtraData
    {
        return $this->repository->getById($id);
    }

    public function store(ExtraData $extraData): ?ExtraData
    {
        return $this->repository->store($extraData);
    }

    public function update(ExtraData $extraData, string $id): ?ExtraData
    {
        return $this->repository->update($extraData, $id);
    }

    public function deleteById(string $id): bool
    {
        return $this->repository->deleteById($id);
    }

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableExtraData(array $filters = []): JsonResponse
    {
        return $this->repository->dataTableExtraData($filters);
    }

    /**
     * @return array
     */
    public function getTableColumns() : array
    {
        return $this->repository->getTableColumns();
    }

    public function assignExtraDataToModel(array $extraData = []) {
        return $this->repository->assignExtraDataToModel($extraData);
    }

}
