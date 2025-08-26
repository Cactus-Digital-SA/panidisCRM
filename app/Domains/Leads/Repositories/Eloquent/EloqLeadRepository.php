<?php

namespace App\Domains\Leads\Repositories\Eloquent;

use App\Domains\Leads\Models\Lead;
use App\Domains\Leads\Repositories\Eloquent\Models\Lead as EloquentLead;
use App\Domains\Leads\Repositories\LeadRepositoryInterface;
use App\Domains\Tags\Repositories\Eloquent\Models\Tag;
use App\Facades\ObjectSerializer;
use App\Helpers\EloquentRelationHelper;
use App\Models\CactusEntity;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class EloqLeadRepository  extends EloquentRelationHelper implements LeadRepositoryInterface
{
    private EloquentLead $model;

    /**
     *  @param EloquentLead $lead
     *  @return void
    */
    public function __construct(EloquentLead $lead)
    {
        $this->model = $lead;
    }

    /**
     * @inheritDoc
     *
     */
    public function get(): array
    {
        $leads = $this->model->all();
        return ObjectSerializer::deserialize($leads?->toJson() ?? "{}", 'array<' . Lead::class . '>', 'json');
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id, bool $withRelations = true): ?Lead
    {
        $lead = $this->model;

        if($withRelations) {
            $lead = $lead->with(['company','company.doy','company.companyType','extraData','sectionData.section','sectionData.option','sectionData.subSection'])->find($id);
        }
        // $lead = $lead->find($id);

        return ObjectSerializer::deserialize($lead?->toJson() ?? "{}",  Lead::class, 'json');
    }


    /**
     * @inheritDoc
     */
    public function getByIdWithMorphsAndRelations(string $modelId, array $morphs = [], array $relations = []): ?Lead
    {
        $lead = $this->model::findOrFail($modelId);

        $lead = $this->modelLoadRelations($lead, $morphs);
        $lead = $this->modelLoadRelations($lead, $relations);

        return ObjectSerializer::deserialize($lead?->toJson() ?? "{}",  Lead::class , 'json');
    }

    /**
     * @inheritDoc
     */
    public function store(CactusEntity|Lead $entity): ?Lead
    {
        $lead = $this->model::create([
            'company_id' => $entity->getCompanyId(),
            'sales_person_id' => $entity->getSalesPersonId(),
        ]);

        return ObjectSerializer::deserialize($lead?->toJson() ?? "{}", Lead::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function update(CactusEntity|Lead $entity, string $id): ?Lead
    {
        $lead = $this->model->findOrFail($id);

        $lead->update([
            'company_id' => $entity->getCompanyId(),
            'sales_person_id' => $entity->getSalesPersonId(),
        ]);


        return ObjectSerializer::deserialize($lead->toJson() ?? "{}", Lead::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function updateStatus(CactusEntity|Lead $entity, string $id): ?Lead
    {
        $lead = $this->model->findOrFail($id);

        $lead->update([
            'status_id' => $entity->getStatusId(),
        ]);

        return ObjectSerializer::deserialize($lead->toJson() ?? "{}", Lead::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function deleteById(string $id): bool
    {
        $lead = $this->model->findOrFail($id);

        return (bool)$lead?->delete();
    }

    /**
     * @inheritDoc
     */
    public function dataTableLeads(array $filters = []): JsonResponse
    {
        $leads = $this->model->query()->with(['company', 'sectionData.section','sectionData.option','sectionData.subSection','sectionData.tags']);

        $leads = $leads
            ->when($filters['filterName'], function ($query,$searchTerm) {
                $query->whereHas('company', function($q) use ($searchTerm) {
                    $q->where('companies.name', 'LIKE', '%'.$searchTerm.'%');
                });
            })
            ->when($filters['filterStatus'] ?? false,function ($query, $searchTerm) {
                $query->where('status_id', $searchTerm);
            });


        if ($filters['columnName'] && $filters['columnSortOrder']) {
            $leads = $leads->orderBy($filters['columnName'], $filters['columnSortOrder']);
        }

        return DataTables::of($leads)
            ->editColumn('company', function ($lead) {
                return $lead?->company?->name;
            })
            ->editColumn('probability', function ($lead) {
                return $lead?->probability. '%' ;
            })
            ->editColumn('has_budget', function ($lead) {
                if($lead->has_budget)
                {
                    return '<i class="ti ti-check ti-xs text-success"></i>';
                }
                return '<i class="ti ti-x ti-xs text-danger"></i>';
            })
//            ->editColumn('decision_maker', function ($lead) {
//                if($lead->decision_maker)
//                {
//                    return '<i class="ti ti-check ti-xs text-success"></i>';
//                }
//                return '<i class="ti ti-x ti-xs text-danger"></i>';
//            })
            ->editColumn('est_closing_date', function ($lead) {
                if($lead->est_closing_date)
                {
                    return date('d-m-Y', strtotime($lead->est_closing_date));
                }
                return ' - ';
            })
            ->editColumn('status', function ($lead) {
                return $lead?->status?->name;
            })
            ->editColumn('qualification', function ($lead) {
                return $lead?->qualification?->name ?? ' - ';
            })
            ->addColumn('sections', function ($lead) {
                $sectionGroups = [];

                foreach ($lead->sectionData ?? [] as $sectionData) {
                    $sectionName = $sectionData->section?->name;
                    $optionName = $sectionData->option?->name ?? '';
                    $subSectionName = $sectionData->subSection?->name ?? '';

                    // Merge options with same section
                    if (!isset($sectionGroups[$sectionName])) {
                        $sectionGroups[$sectionName] = [];
                    }

                    $formattedOption = $subSectionName ? "{$optionName} / {$subSectionName}" : "{$optionName}";
                    $sectionGroups[$sectionName][] = $formattedOption;
                }

                $html = '';
                foreach ($sectionGroups as $section => $options) {
                    $html .= "<strong>{$section}:</strong> " . implode(', ', $options) . "<br>";
                }

                return $html;
            })

//            ->addColumn('tags', function ($lead) {
//                $tags = [];
//
//                foreach ($lead->sectionData ?? [] as $sectionData) {
//                    foreach ($sectionData->tags ?? [] as $tag) {
//                        $tags[] = "<span class='badge bg-primary'>{$tag->name}</span>";
//                    }
//                }
//
//                return implode(' ', $tags);
//            })
            ->addColumn('actions', function ($lead) {
                $deleteUrl = route('admin.leads.destroy', [
                    'leadId' => $lead->id,
                ]);

                $html = '<div class="btn-group">';

                $html .= '<a href="' . route('admin.leads.show', $lead->id) . '" class="btn btn-icon btn-gradient-warning">
                             <i class="ti ti-eye ti-xs"></i>
                        </a>';

                $html .= '<a href="#" class="btn btn-icon btn-gradient-danger"
                           data-bs-toggle="modal" data-bs-target="#deleteModal"
                           onclick="deleteForm(\'' . $deleteUrl . '\')">
                            <i class="ti ti-trash ti-xs"></i>
                       </a>';

                $html .= '</div>';
                return $html;
            })
            ->makeHidden(['created_at', 'updated_at', 'deleted_at'])
            ->rawColumns(['probability','has_budget','decision_maker','sections','tags','actions'])
            ->toJson();
    }

    /**
     * @inheritDoc
     */
    public function getTableColumns(): ?array
    {
        return  [
            'id'=> ['name' => 'id', 'table' => 'leads.id', 'searchable' => 'false', 'sortable' => 'true'],


            'company' => ['name' => 'Company', 'table' => 'companies.name', 'searchable' => 'true', 'sortable' => 'false'],
//            'probability' => ['name' => 'Probability', 'table' => 'leads.probability', 'searchable' => 'true', 'sortable' => 'true'],
//            'has_budget' => ['name' => 'Has budget', 'table' => 'leads.has_budget', 'searchable' => 'true', 'sortable' => 'true'],
//            'decision_maker' => ['name' => 'Decision Maker', 'table' => 'leads.decision_maker', 'searchable' => 'true', 'sortable' => 'true'],
//            'est_closing_date' => ['name' => 'Estimation Closing Date', 'table' => 'leads.est_closing_date', 'searchable' => 'true', 'sortable' => 'true'],
            'sections' => ['name' => 'Sections', 'table' => 'sections', 'searchable' => 'true', 'sortable' => 'false'],
            'status' => ['name' => 'Status', 'table' => 'leads.status', 'searchable' => 'true', 'sortable' => 'false'],
            'qualification' => ['name' => 'Qualification', 'table' => 'leads.qualification', 'searchable' => 'true', 'sortable' => 'false'],

        ];

    }

}
