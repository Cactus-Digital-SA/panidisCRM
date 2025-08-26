<?php

namespace App\Domains\CompanyTypes\Models;

use App\Models\CactusEntity;
use Illuminate\Http\Request;

class CompanyType extends CactusEntity
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
     * @return \App\Domains\CompanyTypes\Models\CompanyType
     */
    public function setId(int $id): CompanyType
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
     * @return \App\Domains\CompanyTypes\Models\CompanyType
     */
    public function setName(string $name): CompanyType
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Summary of fromRequest
     * @param \Illuminate\Http\Request $request
     * @return \App\Domains\CompanyTypes\Models\CompanyType
     */
    public function fromRequest(Request $request): CompanyType
    {
        $companyType = new self();
        $companyType->setName($request['name']);
        return $companyType;
    }
}
