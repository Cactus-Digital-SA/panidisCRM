<?php

namespace App\Domains\Visits\Repositories\Eloquent;


use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Visits\Models\Visit;
use App\Domains\Visits\Models\VisitsStatusesPivot;
use App\Domains\Visits\Repositories\Eloquent\Models;
use App\Domains\Visits\Repositories\Eloquent\Models\VisitsStatusesPivot as EloquentVisitsStatusesPivot;
use App\Domains\Visits\Repositories\Eloquent\Models\VisitStatus;
use App\Domains\Visits\Repositories\VisitRepositoryInterface;
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

class EloqVisitRepository extends EloquentRelationHelper implements VisitRepositoryInterface
{

    /**
     * @param Models\Visit $model
     */
    public function __construct(
        protected readonly Models\Visit $model)
    {}

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        $visit = $this->model::all();

        return ObjectSerializer::deserialize($visit?->toJson() ?? "{}",  "array<". Visit::class . ">" , 'json');

    }

    /**
     * @inheritDoc
     */
    public function getByStatus(string $statusId): array
    {
        $visits = $this->model::join('visits_statuses', 'visits_statuses.visit_id', '=', 'visits.id')
            ->where('visits_statuses.visit_status_id', $statusId)
            ->whereRaw('visits_statuses.date = (SELECT MAX(date) FROM visits_statuses WHERE visit_id = visits.id)') // Get latest date
            ->with('company', 'assignees', 'status') // Eager load related models
            ->select('visits.*') // Select all columns from visits
            ->orderBy('visits_statuses.sort', 'asc') // Optionally, you can order by the pivot `sort` column if needed
            ->get();

        return ObjectSerializer::deserialize($visits?->toJson() ?? "{}",  "array<". Visit::class . ">" , 'json');
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id, bool $withRelations = true): ?Visit
    {
        $visit = $this->model::find($id);

        if($withRelations){
            $visit->load('owner','status','assignees','notes');
        }

        return ObjectSerializer::deserialize($visit?->toJson() ?? "{}",  Visit::class , 'json');
    }

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function searchPaginated(?string $searchTerm, int $offset, int $resultCount): array
    {
        $visits = $this->model
            ->select( 'visits.id', DB::raw('visits.name AS text'));

        if ($searchTerm != null) {
            $visits = $visits->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('id', 'like', '%' . $searchTerm . '%');
        }


        $visits = $visits->skip($offset)->take($resultCount)->get();


        if ($searchTerm == null) {
            $count = $this->model->count();
        } else {
            $count = $visits->count();
        }

        return array(
            "data" => $visits,
            "count" => $count
        );
    }

    /**
     * @inheritDoc
     */
    public function getByIdWithMorphs(string $modelId, array $morphs = []): ?Visit
    {
        $visit = $this->model::findOrFail($modelId);

        $visit = $this->modelLoadRelations($visit, $morphs);

        return ObjectSerializer::deserialize($visit?->toJson() ?? "{}",  Visit::class , 'json');
    }

    /**
     * @inheritDoc
     */
    public function getByIdWithMorphsAndRelations(string $modelId, array $morphs = [], array $relations = []): ?Visit
    {
        $visit = $this->model::findOrFail($modelId);

        $visit = $this->modelLoadRelations($visit, $morphs);
        $visit = $this->modelLoadRelations($visit, $relations);

        return ObjectSerializer::deserialize($visit?->toJson() ?? "{}",  Visit::class , 'json');
    }

    /**
     * @inheritDoc
     */
    public function store(CactusEntity|Visit $entity): ?Visit
    {
        $visit = $this->model::create([
            'name' => $entity->getName(),
            'deadline' => ($deadline = $entity->getDeadline()) ? $deadline->format('Y-m-d') : null,
            'priority' => $entity->getPriority()?->value ?? PriorityEnum::LOW->value,
            'company_id' => $entity->getCompanyId(),
            'owner_id' => $entity->getOwnerId() ?? \Auth::user()->id,
            'visit_type' => $entity->getVisitType()?->value ?? null,
            'visit_date' => $entity->getVisitDate()?->format('Y-m-d') ?? null,
            'products_discussed' => $entity->getProductsDiscussed()?->value ?? null,
            'next_action' => $entity->getNextAction()?->value ?? null,
            'outcome' => $entity->getOutcome() ?? null,
        ]);

        $maxSort = EloquentVisitsStatusesPivot::where('visit_status_id', $entity?->getActiveStatus()?->getId() ?? 1)->max('sort');

        $visit->status()->attach($entity?->getActiveStatus()?->getId() ?? 1, [
            'date' => now(),
            'sort' => $maxSort + 1,
        ]);

        //Active status attach.
        if($entity->getAssignees()) {
            $assignees = [];
            foreach ($entity->getAssignees() as $index => $assigneeId) {
                $assignees[$assigneeId] = [
                    'assigned_by' => auth()->id(),
                ];
            }
            $visit->assignees()->sync($assignees, false);
        }

        $users = $entity->getContacts();
        $visit->contacts()->syncWithoutDetaching($users);

        return ObjectSerializer::deserialize($visit?->toJson() ?? "{}",  Visit::class , 'json');
    }

    public function storeContacts(Visit $entity, string $visitId): bool
    {
        $visit = $this->model->find($visitId);

        if($visit){
            $users = $entity->getContacts();
            $visit->contacts()->syncWithoutDetaching($users);

            return true;
        }

        return false;
    }


    /**
     * @inheritDoc
     */
    public function update(CactusEntity|Visit $entity, string $id): ?Visit
    {
        $visit = $this->model::find($id);
        $oldVisit = $visit;

        $visit->update([
            'name' => $entity->getName(),
            'company_id' => $entity->getCompanyId(),
            'visit_type' => $entity->getVisitType()?->value,
            'visit_date' => $entity->getVisitDate()?->format('Y-m-d'),
            'products_discussed' => $entity->getProductsDiscussed()?->value,
            'next_action' => $entity->getNextAction()?->value,
            'outcome' => $entity->getOutcome(),
            'next_action_comment' => $entity->getNextActionComment(),
            ]);


        if($visit->owner_id == \Auth::user()->id || \Auth::user()->hasRole(RolesEnum::Administrator->value)) {
            $visit->update([
                'owner_id' => $entity->getOwnerId() ?? $visit->owner_id,
            ]);
        }

        $assignees =  $visit->assignees;
        $userId = \Auth::user()->id;

        $hasAccess = $assignees->contains(function ($assignee) use ($userId) {
            return $assignee->id === $userId;
        });

        if($visit->owner_id == \Auth::user()->id || $hasAccess || \Auth::user()->hasRole(RolesEnum::Administrator->value)){
            //Active status
            if($oldVisit->active_status->id != $entity->getActiveStatus()->getId()) {
                //Active status attach.
                $visit->status()->attach($entity->getActiveStatus()->getId(),['date' => now()]);
            }
        }

        $users = $entity->getContacts();
        $visit->contacts()->syncWithoutDetaching($users);

        return ObjectSerializer::deserialize($visit?->toJson() ?? "{}",  Visit::class , 'json');
    }

    public function updatePivotPositionAndStatus(CactusEntity|VisitsStatusesPivot $entity, string $visitId): ?Visit
    {
        $visit = $this->model::find($visitId);

        $statusSlug = $entity->getVisitStatusSlug();
        $newVisitStatusId = VisitStatus::where('slug', $statusSlug)->first()->id;

        $oldVisitStatusId = $visit->status()->first()->pivot->visit_status_id;

        $currentSortValue = $visit->status()
            ->where('visit_status_id', $oldVisitStatusId)
            ->first()->pivot->sort;

        $newSortValue = $entity->getSort() ?? 1;


        if($newVisitStatusId == $oldVisitStatusId) {
            // Έλεγχος αν το sorting έχει αλλάξει πρέπει να γίνει update στις τιμές των άλλων δεδομένων
            if ($newSortValue != $currentSortValue) {
                // Ενημέρωση της βάσης
                if ($newSortValue < $currentSortValue) {
                    // Αν η νέα ταξινόμηση είναι μικρότερη απο την αρχική
                    // αυξάνουμε τις τιμές προηγούμενων δεδομένων
                    DB::table('visits_statuses')
                        ->where('visit_status_id', $newVisitStatusId)
                        ->where('visit_id', '!=', $visitId)
                        ->whereBetween('sort', [$newSortValue, $currentSortValue - 1])
                        ->increment('sort');
                } else {
                    // Αν η νέα τιμή είναι μεγαλύτερη απο την αρχική
                    // πρέπει να μικρύνουμε τις τιμές των επόμενων δεδομένων
                    DB::table('visits_statuses')
                        ->where('visit_status_id', $newVisitStatusId)
                        ->where('visit_id', '!=', $visitId)
                        ->whereBetween('sort', [$currentSortValue + 1, $newSortValue])
                        ->decrement('sort');
                }

                // Update the pivot table
                $visit->status()->updateExistingPivot($newVisitStatusId, ['sort' => $newSortValue]);

            }
        }else{
            DB::table('visits_statuses')
                ->where('visit_status_id', $oldVisitStatusId)
                ->where('visit_id', '!=', $visitId)
                ->where('sort', '>', $currentSortValue)
                ->decrement('sort');

            DB::table('visits_statuses')
                ->where('visit_status_id', $newVisitStatusId)
                ->where('visit_id', '!=', $visitId)
                ->where('sort', '>=', $newSortValue)
                ->increment('sort');


            $visit->status()->attach($newVisitStatusId, ['date' => now(),'sort' => $newSortValue]);

        }


        return ObjectSerializer::deserialize($visit?->toJson() ?? "{}",  Visit::class , 'json');
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
    public function dataTableVisits(array $filters = []): JsonResponse
    {
        $visits = $this->model->with(['owner','company'])
            ->leftJoin('companies', 'companies.id', '=', 'visits.company_id')
            ->select('visits.*', 'companies.name as company_name');

        $visits = $visits
            ->when($filters['filterName'], function ($query,$searchTerm) {
                $query->where('name', 'LIKE', '%'.$searchTerm.'%');
            })
            ->when($filters['filterOwner'], function ($query,$searchTerm) {
                $query->where('owner_id', $searchTerm);
            })
            ->when($filters['filterDeadline'], function ($query,$searchTerm) {
                $dates[0] = Carbon::parse($searchTerm[0])->toDate();
                $dates[1] = Carbon::parse($searchTerm[1])->endOfDay();
                $query->whereBetween('deadline', [$dates[0], $dates[1]]);
            })
            ->when($filters['filterStartDate'], function ($query, $searchTerm) {
                $dates[0] = Carbon::parse($searchTerm[0])->toDate();
                $dates[1] = Carbon::parse($searchTerm[1])->endOfDay();
                $query->whereBetween('visits.created_at', [$dates[0], $dates[1]]);
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
            ->when($filters['assignedBy'], function ($query, $searchTerm) {
                $query->whereHas('assignees', function ($q) use ($searchTerm) {
                    $q->where('assigned_by', $searchTerm);
                });
            })
        ;

        if(isset($filters['morphableType']) && $filters['morphableType'] && $filters['morphableId']) {
            $visits = $visits->when($filters['morphableId'], function ($query, $searchTerm) use ($filters) {
                $query->whereHas($filters['morphableType'], function($q) use ($searchTerm, $filters) {
                    $q->where($filters['morphableType'].'.id', $searchTerm);
                });
            });
        }

        return DataTables::of($visits)
            ->editColumn('company', function ($visit){
                return  $visit->company_id ? '<a href="'. route('admin.companies.show',$visit->company_id).'" class="badge bg-label-primary">' . $visit->company_name . '</span>' : '-';
            })
            ->addColumn('date', function ($visit){
                return $visit->visit_date?->format('d-m-Y') ?? ' - ';
            })
            ->editColumn('visit_type', function ($visit){
                return $visit->visit_type?->value ?? ' - ';
            })
            ->editColumn('outcome', function ($visit){
                return $visit->outcome ?? ' - ';
            })
            ->editColumn('next_action', function ($visit){
                return $visit->next_action?->value ?? ' - ';
            })
            ->addColumn('actions', function ($visit) use ($filters) {
                if(\Auth::user()->hasRole(RolesEnum::Administrator->value)) {
                    $deleteUrl = route('admin.visits.destroy', $visit->id);
                }

                $html = '<div class="btn-group">';

                $html .= '<a href="' . route('admin.visits.show', [$visit->id]) . '" class="btn btn-icon btn-gradient-warning">
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
            ->rawColumns(['company','date','visit_type','outcome','next_action','actions'])
            ->toJson();
    }


    /**
     * @inheritDoc
     */
    public function getTableColumns(?ActionTypesEnum $type = null): ?array
    {
        return  [
            'id'=> ['name' => 'id', 'table' => 'visits.id', 'searchable' => 'false', 'orderable' => 'true'],

            'name' => ['name' => 'Name', 'table' => 'visits.name', 'searchable' => 'true', 'orderable' => 'true'],
            'company' => ['name' => 'Company', 'table' => 'companies.name', 'searchable' => 'false', 'orderable' => 'true'],
            'date' => ['name' => 'date', 'table' => 'visit_date', 'searchable' => 'false', 'orderable' => 'true'],
            'visit_type' => ['name' => 'Visit Type', 'table' => 'visit_type', 'searchable' => 'false', 'orderable' => 'true'],
            'outcome' => ['name' => 'Outcome', 'table' => 'outcome', 'searchable' => 'false', 'orderable' => 'true'],
            'next_action' => ['name' => 'Next Action', 'table' => 'next_action', 'searchable' => 'false', 'orderable' => 'true'],
        ];
    }
}
