<?php

namespace App\Domains\CountryCodes\Models;

use App\Models\CactusEntity;

class CountryCode extends CactusEntity
{
    /**
     * @var ?int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $id;

    /**
     * @var ?string $code
     * @JMS\Serializer\Annotation\SerializedName("code")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $code;

    /**
     * @var ?string $name
     * @JMS\Serializer\Annotation\SerializedName("name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $name;

    public function getValues(bool $withRelations = true): array
    {
        $data = [
            'id' => $this->getId(),
            'code'=> $this->getCode(),
            'name'=> $this->getName(),
        ];
        
        return $data;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): CountryCode
    {
        $this->id = $id;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): CountryCode
    {
        $this->code = $code;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): CountryCode
    {
        $this->name = $name;
        return $this;
    }
}
