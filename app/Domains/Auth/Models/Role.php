<?php

namespace App\Domains\Auth\Models;

use App\Models\CactusEntity;

/**
 * Class Role.
 */
class Role extends CactusEntity
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
     * @var string $guardName
     * @JMS\Serializer\Annotation\SerializedName("guard_name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $guardName;

    /**
     * @var Permission[] $permissions
     * @JMS\Serializer\Annotation\SerializedName("permissions")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Auth\Models\Permission>")
     */
    private array $permissions = [];

    /**
     * @param bool $withRelations
     * @return array The array containing the model values.
     */
    public function getValues(bool $withRelations = true): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'guardName' => $this->guardName,
        ];

//        if ($withRelations) {
//            $data['permissions'] = $this->mapRelationToArray($this->getPermissions());
//        }

        return $data;
    }

    public function setId(int $id): Role
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

    public function setName(string $name): Role
    {
        $this->name = $name;

        return $this;
    }

    public function getGuardName(): string
    {
        return $this->guardName;
    }

    public function setGuardName(string $guardName): Role
    {
        $this->guardName = $guardName;

        return $this;
    }

    /**
     * @return Permission[]
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @param Permission[] $permissions
     * @return Role
     */
    public function setPermissions(array $permissions): Role
    {
        $this->permissions = $permissions;

        return $this;
    }
}
