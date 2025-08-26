<?php

namespace App\Domains\CompanySource\Models;

use App\Models\CactusEntity;
use Illuminate\Http\Request;

class CompanySource extends CactusEntity
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
     * Summary of getValues
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = true): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        return $data;
    }

    /**
     * Summary of getId
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Summary of setId
     * @param int $id
     * @return \App\Domains\CompanySource\Models\CompanySource
     */
    public function setId(int $id): CompanySource
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Summary of getName
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Summary of setName
     * @param string $name
     * @return \App\Domains\CompanySource\Models\CompanySource
     */
    public function setName(string $name): CompanySource
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Summary of fromRequest
     * @param \Illuminate\Http\Request $request
     * @return \App\Domains\CompanySource\Models\CompanySource
     */
    public function fromRequest(Request $request): CompanySource
    {
        $CompanySource = new self();
        $CompanySource->setName($request['name']);
        return $CompanySource;
    }
}
