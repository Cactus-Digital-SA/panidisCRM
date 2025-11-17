<?php

namespace App\Domains\Sectors\Models;

use App\Domains\Auth\Models\User;
use App\Models\CactusEntity;

class SectorUser extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var int $sectorId
     * @JMS\Serializer\Annotation\SerializedName("sector_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $sectorId;

    /**
     * @var int $userId
     * @JMS\Serializer\Annotation\SerializedName("user_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $userId;

    /**
     * @var ?Sector $sector
     * @JMS\Serializer\Annotation\SerializedName("sector")
     * @JMS\Serializer\Annotation\Type("App\Domains\Sectors\Models\Sector")
     */
    private ?Sector $sector = null;

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

    public function setId(int $id): SectorUser
    {
        $this->id = $id;
        return $this;
    }

    public function getSectorId(): int
    {
        return $this->sectorId;
    }

    public function setSectorId(int $sectorId): SectorUser
    {
        $this->sectorId = $sectorId;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): SectorUser
    {
        $this->userId = $userId;
        return $this;
    }

    public function getSector(): ?Sector
    {
        return $this->sector;
    }

    public function setSector(?Sector $sector): SectorUser
    {
        $this->sector = $sector;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): SectorUser
    {
        $this->user = $user;
        return $this;
    }



}
