<?php

namespace App\Domains\Tickets\Repositories;

use App\Domains\Tickets\Models\Ticket;
use App\Domains\Tickets\Models\TicketsStatusesPivot;
use App\Helpers\Enums\ActionTypesEnum;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

interface TicketRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Ticket[]
     */
    public function get(): array;

    /**
     * @return Ticket[]
     */
    public function getByStatus(string $statusId): array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return Ticket|null
     */
    public function getById(string $id, bool $withRelations = true): ?Ticket;

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function searchPaginated(?string $searchTerm, int $offset, int $resultCount): array;

    /**
     * @param string $modelId
     * @param array $morphs
     * @return Ticket|null
     */
    public function getByIdWithMorphs(string $modelId, array $morphs = []): ?Ticket;


    /**
     * @param string $modelId
     * @param array $morphs
     * @param array $relations
     * @return Ticket|null
     */
    public function getByIdWithMorphsAndRelations(string $modelId, array $morphs = [], array $relations = []): ?Ticket;

    /**
     * @param CactusEntity|Ticket $entity
     * @return Ticket|null
     */
    public function store(CactusEntity|Ticket $entity): ?Ticket;

    /**
     * @param Ticket $entity
     * @param string $ticketId
     * @return bool
     */
    public function storeContacts(Ticket $entity, string $ticketId): bool;

    /**
     * @param CactusEntity|Ticket $entity
     * @param string $id
     * @return Ticket|null
     */
    public function update(CactusEntity|Ticket $entity, string $id): ?Ticket;

    /**
     * @param CactusEntity|TicketsStatusesPivot $entity
     * @param string $id
     * @return Ticket|null
     */
    public function updatePivotPosition(CactusEntity|TicketsStatusesPivot $entity, string $ticketId): ?Ticket;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableTickets(array $filters = []): JsonResponse;

    /**
     * @return array|null
     */
    public function getTableColumns(?ActionTypesEnum $type = null):?array;
}
