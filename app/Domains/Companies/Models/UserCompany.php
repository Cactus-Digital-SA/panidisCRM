<?php

namespace App\Domains\Companies\Models;

use App\Domains\Auth\Models\User;
use App\Models\CactusEntity;

class UserCompany extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var ?int $userId
     * @JMS\Serializer\Annotation\SerializedName("user_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $userId;

    /**
     * @var ?int $companyId
     * @JMS\Serializer\Annotation\SerializedName("company_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $companyId;

    /**
     * @var ?User $user
     * @JMS\Serializer\Annotation\SerializedName("user")
     * @JMS\Serializer\Annotation\Type("App\Domains\Auth\Models\User")
     */
    private ?User $user;

    /**
     * @var ?Company $company
     * @JMS\Serializer\Annotation\SerializedName("company")
     * @JMS\Serializer\Annotation\Type("App\Domains\Companies\Models\Company")
     */
    private ?Company $company;

    /**
     * Summary of getValues
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = true): array
    {
        $data = [
            'id' => $this->getId(),
            'userId' => $this->getUserId(),
            'companyId' => $this->getCompanyId(),
        ];

        if ($withRelations) {
            $data['user'] = $this->getUser();
            $data['company'] = $this->getCompany();
        }

        return $data;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): UserCompany
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId
     * @return $this
     */
    public function setUserId(?int $userId): UserCompany
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    /**
     * @param int|null $companyId
     * @return $this
     */
    public function setCompanyId(?int $companyId): UserCompany
    {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): UserCompany
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Company|null
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param Company|null $company
     * @return $this
     */
    public function setCompany(?Company $company): UserCompany
    {
        $this->company = $company;
        return $this;
    }


}
