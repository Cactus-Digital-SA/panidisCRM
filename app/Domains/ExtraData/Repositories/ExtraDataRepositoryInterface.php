<?php

namespace App\Domains\ExtraData\Repositories;

use App\Domains\ExtraData\Enums\ExtraDataModelsEnum;
use App\Domains\ExtraData\Models\ExtraData;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;
use Illuminate\Http\JsonResponse;

interface ExtraDataRepositoryInterface extends RepositoryInterface
{
    /**
     * @return array
     */
    public function get(): array;

    /**
     * @return array
     */
    public function getByModel(ExtraDataModelsEnum $model): array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return ExtraData|null
     */
    public function getById(string $id, bool $withRelations = true): ?ExtraData;

    /**
     * @param CactusEntity|ExtraData $entity
     * @return ExtraData|null
     */
    public function store(CactusEntity|ExtraData $entity): ?ExtraData;

    /**
     * @param CactusEntity|ExtraData $entity
     * @param string $id
     * @return ExtraData|null
     */
    public function update(CactusEntity|ExtraData $entity, string $id): ?ExtraData;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableExtraData(array $filters = []): JsonResponse;

    /**
     * @return array|null
     */
    public function getTableColumns():?array;

    public function assignExtraDataToModel(array $extraData = []);
}
