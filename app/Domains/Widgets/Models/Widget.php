<?php

namespace App\Domains\Widgets\Models;

use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use App\Models\CactusEntity;

class Widget extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var string $name
     * @JMS\Serializer\Annotation\SerializedName("name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $name;

    /**
     * @var string $label
     * @JMS\Serializer\Annotation\SerializedName("label")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $label;

    /**
     * @var string $description
     * @JMS\Serializer\Annotation\SerializedName("description")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $description;

    /**
     * @var Role[] $roles
     * @JMS\Serializer\Annotation\SerializedName("roles")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Auth\Models\Role>")
     */
    private array $roles = [];


    public function getValues(bool $withRelations = true): array
    {
        // TODO: Implement getValues() method.
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Widget
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Widget
    {
        $this->name = $name;
        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): Widget
    {
        $this->label = $label;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Widget
    {
        $this->description = $description;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): Widget
    {
        $this->roles = $roles;
        return $this;
    }




}
