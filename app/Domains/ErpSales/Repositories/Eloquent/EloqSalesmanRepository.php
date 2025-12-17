<?php

namespace App\Domains\ErpSales\Repositories\Eloquent;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Auth\Repositories\Eloquent\Models\User as EloquentUser;
use App\Domains\ErpSales\Models\Salesman;
use App\Domains\ErpSales\Repositories\Eloquent\Models\Salesman as EloquentSalesman;
use App\Domains\ErpSales\Repositories\SalesManRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;

class EloqSalesmanRepository implements SalesManRepositoryInterface
{

    private EloquentSalesman $model;

    /**
     *  @param EloquentSalesman $salesman
     *  @return void
     */
    public function __construct(EloquentSalesman $salesman)
    {
        $this->model = $salesman;
    }


    public function get(): array
    {
        $salesmen = $this->model->get();
        return ObjectSerializer::deserialize($salesmen?->toJson() ?? "{}", "array<". Salesman::class . ">", 'json');
    }

    public function getById(string $id, bool $withRelations = true): ?Salesman
    {
        $salesman = $this->model->find($id);
        return ObjectSerializer::deserialize($salesman?->toJson() ?? "{}", Salesman::class, 'json');
    }

    public function store(Salesman|CactusEntity $entity): ?Salesman
    {
        $salesman = $this->model->create(
            [
                'erp_id' => $entity->getErpId(),
                'name' => $entity->getName(),
            ]
        );
        return ObjectSerializer::deserialize($salesman?->toJson() ?? "{}", Salesman::class , 'json');
    }

    public function update(Salesman|CactusEntity $entity, string $id): ?Salesman
    {
        $salesman = $this->model->find($id);
        $salesman->update(
            [
                'erp_id' => $entity->getErpId(),
                'name' => $entity->getName(),
            ]
        );
        return ObjectSerializer::deserialize($salesman?->toJson() ?? "{}", Salesman::class , 'json');
    }

    public function createOrUpdateByErpId(Salesman|CactusEntity $entity, string $erpId): ?Salesman
    {
        $salesman = $this->model->where('erp_id', $erpId)->first();
        if ($salesman) {
            return $this->update($entity, $salesman->id);
        }
        return $this->store($entity);
    }

    public function deleteById(string $id): bool
    {
        $salesman = $this->model->find($id);
        return $salesman->delete();
    }

    public function getVisibleForUser(string $userId): array
    {
        $user = EloquentUser::findOrFail($userId);
        $query = $this->model->newQuery();

        // Sales Representative
        if ($user->hasAnyRole([RolesEnum::SALES_SKG->value, RolesEnum::SALES_ATH->value])) {
            $query->where('id', $user->salesman_id);
        }
        elseif ($user->hasRole(RolesEnum::SALES_DIRECTOR->value)) {
            // Sales Director βλέπει πωλητές του ίδιου sector

            $sectorIds = $user->sectors()->pluck('sectors.id');

            $query->whereIn('id', function ($sub) use ($sectorIds) {
                $sub->select('users.salesman_id')
                    ->from('users')
                    ->join('sector_user', 'sector_user.user_id', '=', 'users.id')
                    ->whereNotNull('users.salesman_id')
                    ->whereIn('sector_user.sector_id', $sectorIds)
                    ->groupBy('users.salesman_id');
            });
        }

        $salesmen = $query->get();

        return ObjectSerializer::deserialize($salesmen?->toJson() ?? "{}", "array<" . Salesman::class . ">", 'json');
    }
}
