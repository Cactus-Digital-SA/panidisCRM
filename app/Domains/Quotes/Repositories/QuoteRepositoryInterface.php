<?php

namespace App\Domains\Quotes\Repositories;

use App\Domains\Quotes\Models\Quote;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;
use Illuminate\Http\JsonResponse;

interface QuoteRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Quote[]
     */
    public function get(): array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return Quote|null
     */
    public function getById(string $id, bool $withRelations = true): ?Quote;

    /**
     * @param string $uuid
     * @param bool $withRelations
     * @return Quote|null
     */
    public function getByUuid(string $uuid, bool $withRelations = true): ?Quote;

    /**
     * @param CactusEntity|Quote $entity
     * @return Quote|null
     */
    public function store(CactusEntity|Quote $entity): ?Quote;

    /**
     * @param CactusEntity|Quote $entity
     * @param string $id
     * @return Quote|null
     */
    public function update(CactusEntity|Quote $entity, string $id): ?Quote;

    /**
     * @param CactusEntity|Quote $entity
     * @param string $id
     * @return Quote|null
     */
    public function updateStatus(CactusEntity|Quote $entity, string $id): ?Quote;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableQuotes(array $filters = []): JsonResponse;

    /**
     * @return array|null
     */
    public function getTableColumns():?array;

    /**
     * @param string $modelId
     * @param array $morphs
     * @return Quote|null
     */
    public function getByIdWithMorphsAndRelations(string $modelId, array $morphs = [], array $relations = []): ?Quote;
}
