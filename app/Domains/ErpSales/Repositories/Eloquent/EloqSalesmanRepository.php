<?php

namespace App\Domains\ErpSales\Repositories\Eloquent;

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
}
