<?php

namespace App\Domains\Companies\Repositories\Eloquent;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use App\Domains\Companies\Models\Company;
use App\Domains\Companies\Repositories\Eloquent\Models\Company as EloquentCompany;
use App\Domains\Companies\Repositories\CompanyRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class EloqCompanyRepository implements CompanyRepositoryInterface
{

    /**
     *
     * @param \App\Domains\Companies\Repositories\Eloquent\Models\Company $model
     */
    public function __construct(private EloquentCompany $model)
    {}

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        $companies = $this->model->all();
        return ObjectSerializer::deserialize($companies?->toJson() ?? "{}", 'array<' . Company::class . '>', 'json');
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id, bool $withRelations = true): ?Company
    {
        $company = $this->model;

        if($withRelations) {
            $company = $company->with('users','companyType', 'country', 'files', 'notes');
        }

        $company = $company->find($id);

        return ObjectSerializer::deserialize($company?->toJson() ?? "{}", Company::class, 'json');
    }

    public function createOrUpdateByCompanyId(Company|CactusEntity $entity, ?string $companyId): ?Company
    {
        $company = $this->model::updateOrCreate(
            ['id' => $companyId],
            [
                'erp_id' => $entity->getErpId(),
                'name' => $entity->getName(),
                'email' => $entity->getEmail(),
                'phone' => $entity->getPhone(),
                'activity' => $entity->getActivity(),
                'type_id' => $entity->getTypeId(),
                'country_id' => $entity->getCountryId(),
                'city' => $entity->getCity(),
                'source_id' => $entity->getSourceId(),
                'website' => $entity->getWebsite(),
                'linkedin' => $entity->getLinkedin(),
            ]
        );

        return ObjectSerializer::deserialize($company?->toJson() ?? "{}", Company::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function store(CactusEntity|Company $entity): ?Company
    {
        $company = $this->model::create([
            'erp_id' => $entity->getErpId(),
            'name' => $entity->getName(),
            'email' => $entity->getEmail(),
            'phone' => $entity->getPhone(),
            'activity' => $entity->getActivity(),
            'type_id' => $entity->getTypeId(),
            'country_id' => $entity->getCountryId(),
            'city' => $entity->getCity(),
            'source_id' => $entity->getSourceId(),
            'website' => $entity->getWebsite(),
            'linkedin' => $entity->getLinkedin(),
        ]);

        return ObjectSerializer::deserialize($company?->toJson() ?? "{}", Company::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function update(CactusEntity|Company $entity, string $id): ?Company
    {
        $company = $this->model->find($id);

        $company->update([
            'erp_id' => $entity->getErpId(),
            'name' => $entity->getName(),
            'email' => $entity->getEmail(),
            'phone' => $entity->getPhone(),
            'activity' => $entity->getActivity(),
            'type_id' => $entity->getTypeId(),
            'country_id' => $entity->getCountryId(),
            'city' => $entity->getCity(),
            'source_id' => $entity->getSourceId(),
            'website' => $entity->getWebsite(),
            'linkedin' => $entity->getLinkedin(),
        ]);


        return ObjectSerializer::deserialize($company->toJson() ?? "{}", Company::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function deleteById(string $id): bool
    {
        $company = $this->model->findOrFail($id);

        return (bool)$company?->delete();
    }

    public function storeContacts(Company $entity, string $companyId): bool
    {
        $company = $this->model->find($companyId);

        if($company){
            $users = $entity->getUsers();
            $company->users()->syncWithoutDetaching($users);

            return true;
        }

        return false;
    }

    public function deleteContactByUserId(int $userId, string $companyId): ?bool
    {
        $company = $this->model->find($companyId);
        if($company){
            $company->users()->detach($userId);

            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function dataTableCompanies(array $filters = []): JsonResponse
    {
        $companies = $this->model->query();

        $companies = $companies
            ->when($filters['filterName'], function ($query,$searchTerm) {
                $query->where('name', 'LIKE', '%'.$searchTerm.'%');
            })
            ->when($filters['filterTypeId'], function ($query,$searchTerm) {
                $query->where('type_id', $searchTerm);
            })
            ->when($filters['filterDoyId'], function ($query,$searchTerm) {
                $query->where('doy_id', $searchTerm);
            });

        if ($filters['columnName'] && $filters['columnSortOrder']) {
            $companies = $companies->orderBy($filters['columnName'], $filters['columnSortOrder']);
        }

        return DataTables::of($companies)
            ->editColumn('type_id', function ($company) {
                return $company?->companyType?->name;
            })
            ->editColumn('doy', function ($company) {
                return $company?->doy?->name;
            })
            ->addColumn('actions', function ($company) use ($filters) {
                $deleteUrl = route('admin.companies.destroy', [
                    'companyId' => $company->id,
                ]);

                $html = '<div class="btn-group">';

                $html .= '<a href="' . route('admin.companies.show', $company->id) . '" class="btn btn-icon btn-gradient-warning">
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
            ->rawColumns(['actions'])
            ->toJson();
    }

    /**
     * @inheritDoc
     */
    public function dataTableCompaniesContacts(array $filters = []): JsonResponse
    {
        $company = $this->model->query();

        $company = $company->find($filters['filterCompanyId']);

        $companyContacts = $company->users();

        return DataTables::of($companyContacts)
            ->editColumn('name', function ($companyContact) {
                return $companyContact?->name;
            })
            ->editColumn('phone', function ($companyContact) {
                $user = User::find($companyContact->user_id);
                return $user?->userDetails?->phone ?? ' - ';
            })
            ->addColumn('actions', function ($companyContact) use ($filters) {
                $deleteUrl = route('admin.companies.contacts.delete', [
                    'companyId' => $filters['filterCompanyId'],
                    'deleteUserId'=> $companyContact->user_id
                ]);

                $html = '<div class="btn-group">';

//                $html .= '<a href="#" class="btn btn-icon btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#show-user">
//                             <i class="ti ti-eye ti-xs"></i>
//                        </a>';
                $html .= '<a href="#"  class="btn btn-icon btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#show-user" onclick="fetchContact(' . "'" . $companyContact->user_id . "'" . ')" data-bs-id="' . $companyContact->user_id . '" >
                                <i class="ti ti-eye ti-xs"></i>
                            </a>';

                $html .= '<a href="' . route('admin.contacts.edit', $companyContact->user_id) . '" class="btn btn-icon btn-gradient-warning">
                             <i class="ti ti-edit ti-xs"></i>
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
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function getContactsTableColumns(): ?array
    {
        return  [
            'id'=> ['name' => 'id', 'table' => 'users.id', 'searchable' => 'false', 'orderable' => 'true'],

            'name' => ['name' => 'Name', 'table' => 'users.name', 'searchable' => 'true', 'orderable' => 'true'],
            'phone' => ['name' => 'Phone', 'table' => 'users.userDetails.phone', 'searchable' => 'false', 'orderable' => 'true'],
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
        $companies = $this->model
            ->with(['lead', 'client'])
            ->select('companies.id', 'companies.name');

        if ($searchTerm != null) {
            $companies = $companies->where('name', 'LIKE', '%' . $searchTerm . '%');
        }


        $companies = $companies->skip($offset)->take($resultCount)->get();

        // map για προσθήκη status
        $results = $companies->map(function ($company) {
            $status = null;
            if ($company->client) {
                $status = 'client';
            } elseif ($company->lead) {
                $status = 'lead';
            }

            return [
                'id' => $company->id,
                'text' => $company->name,
                'status' => $status,
            ];
        });

        if ($searchTerm == null) {
            $count = $this->model->count();
        } else {
            $count = $companies->count();
        }

        return array(
            "data" => $results,
            "count" => $count
        );
    }

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function getContactsPaginatedByCompanyId(?string $searchTerm, int $offset, int $resultCount, int $companyId): array
    {
        $company = \App\Domains\Companies\Repositories\Eloquent\Models\Company::find($companyId);

        $users = $company->users()->select('users.id', DB::raw('users.name AS text'));

        if ($searchTerm != null) {
            $users = $users->where('users.name', 'LIKE', '%' . $searchTerm . '%');
        }

        if ($searchTerm == null) {
            $count = $users->count();
            $users = $users->skip($offset)->take($resultCount)->get();
        } else {
            $users = $users->skip($offset)->take($resultCount)->get();
            $count = $users->count();
        }

        return array(
            "data" => $users,
            "count" => $count
        );
    }
}
