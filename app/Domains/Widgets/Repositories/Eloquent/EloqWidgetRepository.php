<?php

namespace App\Domains\Widgets\Repositories\Eloquent;

use App\Domains\Widgets\Models\Widget;
use App\Domains\Widgets\Repositories\Eloquent\Models\RoleWidget;
use App\Domains\Widgets\Repositories\Eloquent\Models\Widget as EloquentWidget;
use App\Domains\Widgets\Repositories\WidgetRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;

class EloqWidgetRepository implements WidgetRepositoryInterface
{
    private EloquentWidget $model;

    /**
     *  @param EloquentWidget $widget
     *  @return void
     */
    public function __construct(EloquentWidget $widget)
    {
        $this->model = $widget;
    }

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        $widgets = $this->model->with('roles')->get();
        return ObjectSerializer::deserialize($widgets?->toJson() ?? "{}", "array<". Widget::class . ">", 'json');
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id, bool $withRelations = true): ?Widget
    {
        $widget = $this->model->find($id);
        return ObjectSerializer::deserialize($widget?->toJson() ?? "{}", Widget::class , 'json');
    }

    /**
     * @inheritDoc
     */
    public function store(Widget|CactusEntity $entity): ?Widget
    {
        $widget = $this->model->create(
            [
                'name' => $entity->getName(),
                'label' => $entity->getLabel(),
                'description' => $entity->getDescription(),
            ]
        );
        return ObjectSerializer::deserialize($widget?->toJson() ?? "{}", Widget::class , 'json');
    }

    /**
     * @inheritDoc
     */
    public function update(Widget|CactusEntity $entity, string $id): ?Widget
    {
        $widget = $this->model->find($id);
        $widget->update(
            [
                'name' => $entity->getName(),
                'label' => $entity->getLabel(),
                'description' => $entity->getDescription(),
            ]
        );
        return ObjectSerializer::deserialize($widget?->toJson() ?? "{}", Widget::class , 'json');
    }

    /**
     * @inheritDoc
     */
    public function deleteById(string $id): bool
    {
        $widget = $this->model->find($id);

        if ($widget) {
            $widget->delete();
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function assignWidgetToRole(array $widgets = []): bool
    {
        RoleWidget::query()->truncate();
        foreach ($widgets as $roleId => $selectedData) {
            foreach($selectedData as $widgetId){
                RoleWidget::create([
                    'role_id' => $roleId,
                    'widget_id' => $widgetId
                ]);
            }
        }

        return true;
    }
}
