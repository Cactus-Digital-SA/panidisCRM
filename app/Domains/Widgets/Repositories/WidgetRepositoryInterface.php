<?php

namespace App\Domains\Widgets\Repositories;

use App\Domains\Widgets\Models\Widget;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;

interface WidgetRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Widget[]
     */
    public function get(): array;

    /**
     * @param string $id
     * @param bool $withRelations
     * @return Widget|null
     */
    public function getById(string $id, bool $withRelations = true): ?Widget;

    /**
     * @param CactusEntity|Widget $entity
     * @return Widget|null
     */
    public function store(CactusEntity|Widget $entity): ?Widget;

    /**
     * @param CactusEntity|Widget $entity
     * @param string $id
     * @return Widget|null
     */
    public function update(CactusEntity|Widget $entity, string $id): ?Widget;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;

    /**
     * @param array $widgets
     * @return bool
     */
    public function assignWidgetToRole(array $widgets = []): bool;

}
