<?php

namespace App\Domains\Auth\Models;

use App\Models\CactusEntity;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class UserDetails extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var int|null $userId
     * @JMS\Serializer\Annotation\SerializedName("user_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $userId;

    /**
     * @var string $firstName
     * @JMS\Serializer\Annotation\SerializedName("first_name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $firstName;

    /**
     * @var string $lastName
     * @JMS\Serializer\Annotation\SerializedName("last_name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $lastName;


    /**
     * @var string|null $phone
     * @JMS\Serializer\Annotation\SerializedName("phone")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $phone = null;

    /**
     * @var bool $phoneConfirmed
     * @JMS\Serializer\Annotation\SerializedName("phone_confirmed")
     * @JMS\Serializer\Annotation\Type("bool")
     */
    private bool $phoneConfirmed = false;

    /**
     * @var \DateTime|null $phoneConfirmedAt
     * @JMS\Serializer\Annotation\SerializedName("phone_confirmed_at")
     * @JMS\Serializer\Annotation\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private ?\DateTime $phoneConfirmedAt = null;

    /**
     * @var DateTime|null $birthday
     * @JMS\Serializer\Annotation\SerializedName("birthday")
     * @JMS\Serializer\Annotation\Type("DateTime<'Y-m-d'>")
     */
    private ?DateTime $birthday = null;

    /**
     * @var bool $decisionMaker
     * @JMS\Serializer\Annotation\SerializedName("decision_maker")
     * @JMS\Serializer\Annotation\Type("bool")
     */
    private bool $decisionMaker = false;

    /**
     * @var ?User $user
     * @JMS\Serializer\Annotation\SerializedName("user")
     * @JMS\Serializer\Annotation\Type("App\Domains\Auth\Models\User")
     */
    private ?User $user;

//    /**
//     * @var ?ServiceArea $residenceArea
//     * @JMS\Serializer\Annotation\SerializedName("residence_area")
//     * @JMS\Serializer\Annotation\Type("App\Domains\Auth\Models\User")
//     */
//    private ?ServiceArea $residenceArea;


    /**
     * Summary of getValues
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = true): array
    {
        $data = [
            'id' => $this->id,
            'userId' => $this->userId,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'phone' => $this->phone,
            'phoneConfirmed' => $this->phoneConfirmed,
            'phoneConfirmedAt' => $this->phoneConfirmedAt,
            'birthday' => $this->birthday,
        ];

        if ($withRelations) {
            $data['user'] = $this->getUser();
//            $data['residenceArea'] = $this->getResidenceArea();
        }

        return $data;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id ?? 0;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): UserDetails
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Summary of getUserId
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId ?? null;
    }

    /**
     * Summary of setUserId
     * @param mixed $userId
     * @return \App\Domains\Auth\Models\UserDetails
     */
    public function setUserId(?int $userId): UserDetails
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Summary of getFirstName
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Summary of setFirstName
     * @param string $firstName
     * @return \App\Domains\Auth\Models\UserDetails
     */
    public function setFirstName(string $firstName): UserDetails
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Summary of getLastName
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Summary of setLastName
     * @param string $lastName
     * @return \App\Domains\Auth\Models\UserDetails
     */
    public function setLastName(string $lastName): UserDetails
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Summary of getPhone
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone ?? null;
    }

    /**
     * Summary of setPhone
     * @param mixed $phone
     * @return \App\Domains\Auth\Models\UserDetails
     */
    public function setPhone(?string $phone): UserDetails
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Summary of getPhoneConfirmed
     * @return bool
     */
    public function getPhoneConfirmed(): bool
    {
        return $this->phoneConfirmed;
    }

    /**
     * Summary of setPhoneConfirmed
     * @param bool $phoneConfirmed
     * @return \App\Domains\Auth\Models\UserDetails
     */
    public function setPhoneConfirmed(bool $phoneConfirmed): UserDetails
    {
        $this->phoneConfirmed = $phoneConfirmed;
        return $this;
    }

    /**
     * Summary of getPhoneConfirmedAt
     * @return DateTime|null
     */
    public function getPhoneConfirmedAt(): ?DateTime
    {
        return $this->phoneConfirmedAt;
    }

    /**
     * Summary of setPhoneConfirmedAt
     * @param mixed $phoneConfirmedAt
     * @return \App\Domains\Auth\Models\UserDetails
     */
    public function setPhoneConfirmedAt(?DateTime $phoneConfirmedAt): UserDetails
    {
        $this->phoneConfirmedAt = $phoneConfirmedAt;
        return $this;
    }

    /**
     * Summary of getBirthday
     * @return DateTime|null
     */
    public function getBirthday(): ?DateTime
    {
        return $this->birthday ?? null;
    }

    /**
     * Summary of setBirthday
     * @param mixed $birthday
     * @return \App\Domains\Auth\Models\UserDetails
     */
    public function setBirthday(?DateTime $birthday): UserDetails
    {
        $this->birthday = $birthday;
        return $this;
    }

    public function getDecisionMaker(): bool
    {
        return $this->decisionMaker;
    }

    public function setDecisionMaker(bool $decisionMaker): UserDetails
    {
        $this->decisionMaker = $decisionMaker;
        return $this;
    }

    /**
     * Summary of getUser
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user ?? null;
    }

    /**
     * Summary of setUser
     * @param mixed $user
     * @return \App\Domains\Auth\Models\UserDetails
     */
    public function setUser(?User $user): UserDetails
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Summary of fromRequest
     * @param \Illuminate\Http\Request $request
     * @return \App\Domains\Auth\Models\UserDetails
     */
    public static function fromRequest(Request $request): UserDetails
    {
        $userDetails = new UserDetails();
        return $userDetails
            ->setUserId($request['userId'])
            ->setFirstName($request['firstName'])
            ->setLastName($request['lastName'])
            ->setPhone($request['phone'])
            ->setPhoneConfirmed($request['phoneConfirmed'] ?? true)
            ->setPhoneConfirmedAt(Carbon::parse($request['phoneConfirmedAt']))
            ->setBirthday(Carbon::parse($request['birthday']))
            ->setDecisionMaker($request['decisionMaker'] ?? false);
    }

}
