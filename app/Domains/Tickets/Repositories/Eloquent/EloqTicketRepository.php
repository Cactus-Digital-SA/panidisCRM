<?php

namespace App\Domains\Tickets\Repositories\Eloquent;


use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Tickets\Enums\TicketSourceEnum;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Tickets\Models\TicketsStatusesPivot;
use App\Domains\Tickets\Repositories\Eloquent\Models\TicketsStatusesPivot as EloquentTicketsStatusesPivot;
use App\Domains\Tickets\Repositories\Eloquent\Models\TicketStatus;
use App\Domains\Tickets\Repositories\TicketRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Helpers\EloquentRelationHelper;
use App\Helpers\Enums\ActionTypesEnum;
use App\Helpers\Enums\PriorityEnum;
use App\Models\CactusEntity;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class EloqTicketRepository extends EloquentRelationHelper implements TicketRepositoryInterface
{

    /**
     * @param Models\Ticket $model
     */
    public function __construct(
        protected readonly Models\Ticket $model)
    {}

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        $ticket = $this->model::all();

        return ObjectSerializer::deserialize($ticket?->toJson() ?? "{}",  "array<". Ticket::class . ">" , 'json');

    }

    /**
     * @inheritDoc
     */
    public function getByStatus(string $statusId): array
    {
        $tickets = $this->model::join('tickets_statuses', 'tickets_statuses.ticket_id', '=', 'tickets.id')
            ->where('tickets_statuses.ticket_status_id', $statusId)
            ->whereRaw('tickets_statuses.date = (SELECT MAX(date) FROM tickets_statuses WHERE ticket_id = tickets.id)') // Get latest date
            ->with('company', 'assignees', 'status') // Eager load related models
            ->select('tickets.*') // Select all columns from tickets
            ->orderBy('tickets_statuses.sort', 'asc') // Optionally, you can order by the pivot `sort` column if needed
            ->get();

        return ObjectSerializer::deserialize($tickets?->toJson() ?? "{}",  "array<". Ticket::class . ">" , 'json');
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id, bool $withRelations = true): ?Ticket
    {
        $ticket = $this->model::find($id);

        if($withRelations){
            $ticket->load('owner','status','assignees','notes');
        }

        return ObjectSerializer::deserialize($ticket?->toJson() ?? "{}",  Ticket::class , 'json');
    }

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function searchPaginated(?string $searchTerm, int $offset, int $resultCount): array
    {
        $tickets = $this->model
            ->select( 'tickets.id', DB::raw('tickets.name AS text'));

        if ($searchTerm != null) {
            $tickets = $tickets->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('id', 'like', '%' . $searchTerm . '%');
        }


        $tickets = $tickets->skip($offset)->take($resultCount)->get();


        if ($searchTerm == null) {
            $count = $this->model->count();
        } else {
            $count = $tickets->count();
        }

        return array(
            "data" => $tickets,
            "count" => $count
        );
    }

    /**
     * @inheritDoc
     */
    public function getByIdWithMorphs(string $modelId, array $morphs = []): ?Ticket
    {
        $ticket = $this->model::findOrFail($modelId);

        $ticket = $this->modelLoadRelations($ticket, $morphs);

        return ObjectSerializer::deserialize($ticket?->toJson() ?? "{}",  Ticket::class , 'json');
    }

    /**
     * @inheritDoc
     */
    public function getByIdWithMorphsAndRelations(string $modelId, array $morphs = [], array $relations = []): ?Ticket
    {
        $ticket = $this->model::findOrFail($modelId);

        $ticket = $this->modelLoadRelations($ticket, $morphs);
        $ticket = $this->modelLoadRelations($ticket, $relations);

        return ObjectSerializer::deserialize($ticket?->toJson() ?? "{}",  Ticket::class , 'json');
    }

    /**
     * @inheritDoc
     */
    public function store(CactusEntity|Ticket $entity): ?Ticket
    {

        $ticket = $this->model::create([
            'name' => $entity->getName(),
            'deadline' => ($deadline = $entity->getDeadline()) ? $deadline->format('Y-m-d') : null,
            'billable' => $entity->getBillable() ?? false,
            'public' => $entity->getPublic() ?? false,
            'est_time' => $entity->getEstTime(),
            'priority' => $entity->getPriority()?->value ?? PriorityEnum::LOW->value,
            'source' => $entity->getSource() ?? TicketSourceEnum::SYSTEM->value,
            'company_id' => $entity->getCompanyId(),
            'owner_id' => $entity->getOwnerId() ?? \Auth::user()->id,
        ]);

        $maxSort = EloquentTicketsStatusesPivot::where('ticket_status_id', $entity?->getActiveStatus()?->getId() ?? 1)->max('sort');

        $ticket->status()->attach($entity?->getActiveStatus()?->getId() ?? 1, [
            'date' => now(),
            'sort' => $maxSort + 1,
        ]);

        //Active status attach.
//        $ticket->status()->attach($entity->getActiveStatus()->getId(),['date' => now()]);

        if($entity->getAssignees()) {
            $assignees = [];
            foreach ($entity->getAssignees() as $index => $assigneeId) {
                $assignees[$assigneeId] = [
                    'assigned_by' => auth()->id(),
                ];
            }

            $ticket->assignees()->sync($assignees, false);
        }

        // Blocked By
        if (!is_null($entity->getBlockedByTickets())) {
            $ticket->blockedBy()->sync($entity->getBlockedByTickets());
        }

        return ObjectSerializer::deserialize($ticket?->toJson() ?? "{}",  Ticket::class , 'json');
    }

    public function storeContacts(Ticket $entity, string $ticketId): bool
    {
        $ticket = $this->model->find($ticketId);

        if($ticket){
            $users = $entity->getContacts();
            $ticket->contacts()->syncWithoutDetaching($users);

            return true;
        }

        return false;
    }


    /**
     * @inheritDoc
     */
    public function update(CactusEntity|Ticket $entity, string $id): ?Ticket
    {
        $ticket = $this->model::find($id);
        $oldTicket = $ticket;

        $ticket->update([
            'name' => $entity->getName(),
            'deadline' => ($deadline = $entity->getDeadline()) ? $deadline->format('Y-m-d') : null,
            'billable' => $entity->getBillable(),
            'public' => $entity->getPublic(),
            'est_time' => $entity->getEstTime(),
            'priority' => $entity->getPriority(),
            'source' => $entity->getSource(),
            'company_id' => $entity->getCompanyId(),
        ]);

        if($ticket->owner_id == \Auth::user()->id || \Auth::user()->hasRole(RolesEnum::Administrator->value)) {
            $ticket->update([
                'owner_id' => $entity->getOwnerId() ?? $ticket->owner_id,
            ]);
        }

        $assignees =  $ticket->assignees;
        $userId = \Auth::user()->id;

        $hasAccess = $assignees->contains(function ($assignee) use ($userId) {
            return $assignee->id === $userId;
        });

        if($ticket->owner_id == \Auth::user()->id || $hasAccess || \Auth::user()->hasRole(RolesEnum::Administrator->value)){
            //Active status
            if($oldTicket->active_status->id != $entity->getActiveStatus()->getId()) {
                //Active status attach.
                $ticket->status()->attach($entity->getActiveStatus()->getId(),['date' => now()]);
            }

        }

        // Blocked By
        if (!is_null($entity->getBlockedByTickets())) {
            $ticket->blockedBy()->sync($entity->getBlockedByTickets());
        }


        return ObjectSerializer::deserialize($ticket?->toJson() ?? "{}",  Ticket::class , 'json');
    }

    public function updatePivotPosition(CactusEntity|TicketsStatusesPivot $entity, string $ticketId): ?Ticket
    {
        $ticket = $this->model::find($ticketId);

        $statusSlug = $entity->getTicketStatusSlug();
        $newTicketStatusId = TicketStatus::where('slug', $statusSlug)->first()->id;

        $oldTicketStatusId = $ticket->status()->first()->pivot->ticket_status_id;

        $currentSortValue = $ticket->status()
            ->where('ticket_status_id', $oldTicketStatusId)
            ->first()->pivot->sort;

        $newSortValue = $entity->getSort() ?? 1;


        if($newTicketStatusId == $oldTicketStatusId) {
            // Έλεγχος αν το sorting έχει αλλάξει πρέπει να γίνει update στις τιμές των άλλων δεδομένων
            if ($newSortValue != $currentSortValue) {
                // Ενημέρωση της βάσης
                if ($newSortValue < $currentSortValue) {
                    // Αν η νέα ταξινόμηση είναι μικρότερη απο την αρχική
                    // αυξάνουμε τις τιμές προηγούμενων δεδομένων
                    DB::table('tickets_statuses')
                        ->where('ticket_status_id', $newTicketStatusId)
                        ->where('ticket_id', '!=', $ticketId)
                        ->whereBetween('sort', [$newSortValue, $currentSortValue - 1])
                        ->increment('sort');
                } else {
                    // Αν η νέα τιμή είναι μεγαλύτερη απο την αρχική
                    // πρέπει να μικρύνουμε τις τιμές των επόμενων δεδομένων
                    DB::table('tickets_statuses')
                        ->where('ticket_status_id', $newTicketStatusId)
                        ->where('ticket_id', '!=', $ticketId)
                        ->whereBetween('sort', [$currentSortValue + 1, $newSortValue])
                        ->decrement('sort');
                }

                // Update the pivot table
                $ticket->status()->updateExistingPivot($newTicketStatusId, ['sort' => $newSortValue]);

            }
        }else{
            DB::table('tickets_statuses')
                ->where('ticket_status_id', $oldTicketStatusId)
                ->where('ticket_id', '!=', $ticketId)
                ->where('sort', '>', $currentSortValue)
                ->decrement('sort');

            DB::table('tickets_statuses')
                ->where('ticket_status_id', $newTicketStatusId)
                ->where('ticket_id', '!=', $ticketId)
                ->where('sort', '>=', $newSortValue)
                ->increment('sort');


            $ticket->status()->attach($newTicketStatusId, ['date' => now(),'sort' => $newSortValue]);

        }


        return ObjectSerializer::deserialize($ticket?->toJson() ?? "{}",  Ticket::class , 'json');
    }

    /**
     * @inheritDoc
     */
    public function deleteById(string $id): bool
    {
        if(\Auth::user()->hasRole(RolesEnum::Administrator->value)) {
            return $this->model::find($id)->delete();
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function dataTableTickets(array $filters = []): JsonResponse
    {
        $tickets = $this->model->with('owner')->select('tickets.*');

        $tickets = $tickets
            ->when($filters['filterName'], function ($query,$searchTerm) {
                $query->where('name', 'LIKE', '%'.$searchTerm.'%');
            })
            ->when($filters['filterOwner'], function ($query,$searchTerm) {
                $query->where('owner_id', $searchTerm);
            })
            ->when($filters['filterDeadline'], function ($query,$searchTerm) {
                $dates[0] = Carbon::parse($searchTerm[0])->toDate();
                $dates[1] = Carbon::parse($searchTerm[1])->toDate();
                $query->whereBetween('deadline', [$dates[0], $dates[1]]);
            })
            ->when($filters['filterPriority'], function ($query,$searchTerm) {
                $query->where('priority', $searchTerm);
            })
            ->when($filters['filterStatus'], function ($query,$searchTerm) {
                $query->whereHas('status', function($q) use ($searchTerm) {
                    $q->whereIn('ticket_status.id', $searchTerm)
                        ->whereRaw('tickets_statuses.date = (SELECT MAX(date) FROM tickets_statuses WHERE tickets_statuses.ticket_id = tickets.id)');
                });
            })
            ->when($filters['filterAssignees'], function ($query,$searchTerm) {
                $query->whereHas('assignees', function($q) use ($searchTerm) {
                    $q->whereIn('users.id', $searchTerm);
                });
            })
            ->when($filters['filterCompany'], function ($query,$searchTerm) {
                $query->where('company_id', $searchTerm);
            })
            ->when($filters['ticketMine'], function ($query,$searchTerm) {
                $query->where(function ($q) use ($searchTerm){
                    $q->where('owner_id', $searchTerm)
                    ->orWhereHas('assignees', function($k) use ($searchTerm) {
                        $k->where('users.id', $searchTerm);
                    });
                });
            })
            ->when($filters['assignedBy'], function ($query, $searchTerm) {
                $query->whereHas('assignees', function ($q) use ($searchTerm) {
                    $q->where('assigned_by', $searchTerm);
                });
            });

        if(isset($filters['morphableType']) && $filters['morphableType'] && $filters['morphableId']) {
            $tickets = $tickets->when($filters['morphableId'], function ($query, $searchTerm) use ($filters) {
                $query->whereHas($filters['morphableType'], function($q) use ($searchTerm, $filters) {
                    $q->where($filters['morphableType'].'.id', $searchTerm);
                });
            });
        }

        if ($filters['columnName'] && $filters['columnSortOrder']) {
            $tickets = $tickets->orderBy($filters['columnName'], $filters['columnSortOrder']);
        }

        return DataTables::of($tickets)
            ->editColumn('deadline', function ($ticket) {
                return $ticket->deadline ? Carbon::parse($ticket->deadline)->format('d-m-Y'): '-';
            })
            ->editColumn('owner', function ($ticket){
                return  '<a href="'. route('admin.users.show',$ticket->owner_id).'" class="badge bg-label-dark">' . $ticket->owner->name . '</span>';
            })
            ->editColumn('billable', function ($ticket){
                return  '<span class="badge bg-label-'. ($ticket->billable ? 'success' : 'danger') . '">' . ($ticket->billable ? __('Yes') : __('No') ). '</span>';
            })
            ->editColumn('public', function ($ticket){
                return  '<span class="badge bg-label-'. ($ticket->public ? 'success' : 'danger') . '">' . ($ticket->public ? __('Yes') : __('No') ). '</span>';
            })
            ->editColumn('company', function ($ticket){
                return  $ticket->company_id ? '<a href="'. route('admin.companies.show',$ticket->company_id).'" class="badge bg-label-primary">' . $ticket->company->name . '</span>' : '-';
            })
            ->editColumn('assignees', function ($ticket){
                $html ='';
                foreach ($ticket->assignees as $assignee){
                    $html .=  '<a href="'. route('admin.users.show',$assignee->id).'" class="badge bg-label-secondary">' . $assignee->name . '</span>';
                }
                return $html;
            })
            ->editColumn('priority', function ($ticket){
                return  '<span class="badge '. $ticket->priority?->getLabelClass() .'">' . $ticket->priority?->value . '</span>';
            })
            ->editColumn('est_time_array', function ($ticket){
                return  '<span class="badge bg-label-info">' . $ticket->est_time_array['human'] . '</span>';
            })
            ->editColumn('active_status', function ($ticket){
                return  '<span class="badge bg-label-'. $ticket->active_status['label'] . '">' . $ticket->active_status['name'] . '</span>';
            })
            ->addColumn('actions', function ($ticket) use ($filters) {
                if(\Auth::user()->hasRole(RolesEnum::Administrator->value)) {
                    $deleteUrl = route('admin.tickets.destroy', $ticket->id);
                }

                $html = '<div class="btn-group">';

                $html .= '<a href="' . route('admin.tickets.show', [$ticket->id]) . '" class="btn btn-icon btn-gradient-warning">
                             <i class="ti ti-eye ti-xs"></i>
                        </a>';

                if(isset($deleteUrl)){
                $html .= '<a href="#" class="btn btn-icon btn-gradient-danger"
                           data-bs-toggle="modal" data-bs-target="#deleteModal"
                           onclick="deleteForm(\'' . $deleteUrl . '\')">
                            <i class="ti ti-trash ti-xs"></i>
                       </a>';
                }

                $html .= '</div>';
                return $html;
            })
            ->makeHidden(['created_at', 'updated_at', 'deleted_at'])
            ->rawColumns(['actions','billable', 'public' ,'priority','est_time_array','active_status','owner','assignees','company'])
            ->toJson();
    }

    /**
     * @inheritDoc
     */
    public function getTableColumns(?ActionTypesEnum $type = null): ?array
    {
        return  [
            'id'=> ['name' => 'id', 'table' => 'tickets.id', 'searchable' => 'false', 'orderable' => 'true'],

            'name' => ['name' => 'Name', 'table' => 'tickets.name', 'searchable' => 'true', 'orderable' => 'true'],
            'company' => ['name' => 'Company', 'table' => '', 'searchable' => 'true', 'orderable' => 'true'],
            'billable' =>  ['name' => 'Billable', 'table' => 'tickets.billable', 'searchable' => 'false', 'orderable' => 'true'],
            'deadline' => ['name' => 'Deadline', 'table' => 'tickets.deadline', 'searchable' => 'true', 'orderable' => 'true'],
            'active_status' => ['name' => 'Status', 'table' => '', 'searchable' => '', 'orderable' => ''],
            'public' => ['name' => 'Public', 'table' => 'tickets.public', 'searchable' => 'true', 'orderable' => 'true'],
            'est_time_array' => ['name' => 'Estimation Time', 'table' => 'tickets.est_time', 'searchable' => 'true', 'orderable' => 'true'],
            'owner' => ['name' => 'Manager', 'table' => '', 'searchable' => 'true', 'orderable' => 'true'],
            'assignees' => ['name' => 'Assignees', 'table' => '', 'searchable' => 'true', 'orderable' => 'true'],
            'source' => ['name' => 'Source', 'table' => 'tickets.source', 'searchable' => 'true', 'orderable' => 'true'],
            'priority' => ['name' => 'Priority', 'table' => 'tickets.priority', 'searchable' => 'true', 'orderable' => 'true'],
        ];
    }
}
