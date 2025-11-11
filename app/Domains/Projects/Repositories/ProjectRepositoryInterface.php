<?php

namespace App\Domains\Projects\Repositories;

use App\Domains\Projects\Models\Project;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;
use Illuminate\Http\JsonResponse;

interface ProjectRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Project[]
     */
    public function get(): array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return Project|null
     */
    public function getById(string $id, bool $withRelations = true): ?Project;

    /**
     * @param string $modelId
     * @param array $morphs
     * @return Project|null
     */
    public function getByIdWithMorphs(string $modelId, array $morphs = []): ?Project;


    /**
     * @param string $modelId
     * @param array $morphs
     * @return Project|null
     */
    public function getByIdWithMorphsAndRelations(string $modelId, array $morphs = [], array $relations = []): ?Project;

    /**
     * @param CactusEntity|Project $entity
     * @return Project|null
     */
    public function store(CactusEntity|Project $entity): ?Project;

    /**
     * @param string $projectId
     * @param string $ticketId
     * @return Project|null
     */
    public function assignTicket(string $projectId, string $ticketId): ?Project;

    /**
     * @param CactusEntity|Project $entityx
     * @param string $id
     * @return Project|null
     */
    public function update(CactusEntity|Project $entity, string $id): ?Project;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableProjects(array $filters = []): JsonResponse;

    /**
     * @return array|null
     */
    public function getTableColumns():?array;
}
