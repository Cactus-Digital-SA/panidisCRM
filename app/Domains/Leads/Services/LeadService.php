<?php

namespace App\Domains\Leads\Services;

use App\Domains\Events\Enums\EventTypeEnum;
use App\Domains\Events\Models\CactusEvent;
use App\Domains\Events\Services\CactusEventService;
use App\Domains\Leads\Models\Lead;
use App\Domains\Leads\Repositories\Eloquent\Models\LeadStatusEnum;
use App\Domains\Leads\Repositories\LeadRepositoryInterface;
use App\Models\Enums\EloqMorphEnum;
use Illuminate\Http\JsonResponse;

class LeadService
{
    private LeadRepositoryInterface $repository;

    /**
     * @param LeadRepositoryInterface $repository
     */
    public function __construct(LeadRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Lead[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $id
     * @return Lead|null
     */
    public function getById(string $leadId, bool $withRelations = true): ?Lead
    {
        return $this->repository->getById($leadId, $withRelations);
    }

    /**
     * @param Lead $lead
     * @return Lead|null
     */
    public function store(Lead $lead): Lead
    {
        $leadDTO = $this->repository->store($lead);
        return $leadDTO;
    }

    /**
     * @param Lead $lead
     * @param string $id
     * @return Lead|null
     */
    public function update(Lead $lead, string $id): Lead
    {
        $leadDTO = $this->repository->update($lead, $id);
        return $leadDTO;
    }

    public function updateStatus(Lead $lead, string $id): Lead
    {
        return $this->repository->updateStatus($lead, $id);
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

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableLeads(array $filters = []): JsonResponse
    {
        return $this->repository->dataTableLeads($filters);
    }

    /**
     * @return array
     */
    public function getTableColumns() : array
    {
        return $this->repository->getTableColumns();
    }

    /**
     * @param string $id
     * @param array $morphs
     * @param array $relations
     * @return Lead
     */
    public function getByIdWithMorphsAndRelations(string $id, array $morphs = [], array $relations = []): Lead
    {
        return $this->repository->getByIdWithMorphsAndRelations($id, $morphs, $relations);
    }

}
