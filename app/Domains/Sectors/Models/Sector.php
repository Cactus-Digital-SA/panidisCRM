<?php

namespace App\Domains\Sectors\Models;

use App\Domains\Auth\Models\User;
use App\Models\CactusEntity;

class Sector extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var string $erpId
     * @JMS\Serializer\Annotation\SerializedName("erp_id")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $erpId;

    /**
     * @var string $name
     * @JMS\Serializer\Annotation\SerializedName("name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $name;

    /**
     * @var User[] $users
     * @JMS\Serializer\Annotation\SerializedName("users")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Auth\Models\User>")
     */
    private array $users = [];

    public function getValues(bool $withRelations = true): array
    {
        // TODO: Implement getValues() method.
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Sector
    {
        $this->id = $id;
        return $this;
    }

    public function getErpId(): string
    {
        return $this->erpId;
    }

    public function setErpId(string $erpId): Sector
    {
        $this->erpId = $erpId;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Sector
    {
        $this->name = $name;
        return $this;
    }


}
