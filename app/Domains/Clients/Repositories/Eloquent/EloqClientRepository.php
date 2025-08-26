<?php

namespace App\Domains\Clients\Repositories\Eloquent;

use App\Domains\Clients\Models\Client;
use App\Domains\Clients\Repositories\ClientRepositoryInterface;
use App\Domains\Clients\Repositories\Eloquent\Models\Client as EloquentClient;
use App\Domains\ExtraData\Enums\ExtraDataModelsEnum;
use App\Domains\ExtraData\Enums\VisibilityEnum;
use App\Domains\ExtraData\Repositories\Eloquent\Models\ExtraData;
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
            $client = $client->with('projects','company','company.doy','company.companyType','company.extraData','company.country','extraData');
        }

        $client = $client->find($id);

        return ObjectSerializer::deserialize($client->toJson() ?? "{}", Client::class, 'json');
    }

    public function createOrUpdate(CactusEntity|Client $entity): ?Client
    {
        $client = $this->model::updateOrCreate(
            ['company_id' => $entity->getCompanyId(),],
            [
                'status_id' => $entity->getStatusId(),
            ]
        );

        return ObjectSerializer::deserialize($client?->toJson() ?? "{}", Client::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function getByIdWithMorphsAndRelations(string $modelId, array $morphs = [], array $relations = []): ?Client
    {
        $lead = $this->model::findOrFail($modelId);

        $lead = $this->modelLoadRelations($lead, $morphs);
        $lead = $this->modelLoadRelations($lead, $relations);

        return ObjectSerializer::deserialize($lead?->toJson() ?? "{}",  Client::class , 'json');
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

        return ObjectSerializer::deserialize($client->toJson() ?? "{}", Client::class, 'json');
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
        $clients = $this->model->query()->with('company');

        $clients = $clients
            ->when($filters['filterName'], function ($query,$searchTerm) {
                $query->whereHas('company', function($q) use ($searchTerm) {
                    $q->where('companies.name', $searchTerm);
                });
            });

        if ($filters['columnName'] && $filters['columnSortOrder']) {
            $clients = $clients->orderBy($filters['columnName'], $filters['columnSortOrder']);
        }

        return DataTables::of($clients)
            ->editColumn('company', function ($client) {
                return $client?->company?->name;
            })
            ->editColumn('status', function ($client) {
                return $client?->status?->name;
            })
            ->addColumn('actions', function ($client) {
                $deleteUrl = route('admin.clients.destroy', [
                    'clientId' => $client->id,
                ]);

                $html = '<div class="btn-group">';

                $html .= '<a href="' . route('admin.clients.show', $client->id) . '" class="btn btn-icon btn-gradient-warning">
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
            ->rawColumns(['source', 'actions'])
            ->toJson();
    }

    /**
     * @inheritDoc
     */
    public function getTableColumns(): ?array
    {
        return  [
            'id'=> ['name' => 'id', 'table' => 'clients.id', 'searchable' => 'false', 'sortable' => 'false', 'visible'=> 'false'],

            'company' => ['name' => 'Company', 'table' => 'companies.name', 'searchable' => 'true', 'sortable' => 'true'],
            'status' => ['name' => 'Status', 'table' => 'clients.status', 'searchable' => 'true', 'sortable' => 'true'],
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
