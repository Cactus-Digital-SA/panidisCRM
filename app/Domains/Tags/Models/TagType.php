<?php

namespace App\Domains\Tags\Models;

use App\Models\CactusEntity;

class TagType extends CactusEntity
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
     * @param bool $withRelations
     * @return array{id: int, name: string}
     */
    public function getValues(bool $withRelations = true): array
    {
        $data = [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];


        return $data;
    }

    /**
     * Summary of getId
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    /**
     * Summary of setId
     * @param int $id
     * @return \App\Domains\Tags\Models\TagType
     */
    public function setId(int $id): TagType
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Summary of getName
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    /**
     * Summary of setName
     * @param string $name
     * @return \App\Domains\Tags\Models\TagType
     */
    public function setName(string $name): TagType
    {
        $this->name = $name;

        return $this;
    }

}
