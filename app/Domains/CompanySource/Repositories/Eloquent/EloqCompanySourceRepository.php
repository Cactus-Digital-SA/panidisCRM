<?php

namespace App\Domains\CompanySource\Repositories\Eloquent;

use App\Domains\CompanySource\Repositories\Eloquent\Models\CompanySource as EloquentCompanySource;
use App\Domains\CompanySource\Models\CompanySource;
use App\Domains\CompanySource\Repositories\CompanySourceRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;

class EloqCompanySourceRepository implements CompanySourceRepositoryInterface
{
    private $model;

    /**
     * @param EloquentCompanySource $companySource
     */
    public function __construct(EloquentCompanySource $companySource)
    {
        $this->model = $companySource;
    }

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        $companySources = $this->model->get();
        return ObjectSerializer::deserialize($companySources->toJson() ?? "{}", 'array<' . CompanySource::class . '>', 'json');
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id): ?CompanySource
    {
        $companySource = $this->model->find($id);
        return ObjectSerializer::deserialize($companySource->toJson() ?? "{}", CompanySource::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function store(CompanySource|CactusEntity $entity): CompanySource
    {
        $companySource = $this->model::create([
            'name' => $entity->getName(),
        ]);

        return ObjectSerializer::deserialize($companySource->toJson() ?? "{}", CompanySource::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function update(CompanySource|CactusEntity $entity, string $id): CompanySource
    {
        $companySource = $this->model->find($id);

        if($companySource) {
            $companySource->update([
                'name' => $entity->getName(),
            ]);
        }

        return ObjectSerializer::deserialize($companySource->toJson() ?? "{}", CompanySource::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function deleteById(string $id): bool
    {
        $companySource = $this->model::find($id);

        if ($companySource) {
            $companySource->delete();
            return true;
        }

        return false;
    }
}
