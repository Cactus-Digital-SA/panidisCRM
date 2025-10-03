<?php

namespace App\Domains\Clients\Repositories\Eloquent;

use App\Domains\Clients\Models\Client;
use App\Domains\Clients\Repositories\ClientRepositoryInterface;
use App\Domains\Clients\Repositories\Eloquent\Models\Client as EloquentClient;
use App\Domains\ExtraData\Enums\ExtraDataModelsEnum;
use App\Domains\ExtraData\Enums\VisibilityEnum;
use App\Domains\ExtraData\Repositories\Eloquent\Models\ExtraData;
use App\Domains\Tags\Enums\TagTypesEnum;
use App\Domains\Tags\Repositories\Eloquent\Models\Tag;
use App\Facades\ObjectSerializer;
use App\Helpers\EloquentRelationHelper;
use App\Models\CactusEntity;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Container\Attributes\Auth;

class EloqClientRepository extends EloquentRelationHelper implements ClientRepositoryInterface
{
    private EloquentClient $model;

    public function __construct(EloquentClient $client = null)
    {
        $this->model = $client ?? new EloquentClient();
    }

    /**
     * @return Client[]
     */
    public function get(): array
    {
        $clients = $this->model->all();
        return ObjectSerializer::deserialize($clients->toJson() ?? "{}", 'array<' . Client::class . '>', 'json');
    }

    public function getById(string $id, bool $withRelations = true): ?Client
    {
        $client = $this->model;

        if($withRelations) {
            $client = $client->with('projects','company.companyType','company.companySource','company.extraData','company.country','extraData');
        }

        $client = $client->find($id);

        return ObjectSerializer::deserialize($client->toJson() ?? "{}", Client::class, 'json');
    }

    public function createOrUpdate(CactusEntity|Client $entity): ?Client
    {
        $client = $this->model::updateOrCreate(
            ['company_id' => $entity->getCompanyId(),],
            [
                'sales_person_id' => $entity->getSalesPersonId(),
            ]
        );

        $client->tags()->syncWithoutDetaching($entity->getTagIds());

        return ObjectSerializer::deserialize($client?->toJson() ?? "{}", Client::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function getByIdWithMorphsAndRelations(string $modelId, array $morphs = [], array $relations = []): ?Client
    {
        $client = $this->model::findOrFail($modelId);

        $client = $this->modelLoadRelations($client, $morphs);
        $client = $this->modelLoadRelations($client, $relations);

        return ObjectSerializer::deserialize($client?->toJson() ?? "{}",  Client::class , 'json');
    }

    /**
     * @param CactusEntity|Client $entity
     * @return Client|null
     */
    public function store(CactusEntity|Client $entity): ?Client
    {
        $client = $this->model::create([
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

        $client->tags()->syncWithoutDetaching($tagIds);

        return ObjectSerializer::deserialize($client->toJson() ?? "{}", Client::class, 'json');
    }

    public function storeOrUpdate(Client|CactusEntity $entity): ?Client
    {
        $client = $this->model::updateOrCreate([
            'company_id' => $entity->getCompanyId(),
            ],
            [
                'sales_person_id' => $entity->getSalesPersonId(),
            ]);

        return ObjectSerializer::deserialize($client?->toJson() ?? "{}", Client::class, 'json');
    }

    /**
     * @param CactusEntity|Client $entity
     * @param string $id
     * @return Client|null
     */
    public function update(CactusEntity|Client $entity, string $id): ?Client
    {
        $client = $this->model->find($id);

        $client->update([
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

        $client->tags()->syncWithoutDetaching($tagIds);

        return ObjectSerializer::deserialize($client->toJson() ?? "{}", Client::class, 'json');
    }

    public function deleteById(string $id): bool
    {
        $client = $this->model->findOrFail($id);

        if ($client) {
            $client->delete();

            return true;
        }

        return false;
    }

    /**
     * @param array $filters
     * @return JsonResponse
     * @throws Exception
     */
    public function dataTableClients(array $filters = []): JsonResponse
    {
        $clients = $this->model->query()->with(['company.companyType', 'company.companySource', 'company.country', 'salesPerson']);

        $clients = $clients
            ->when($filters['filterName'], function ($query,$searchTerm) {
                $query->whereHas('company', function($q) use ($searchTerm) {
                    $q->where('companies.name', $searchTerm);
                });
            });

        return DataTables::of($clients)
            ->editColumn('erpId', function ($client) {
                return '# '. $client?->company?->erp_id ?? ' - ';
            })
            ->editColumn('company', function ($client) {
                return $client?->company?->name;
            })
            ->addColumn('companyType', function ($client) {
                return $client?->company?->companyType?->name;
            })
            ->addColumn('companyRegion', function ($client) {
                if($client?->company?->country?->name == 'Greece'){
                    return $client?->company?->country?->name . ' - ' . $client?->company?->city;
                }
                return $client?->company?->country?->name;
            })
            ->addColumn('currentBalance', function ($client) {
                return $client?->company?->currentBalance ?? ' - ';
            })
            ->addColumn('salesPerson', function ($client) {
                $html ='';
                if($client?->salesPerson){
                    $html .=  '<a href="'. route('admin.users.show',$client?->salesPerson?->id).'" class="badge bg-label-secondary">' . $client?->salesPerson?->name . '</span>';
                }
                return $html;
            })
            ->addColumn('actions', function ($client) {
                $deleteUrl = route('admin.clients.destroy', [
                    'clientId' => $client->id,
                ]);

                $html = '<div class="btn-group">';

                $html .= '<a href="' . route('admin.clients.show', $client->id) . '" class="btn btn-icon btn-gradient-warning">
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
            'id'=> ['name' => 'id', 'table' => 'clients.id', 'searchable' => 'false', 'orderable' => 'true'],
            'erpId'=> ['name' => 'ERP ID', 'table' => 'company.erp_id', 'searchable' => 'false', 'orderable' => 'true'],
            'company' => ['name' => 'Εταιρεία', 'table' => 'company.name', 'searchable' => 'true', 'orderable' => 'true'],
            'companyType' => ['name' => 'Κατηγορία Πελάτη', 'table' => 'company.companyType.name', 'searchable' => 'true', 'orderable' => 'true'],
            'companyRegion' => ['name' => 'Περιοχή', 'table' => 'company.country.name', 'searchable' => 'true', 'orderable' => 'true'],
            'currentBalance' => ['name' => 'Υπόλοιπο', 'table' => 'company.current_balance', 'searchable' => 'true', 'orderable' => 'true'],
            'salesPerson' => ['name' => 'Πωλητής', 'table' => 'salesPerson.name', 'searchable' => 'true', 'orderable' => 'true'],
        ];

    }


    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function namesPaginated(?string $searchTerm, int $offset, int $resultCount): array
    {
        $clients = $this->model->join('companies','companies.id', '=','clients.company_id')
            ->select('clients.id', DB::raw('companies.name AS text'));

        if ($searchTerm != null) {
            $clients = $clients->whereHas('company', function($q) use ($searchTerm ) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%');
            });
        }


        $clients = $clients->skip($offset)->take($resultCount)->get('id');


        if ($searchTerm == null) {
            $count = $this->model->count();
        } else {
            $count = $clients->count();
        }

        return array(
            "data" => $clients,
            "count" => $count
        );
    }


}
