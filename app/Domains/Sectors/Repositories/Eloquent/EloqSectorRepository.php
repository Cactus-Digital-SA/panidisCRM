<?php

namespace App\Domains\Sectors\Repositories\Eloquent;

use App\Domains\Sectors\Models\Sector;
use App\Domains\Sectors\Repositories\Eloquent\Models\Sector as EloquentSector;
use App\Domains\Sectors\Repositories\SectorRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;

class EloqSectorRepository implements SectorRepositoryInterface
{
    private EloquentSector $model;

    /**
     *  @param EloquentSector $sector
     *  @return void
     */
    public function __construct(EloquentSector $sector)
    {
        $this->model = $sector;
    }

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        $sectors = $this->model::all();

        return ObjectSerializer::deserialize($sectors?->toJson() ?? "{}", "array<". Sector::class . ">", 'json');
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id, bool $withRelations = true): ?Sector
    {
        $sector = $this->model::find($id);

        return ObjectSerializer::deserialize($sector?->toJson() ?? "{}", Sector::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function store(CactusEntity|Sector $entity): ?Sector
    {
        $sector = $this->model::create([
            'erp_id' => $entity->getErpId(),
            'name' => $entity->getName(),
        ]);

        return ObjectSerializer::deserialize($sector?->toJson() ?? "{}", Sector::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function update(CactusEntity|Sector $entity, string $id): ?Sector
    {
        $sector = $this->model->findOrFail($id);

        $sector->update([
            'erp_id' => $entity->getErpId(),
            'name' => $entity->getName(),
        ]);

        return ObjectSerializer::deserialize($sector?->toJson() ?? "{}", Sector::class, 'json');
    }

    public function createOrUpdateByErpId(Sector $entity, string $erpId): ?Sector
    {
        $sector = $this->model->where('erp_id', $erpId)->first();

        if($sector) {
            $sector = $this->update($entity, $sector->id);
        }else{
            $sector = $this->store($entity);
        }

        return $sector;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(string $id): bool
    {
        // TODO: Implement deleteById() method.
    }

}
