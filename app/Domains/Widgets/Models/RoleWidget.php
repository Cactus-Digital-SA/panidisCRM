<?php

namespace App\Domains\Widgets\Models;

use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use App\Domains\Sectors\Models\Sector;
use App\Models\CactusEntity;

class RoleWidget extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var int $roleId
     * @JMS\Serializer\Annotation\SerializedName("role_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $roleId;

    /**
     * @var int $widgetId
     * @JMS\Serializer\Annotation\SerializedName("widget_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $widgetId;

    /**
     * @var bool $enabled
     * @JMS\Serializer\Annotation\SerializedName("enabled")
     * @JMS\Serializer\Annotation\Type("bool")
     */
    private bool $enabled = true;

    /**
     * @var int $position
     * @JMS\Serializer\Annotation\SerializedName("position")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $position = 0;

    /**
     * @var ?Role $role
     * @JMS\Serializer\Annotation\SerializedName("role")
     * @JMS\Serializer\Annotation\Type("App\Domains\Auth\Models\Role")
     */
    private ?Role $role = null;

    /**
     * @var ?Widget $widget
     * @JMS\Serializer\Annotation\SerializedName("widget")
     * @JMS\Serializer\Annotation\Type("App\Domains\Widgets\Models\Widget")
     */
    private ?Widget $widget = null;



    public function getValues(bool $withRelations = true): array
    {
        // TODO: Implement getValues() method.
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): RoleWidget
    {
        $this->id = $id;
        return $this;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function setRoleId(int $roleId): RoleWidget
    {
        $this->roleId = $roleId;
        return $this;
    }

    public function getWidgetId(): int
    {
        return $this->widgetId;
    }

    public function setWidgetId(int $widgetId): RoleWidget
    {
        $this->widgetId = $widgetId;
        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): RoleWidget
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): RoleWidget
    {
        $this->position = $position;
        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): RoleWidget
    {
        $this->role = $role;
        return $this;
    }

    public function getWidget(): ?Widget
    {
        return $this->widget;
    }

    public function setWidget(?Widget $widget): RoleWidget
    {
        $this->widget = $widget;
        return $this;
    }


}
