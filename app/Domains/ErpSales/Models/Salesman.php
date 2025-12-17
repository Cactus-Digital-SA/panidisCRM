<?php

namespace App\Domains\ErpSales\Models;

use App\Domains\Auth\Models\User;
use App\Models\CactusEntity;

class Salesman extends CactusEntity
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
     * @var ?User $user
     * @JMS\Serializer\Annotation\SerializedName("user")
     * @JMS\Serializer\Annotation\Type("App\Domains\Auth\Models\User")
     */
    private ?User $user = null;


    public function getValues(bool $withRelations = true): array
    {
        // TODO: Implement getValues() method.
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Salesman
    {
        $this->id = $id;
        return $this;
    }

    public function getErpId(): string
    {
        return $this->erpId;
    }

    public function setErpId(string $erpId): Salesman
    {
        $this->erpId = $erpId;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Salesman
    {
        $this->name = $name;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): Salesman
    {
        $this->user = $user;
        return $this;
    }


}
