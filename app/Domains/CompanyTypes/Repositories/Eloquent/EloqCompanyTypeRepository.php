<?php

namespace App\Domains\CompanyTypes\Repositories\Eloquent;

use App\Domains\CompanyTypes\Repositories\Eloquent\Models\CompanyType as EloquentCompanyType;
use App\Domains\CompanyTypes\Models\CompanyType;
use App\Domains\CompanyTypes\Repositories\CompanyTypeRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;

class EloqCompanyTypeRepository implements CompanyTypeRepositoryInterface
{
    private $model;

    /**
     * @param EloquentCompanyType $companyType
     */
    public function __construct(EloquentCompanyType $companyType)
    {
        $this->model = $companyType;
    }

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        $companyTypes = $this->model->get();
        return ObjectSerializer::deserialize($companyTypes->toJson() ?? "{}", 'array<' . CompanyType::class . '>', 'json');
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id): ?CompanyType
    {
        $companyType = $this->model->find($id);
        return ObjectSerializer::deserialize($companyType->toJson() ?? "{}", CompanyType::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function store(CompanyType|CactusEntity $entity): CompanyType
    {
        $companyType = $this->model::create([
            'name' => $entity->getName(),
        ]);

        return ObjectSerializer::deserialize($companyType->toJson() ?? "{}", CompanyType::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function update(CompanyType|CactusEntity $entity, string $id): CompanyType
    {
        $companyType = $this->model->find($id);

        if($companyType) {
            $companyType->update([
                'name' => $entity->getName(),
            ]);
        }

        return ObjectSerializer::deserialize($companyType->toJson() ?? "{}", CompanyType::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function deleteById(string $id): bool
    {
        $companyType = $this->model::find($id);

        if ($companyType) {
            $companyType->delete();
            return true;
        }

        return false;
    }
}
