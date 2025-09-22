<?php

namespace App\Domains\Leads\Repositories\Eloquent;

use App\Domains\Leads\Models\Lead;
use App\Domains\Leads\Repositories\Eloquent\Models\Lead as EloquentLead;
use App\Domains\Leads\Repositories\LeadRepositoryInterface;
use App\Domains\Tags\Enums\TagTypesEnum;
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
            $lead = $lead->with(['company','company.companyType','company.companySource','tags'])->find($id);
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

        $tagIds = [];
        if (!empty($entity->getTagIds())) {
            foreach ($entity->getTagIds() as $tag) {
                if (is_numeric($tag)) {
                    $tagIds[] = (int) $tag;
                } else {
                    // προσθήκη νέου tag
                    $newTag = Tag::firstOrCreate(['name' => $tag]);

                    $newTag->types()->syncWithoutDetaching([TagTypesEnum::PRODUCT->value]);

                    $tagIds[] = $newTag->id;
                }
            }
        }

        $lead->tags()->syncWithoutDetaching($tagIds);


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

        $tagIds = [];
        if (!empty($entity->getTagIds())) {
            foreach ($entity->getTagIds() as $tag) {
                if (is_numeric($tag)) {
                    $tagIds[] = (int) $tag;
                } else {
                    // προσθήκη νέου tag
                    $newTag = Tag::firstOrCreate(['name' => $tag]);

                    $newTag->types()->syncWithoutDetaching([TagTypesEnum::PRODUCT->value]);

                    $tagIds[] = $newTag->id;
                }
            }
        }

        $lead->tags()->syncWithoutDetaching($tagIds);


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
        $leads = $this->model->query();

        $leads = $leads->with(['company.companyType', 'company.companySource', 'company.country', 'salesPerson']);
        $leads = $leads
            ->when($filters['filterName'], function ($query,$searchTerm) {
                $query->whereHas('company', function($q) use ($searchTerm) {
                    $q->where('companies.name', 'LIKE', '%'.$searchTerm.'%');
                });
            });

        return DataTables::of($leads)
            ->editColumn('erpId', function ($lead) {
                return '# '. $lead?->company?->erp_id ?? ' - ';
            })
            ->editColumn('company', function ($lead) {
                return $lead?->company?->name;
            })
            ->addColumn('companyType', function ($lead) {
                return $lead?->company?->companyType?->name;
            })
            ->addColumn('companyRegion', function ($lead) {
                if($lead?->company?->country?->name == 'Greece'){
                    return $lead?->company?->country?->name . ' - ' . $lead?->company?->city;
                }
                return $lead?->company?->country?->name;
            })
            ->addColumn('currentBalance', function ($lead) {
                return $lead?->company?->currentBalance ?? ' - ';
            })
            ->addColumn('salesPerson', function ($lead) {
                $html ='';
                if($lead?->salesPerson){
                    $html .=  '<a href="'. route('admin.users.show',$lead?->salesPerson?->id).'" class="badge bg-label-secondary">' . $lead?->salesPerson?->name . '</span>';
                }
                return $html;
            })
            ->addColumn('actions', function ($lead) {
                $deleteUrl = route('admin.leads.destroy', [
                    'leadId' => $lead->id,
                ]);

                $html = '<div class="btn-group">';

                $html .= '<a href="' . route('admin.leads.show', $lead->id) . '" class="btn btn-icon btn-gradient-warning">
                             <i class="ti ti-eye ti-sm"></i>
                        </a>';

                $html .= '<a href="#" class="btn btn-icon btn-gradient-danger"
                           data-bs-toggle="modal" data-bs-target="#deleteModal"
                           onclick="deleteForm(\'' . $deleteUrl . '\')">
                            <i class="ti ti-trash ti-sm"></i>
                       </a>';

                $html .= '</div>';
                return $html;
            })
            ->makeHidden(['created_at', 'updated_at', 'deleted_at'])
            ->rawColumns(['salesPerson', 'actions'])
            ->toJson();
    }

    /**
     * @inheritDoc
     */
    public function getTableColumns(): ?array
    {
        return  [
            'id'=> ['name' => 'id', 'table' => 'leads.id', 'searchable' => 'false', 'orderable' => 'true'],
            'erpId'=> ['name' => 'ERP ID', 'table' => 'company.erp_id', 'searchable' => 'false', 'orderable' => 'true'],
            'company' => ['name' => 'Εταιρεία', 'table' => 'company.name', 'searchable' => 'true', 'orderable' => 'true'],
            'companyType' => ['name' => 'Κατηγορία Πελάτη', 'table' => 'company.companyType.name', 'searchable' => 'true', 'orderable' => 'true'],
            'companyRegion' => ['name' => 'Περιοχή', 'table' => 'company.country.name', 'searchable' => 'true', 'orderable' => 'true'],
            'currentBalance' => ['name' => 'Υπόλοιπο', 'table' => 'company.current_balance', 'searchable' => 'true', 'orderable' => 'true'],
            'salesPerson' => ['name' => 'Πωλητής', 'table' => 'salesPerson.name', 'searchable' => 'true', 'orderable' => 'true'],
        ];

    }

}
