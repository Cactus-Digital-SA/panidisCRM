<?php

namespace App\Domains\Leads\Repositories;

use App\Domains\Leads\Models\Lead;
use App\Models\CactusEntity;
use App\Models\Enums\EloqMorphEnum;
use App\Repositories\RepositoryInterface;
use Illuminate\Http\JsonResponse;

interface LeadRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Lead[]
     */
    public function get(): array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return Lead|null
     */
    public function getById(string $id, bool $withRelations = true): ?Lead;

    /**
     * @param CactusEntity|Lead $entity
     * @return Lead|null
     */
    public function store(CactusEntity|Lead $entity): ?Lead;

    /**
     * @param CactusEntity|Lead $entity
     * @param string $id
     * @return Lead|null
     */
    public function update(CactusEntity|Lead $entity, string $id): ?Lead;

    /**
     * @param CactusEntity|Lead $entity
     * @param string $id
     * @return Lead|null
     */
    public function updateStatus(CactusEntity|Lead $entity, string $id): ?Lead;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableLeads(array $filters = []): JsonResponse;

    /**
     * @return array|null
     */
    public function getTableColumns():?array;

    /**
     * @param string $modelId
     * @param array $morphs
     * @return Lead|null
     */
    public function getByIdWithMorphsAndRelations(string $modelId, array $morphs = [], array $relations = []): ?Lead;

}
