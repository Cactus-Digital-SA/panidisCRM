<?php

namespace App\Domains\Auth\Models;

use App\Models\CactusEntity;

/**
 * Class Permission.
 */
class Permission extends CactusEntity
{
    /**
     * @var int
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var string
     * @JMS\Serializer\Annotation\SerializedName("name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $name;

    /**
     * @var string
     * @JMS\Serializer\Annotation\SerializedName("guard_name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $guardName;

    /**
     * @var string
     * @JMS\Serializer\Annotation\SerializedName("description")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $description;

    /**
     * @var ?int
     * @JMS\Serializer\Annotation\SerializedName("parent_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $parentId;

    /**
     * @var int
     * @JMS\Serializer\Annotation\SerializedName("sort")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $sort;

    /**
     * @var Permission[] $children
     * @JMS\Serializer\Annotation\SerializedName("children")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Auth\Models\Permission>")
     */
    private array $children = [];

    /**
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = true): array
    {
        $data = [];

        foreach (get_object_vars($this) as $property => $value) {
            $data[$property] = $value;
        }

        if ($withRelations) {
            $data['children'] = $this->getChildren();
        }


        return $data;
    }

    public function setId(int $id): Permission
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Permission
    {
        $this->name = $name;

        return $this;
    }

    public function getGuardName(): string
    {
        return $this->guardName;
    }

    public function setGuardName(string $guardName): Permission
    {
        $this->guardName = $guardName;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Permission
    {
        $this->description = $description;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): Permission
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): Permission
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return Permission[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function setChildren(array $children): Permission
    {
        $this->children = $children;

        return $this;
    }
}
