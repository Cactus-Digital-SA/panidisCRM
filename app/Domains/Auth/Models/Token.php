<?php

namespace App\Domains\Auth\Models;

use App\Models\CactusEntity;
use DateTime;

class Token extends CactusEntity
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
     * @var string $token
     * @JMS\Serializer\Annotation\SerializedName("token")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $token;

    /**
     * @var DateTime|null $lastUsedAt
     * @JMS\Serializer\Annotation\SerializedName("last_used_at")
     * @JMS\Serializer\Annotation\Type("DateTime<'Y-m-d\TH:i:s.up'>")
     */
    private ?DateTime $lastUsedAt = null;

    /**
     * @var DateTime|null $expiresAt
     * @JMS\Serializer\Annotation\SerializedName("expires_at")
     * @JMS\Serializer\Annotation\Type("DateTime<'Y-m-d\TH:i:s.up'>")
     */
    private ?DateTime $expiresAt = null;

    public function getValues(bool $withRelations = true): array
    {
        return [];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Token
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Token
    {
        $this->name = $name;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): Token
    {
        $this->token = $token;
        return $this;
    }

    public function getLastUsedAt(): ?DateTime
    {
        return $this->lastUsedAt;
    }

    public function setLastUsedAt(?DateTime $lastUsedAt): Token
    {
        $this->lastUsedAt = $lastUsedAt;
        return $this;
    }

    public function getExpiresAt(): ?DateTime
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?DateTime $expiresAt): Token
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }


}
