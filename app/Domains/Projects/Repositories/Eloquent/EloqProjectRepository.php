<?php

namespace App\Domains\Projects\Repositories\Eloquent;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Companies\Repositories\Eloquent\Models\Company;
use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Repositories\ProjectRepositoryInterface;
use App\Domains\Tickets\Repositories\Eloquent\Models\TicketStatus;
use App\Facades\ObjectSerializer;
use App\Helpers\EloquentRelationHelper;
use App\Models\CactusEntity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use function PHPUnit\Framework\isArray;

class EloqProjectRepository extends EloquentRelationHelper implements ProjectRepositoryInterface
{

    /**
     * @param Models\Project $model
     */
    public function __construct(
        protected readonly Models\Project $model)
    {}

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        $project = $this->model::all();

        return ObjectSerializer::deserialize($project?->toJson() ?? "{}",  "array<". Project::class . ">" , 'json');

    }

    /**
     * @inheritDoc
     */
    public function getById(string $id, bool $withRelations = true): ?Project
    {
        $project = $this->model::find($id);

        if($withRelations){
            $project->load('type','owner','createdByUser','status','assignees','client.company','company');
        }

        return ObjectSerializer::deserialize($project?->toJson() ?? "{}",  Project::class , 'json');
    }


    /**
     * @inheritDoc
     */
    public function getByIdWithMorphs(string $modelId, array $morphs = []): ?Project
    {
        $project = $this->model::findOrFail($modelId);

        $project = $this->modelLoadRelations($project, $morphs);

        return ObjectSerializer::deserialize($project?->toJson() ?? "{}",  Project::class , 'json');
    }

    /**
     * @inheritDoc
     */
    public function getByIdWithMorphsAndRelations(string $modelId, array $morphs = [], array $relations = []): ?Project
    {
        $project = $this->model::findOrFail($modelId);

        $project = $this->modelLoadRelations($project, $morphs);
        $project = $this->modelLoadRelations($project, $relations);

        return ObjectSerializer::deserialize($project?->toJson() ?? "{}",  Project::class , 'json');
    }

    /**
     * @inheritDoc
     */
    public function store(CactusEntity|Project $entity): ?Project
    {
        $company = Company::find($entity->getCompanyId());
        if($company->client ?? null){
            $entity->setClientId($company->client->id);
        }

        $project = $this->model::create([
            'name' => $entity->getName(),
            'description' =>  $entity->getDescription(),
            'start_date' => ($startDate = $entity->getStartDate()) ? $startDate->format('Y-m-d') : null,
            'deadline' => ($deadline = $entity->getDeadline()) ? $deadline->format('Y-m-d') : null,
            'sales_cost' => $entity->getSalesCost(),
            'google_drive' => $entity->getGoogleDrive(),
            'est_time' => $entity->getEstTime(),
            'est_date' => ($estDate = $entity->getEstDate()) ? $estDate->format('Y-m-d') : null,
            'priority' => $entity->getPriority(),
            'type_id' => $entity->getTypeId(),
            'client_id' => $entity->getClientId(),
            'company_id' => $entity->getCompanyId(),
            'owner_id' => $entity->getOwnerId(),
            'created_by' => $entity->getCreatedBy(),
            'category' => $entity->getCategory() ?? null,
            'category_status' => $entity->getCategoryStatus() ?? null
        ]);

        //Active status attach.
        $project->status()->attach($entity->getActiveStatus()->getId(),['date' => now()]);


        //Assignees
//        if($entity->getAssignees()) {
//            foreach ($entity->getAssignees() as $assignee) {
//                $project->assignees()->sync($assignee->getId(), false);
//            }
//        }

        if ($entity->getAssignees()) {
            $assignees = [];

            foreach ($entity->getAssignees() as $assignee) {
                $assignees[$assignee->getId()] = [
                    'assigned_by' => auth()->id(),
                    // 'sort' => optional
                ];
            }

            $project->assignees()->syncWithoutDetaching($assignees);
        }

        return ObjectSerializer::deserialize($project?->toJson() ?? "{}",  Project::class , 'json');
    }

    public function assignTicket(string $projectId, string $ticketId): ?Project
    {
        $project = $this->model::findOrFail($projectId);
        $project->tickets()->attach($ticketId);

        return ObjectSerializer::deserialize($project?->toJson() ?? "{}",  Project::class , 'json');
    }

    /**
     * @inheritDoc
     */
    public function update(CactusEntity|Project $entity, string $id): ?Project
    {
        $project = $this->model::find($id);
        $oldProject = $project;

        $company = Company::find($entity->getCompanyId());
        if($company->client ?? null){
            $entity->setClientId($company->client->id);
        }

        $project->update([
            'name' => $entity->getName(),
            'description' =>  $entity->getDescription(),
            'start_date' => ($startDate = $entity->getStartDate()) ? $startDate->format('Y-m-d') : null,
            'deadline' => ($deadline = $entity->getDeadline()) ? $deadline->format('Y-m-d') : null,
            'sales_cost' => $entity->getSalesCost(),
            'google_drive' => $entity->getGoogleDrive(),
            'est_time' => $entity->getEstTime(),
            'est_date' => ($estDate = $entity->getEstDate()) ? $estDate->format('Y-m-d') : null,
            'priority' => $entity->getPriority(),
            'client_id' => $entity->getClientId(),
            'company_id' => $entity->getCompanyId(),
            'category' => $entity->getCategory() ?? null,
            'category_status' => $entity->getCategoryStatus() ?? null
        ]);

        if($project->owner_id == \Auth::user()->id || \Auth::user()->hasRole(RolesEnum::Administrator->value)){
            $project->update([
                'owner_id' => $entity->getOwnerId(),
            ]);

            // Active status
            if($oldProject->active_status->id != $entity->getActiveStatus()->getId()) {
                // Active status attach.
                $project->status()->attach($entity->getActiveStatus()->getId(), ['date' => now()]);

                // Έλεγχος για ακύρωση
                if ($entity->getActiveStatus()->getSlug() === 'cancelled') {
                    $ticketCancelledStatusId = TicketStatus::where('slug', 'cancelled')->first()?->id;

                    if ($ticketCancelledStatusId) {
                        foreach ($project->tickets as $ticket) {

                            $alreadyCancelled = $ticket->status()
                                ->wherePivot('date', '<=', now())
                                ->where('tickets_statuses.id', $ticketCancelledStatusId)
                                ->exists();

                            if (!$alreadyCancelled) {
                                $ticket->status()->attach($ticketCancelledStatusId, ['date' => now()]);
                            }
                        }
                    }
                }
            }

        }

        //Assignees
//        if($entity->getAssignees()) {
//            foreach ($entity->getAssignees() as $assignee) {
//                $project->assignees()->sync($assignee->getId(), false);
//            }
//        }

        if (!is_null($entity->getAssignees()) && isArray($entity->getAssignees())) {
            $assignees = [];

            foreach ($entity->getAssignees() as $assignee) {
                $assignees[$assignee->getId()] = [
                    'assigned_by' => auth()->id(),
                    // 'sort' => optional
                ];
            }

            $project->assignees()->sync($assignees);
        }


        return ObjectSerializer::deserialize($project?->toJson() ?? "{}",  Project::class , 'json');
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
    public function dataTableProjects(array $filters = []): JsonResponse
    {
        $projects = $this->model->with(['owner','company'])->select('projects.*');

        $projects = $projects
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
            ->when($filters['filterStartDate'], function ($query,$searchTerm) {
                $dates[0] = Carbon::parse($searchTerm[0])->toDate();
                $dates[1] = Carbon::parse($searchTerm[1])->toDate();
                $query->whereBetween('start_date', [$dates[0], $dates[1]]);
            })
            ->when($filters['filterPriority'], function ($query,$searchTerm) {
                $query->where('priority', $searchTerm);
            })
            ->when($filters['filterStatus'], function ($query,$searchTerm) {
                $query->whereHas('status', function($q) use ($searchTerm) {
                    $q->whereIn('project_status.id', $searchTerm)
                        ->whereRaw('projects_statuses.date = (SELECT MAX(date) FROM projects_statuses WHERE projects_statuses.project_id = projects.id)');
                });
            })
            ->when($filters['filterAssignees'], function ($query,$searchTerm) {
                $query->whereHas('assignees', function($q) use ($searchTerm) {
                    $q->whereIn('users.id', $searchTerm);
                });
            })
            ->when($filters['filterClient'], function ($query,$searchTerm) {
                $query->where('client_id', $searchTerm);
            })
            ->when($filters['filterCompany'], function ($query,$searchTerm) {
                $query->where('company_id', $searchTerm);
            })

            ->when($filters['projectTypeId'], function ($query,$searchTerm) {
                $query->where('type_id', $searchTerm);
            })
            ->when($filters['projectMine'], function ($query,$searchTerm) {
                $query->where(function ($q) use ($searchTerm){
                    $q->where('owner_id', $searchTerm)
                    ->orWhereHas('assignees', function($k) use ($searchTerm) {
                        $k->where('users.id', $searchTerm);
                    });
                });
            });


        if (!isset($filters['columnName']) || !isset($filters['columnSortOrder'])) {
            $projects = $projects
                ->orderBy('projects.deadline', 'asc')
                ->orderBy('projects.priority', 'desc');
        }



        return DataTables::of($projects)
            ->editColumn('start_date', function ($project) {
                return $project->start_date ? Carbon::parse($project->start_date)->format('d-m-Y'): '-';
            })
            ->editColumn('deadline', function ($project) {
                return $project->deadline ? Carbon::parse($project->deadline)->format('d-m-Y'): '-';
            })
            ->editColumn('est_date', function ($project) {
                return $project->est_date ? Carbon::parse($project->est_date)->format('d-m-Y') : '-';
            })
            ->editColumn('owner', function ($project){
                return  '<a href="'. route('admin.users.show',$project->owner_id).'" class="badge bg-label-dark">' . $project->owner->name . '</span>';
            })
            ->editColumn('client', function ($project){
                if($project->client){
                    return  '<a href="'. route('admin.clients.show',$project->client_id).'" class="badge bg-label-primary">' . $project->client->company->name . '</span>';
                }
                if($project->company_id){
                    if($project->company?->client){
                        return  '<a href="'. route('admin.clients.show',$project->company->client->id).'" class="badge bg-label-primary">' . $project->client->company->name . '</span>';
                    }
                    if($project->company?->lead){
                        return  '<a href="'. route('admin.leads.show',$project->company->lead->id).'" class="badge bg-label-primary">' . $project->company->name . '</span>';
                    }
                }
                return '-';
            })
            ->editColumn('assignees', function ($project){
                $html ='';
                foreach ($project->assignees as $assignee){
                    $html .=  '<a href="'. route('admin.users.show',$assignee->id).'" class="badge bg-label-secondary">' . $assignee->name . '</span>';
                }
                return $html;
            })
            ->editColumn('priority', function ($project){
                return  '<span class="badge '. $project->priority->getLabelClass() .'">' . $project->priority->value . '</span>';
            })
            ->editColumn('est_time_array', function ($project){
                return  '<span class="badge bg-label-info">' . $project->est_time_array['human'] . '</span>';
            })
            ->editColumn('active_status', function ($project){
                return  '<span class="badge bg-label-'. $project->active_status['label'] . '">' . $project->active_status['name'] . '</span>';
            })
            ->editColumn('google_drive', function ($project){
                return  '<a href="' . $project->google_drive . '" >' . $project->google_drive . '</a>';
            })
            ->addColumn('actions', function ($project) use ($filters) {
                if(\Auth::user()->hasRole(RolesEnum::Administrator->value)){
                    $deleteUrl = route('admin.projects.destroy', $project->id);
                }
                $html = '<div class="btn-group">';

                $html .= '<a href="' . route('admin.projects.show', [$filters['projectTypeSlug'],$project->id]) . '" class="btn btn-icon btn-gradient-warning">
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
            ->rawColumns(['actions','priority','est_time_array','active_status','owner','assignees','client','google_drive'])
            ->toJson();
    }


    /**
     * @inheritDoc
     */
    public function getTableColumns(): ?array
    {
        return  [
            'id'=> ['name' => 'id', 'table' => 'projects.id', 'searchable' => 'false', 'orderable' => 'true'],

            'name' => ['name' => 'Name', 'table' => 'projects.name', 'searchable' => 'true', 'orderable' => 'true'],
            'description' => ['name' => 'Description', 'table' => 'projects.name', 'searchable' => 'true', 'orderable' => 'true'],
            'client' => ['name' => 'Client', 'table' => 'companies.name', 'searchable' => 'true', 'orderable' => 'false'],
            'start_date' =>  ['name' => 'Start Date', 'table' => 'projects.start_date', 'searchable' => 'true', 'orderable' => 'true'],
            'deadline' => ['name' => 'Deadline', 'table' => 'projects.deadline', 'searchable' => 'true', 'orderable' => 'true'],
            'priority' => ['name' => 'Priority', 'table' => 'projects.priority', 'searchable' => 'true', 'orderable' => 'true'],
            'active_status' => ['name' => 'Status', 'table' => '', 'searchable' => 'false', 'orderable' => 'false'],
            'sales_cost' => ['name' => 'Sales Cost', 'table' => 'projects.sales_cost', 'searchable' => 'true', 'orderable' => 'true'],
//            'est_time_array' => ['name' => 'Estimation Time', 'table' => 'projects.est_time', 'searchable' => 'true', 'orderable' => 'true'],
            'owner' => ['name' => 'Manager', 'table' => '', 'searchable' => 'true', 'orderable' => 'false'],
            'assignees' => ['name' => 'Assignees', 'table' => '', 'searchable' => 'true', 'orderable' => 'false'],
//            'google_drive' => ['name' => 'Google Drive', 'table' => 'projects.google_drive', 'searchable' => 'true', 'orderable' => 'false'],
//            'est_date' =>  ['name' => 'Estimation Date', 'table' => 'projects.est_date', 'searchable' => 'true', 'orderable' => 'true'],
        ];

        //{ data: 'sales_cost' , name: 'projects.sales_cost'},
        //                            { data: 'est_time.human' , name:'projects.est_time',  searchable : false, sortable:true},
        //                            { data: 'owner.name'},
    }
}
