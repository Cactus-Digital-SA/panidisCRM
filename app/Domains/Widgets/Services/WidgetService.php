<?php

namespace App\Domains\Widgets\Services;

use App\Domains\Widgets\Models\Widget;
use App\Domains\Widgets\Repositories\WidgetRepositoryInterface;

class WidgetService
{
    private WidgetRepositoryInterface $repository;

    /**
     * @param WidgetRepositoryInterface $repository
     */
    public function __construct(WidgetRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Widget[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $id
     * @return Widget|null
     */
    public function getById(string $widgetId, bool $withRelations = true): ?Widget
    {
        return $this->repository->getById($widgetId, $withRelations);
    }

    /**
     * @param Widget $widget
     * @return Widget|null
     */
    public function store(Widget $widget): Widget
    {
        $widgetDTO = $this->repository->store($widget);
        return $widgetDTO;
    }

    /**
     * @param Widget $widget
     * @param string $id
     * @return Widget|null
     */
    public function update(Widget $widget, string $id): Widget
    {
        $widgetDTO = $this->repository->update($widget, $id);
        return $widgetDTO;
    }

    /**
     * @param string $id
     * @return bool
     *
     */
    public function deleteById(string $id): bool
    {
        return $this->repository->deleteById($id);
    }

    public function assignWidgetToRole(array $widgets = []): bool
    {
        return $this->repository->assignWidgetToRole($widgets);
    }
}
