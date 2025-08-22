<?php

namespace App\Domains\Files\Repositories;

use App\Domains\Files\Models\File;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;

interface FileRepositoryInterface extends RepositoryInterface
{
    public function getById(string $id): ?File;
    public function store(CactusEntity|File $entity): ?File;
    public function update(CactusEntity|File $entity, string $id): ?File;
    public function deleteById(string $id): bool;

    /**
     * @param File $file
     * @param string $model
     * @param string $morphable_id
     * @return mixed
     */
    public function attach(File $file, string $model, string $morphable_id):bool;

    public function deleteByPath(string $filePath): bool;


}
