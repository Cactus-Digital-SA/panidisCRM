<?php

namespace App\Domains\Companies\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Clients\Models\Client;
use App\Domains\CompanySource\Models\CompanySource;
use App\Domains\CompanyTypes\Models\CompanyType;
use App\Domains\CountryCodes\Models\CountryCode;
use App\Domains\ExtraData\Models\ExtraData;
use App\Domains\Files\Models\File;
use App\Domains\Leads\Models\Lead;
use App\Domains\Notes\Models\Note;
use App\Domains\Tickets\Models\Ticket;
use App\Models\CactusEntity;
use App\Models\Enums\EloqMorphEnum;
use Illuminate\Http\Request;
use JMS\Serializer\Annotation as Serializer;

class Company extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var ?string $erpId
     * @JMS\Serializer\Annotation\SerializedName("erp_id")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $erpId = null;

    /**
     * @var string $name
     * @JMS\Serializer\Annotation\SerializedName("name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $name;

    /**
     * @var string|null $email
     * @JMS\Serializer\Annotation\SerializedName("email")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $email = "";

    /**
     * @var string|null $phone
     * @JMS\Serializer\Annotation\SerializedName("phone")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $phone = null;

    /**
     * @var ?string $activity
     * @JMS\Serializer\Annotation\SerializedName("activity")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $activity = null;

    /**
     * @var int|null $typeId
     * @JMS\Serializer\Annotation\SerializedName("type_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $typeId;

    /**
     * @var CompanyType|null $companyType
     * @JMS\Serializer\Annotation\SerializedName("company_type")
     * @JMS\Serializer\Annotation\Type("App\Domains\CompanyTypes\Models\CompanyType")
     */
    private ?CompanyType $companyType;

    /**
     * @var int|null $sourceId
     * @JMS\Serializer\Annotation\SerializedName("source_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $sourceId;

    /**
     * @var CompanySource|null $companySource
     * @JMS\Serializer\Annotation\SerializedName("company_source")
     * @JMS\Serializer\Annotation\Type("App\Domains\CompanySource\Models\CompanySource")
     */
    private ?CompanySource $companySource = null;

    /**
     * @var string|null $website
     * @JMS\Serializer\Annotation\SerializedName("website")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $website = null;

    /**
     * @var string $linkedin
     * @JMS\Serializer\Annotation\SerializedName("linkedin")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $linkedin = null;

    /**
     * @var ?int $countryId
     * @JMS\Serializer\Annotation\SerializedName("country_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $countryId = null;

    /**
     * @var CountryCode|null $country
     * @JMS\Serializer\Annotation\SerializedName("country")
     * @JMS\Serializer\Annotation\Type("App\Domains\CountryCodes\Models\CountryCode")
     */
    private ?CountryCode $country = null;

    /**
     * @var string $city
     * @JMS\Serializer\Annotation\SerializedName("city")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $city = null;

    /**
     * @var User[] $users
     * @JMS\Serializer\Annotation\SerializedName("users")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Auth\Models\User>")
     */
    private array $users = [];

    /**
     * @var ?Client $client
     * @JMS\Serializer\Annotation\SerializedName("client")
     * @JMS\Serializer\Annotation\Type("App\Domains\Clients\Models\Client")
     */
    private ?Client $client = null;

    /**
     * @var ?Lead $lead
     * @JMS\Serializer\Annotation\SerializedName("lead")
     * @JMS\Serializer\Annotation\Type("App\Domains\Leads\Models\Lead")
     */
    private ?Lead $lead = null;

    /**
     * @var Ticket[] $tickets
     * @JMS\Serializer\Annotation\SerializedName("tickets")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Tickets\Models\Ticket>")
     */
    private array $tickets;

    /**
     * @var ExtraData[] $extraData
     * @Serializer\SerializedName("extra_data")
     * @Serializer\Type("array<App\Domains\ExtraData\Models\ExtraData>")
     */
    private array $extraData = [];

    /**
     * @var int[] $extraDataIds
     * @Serializer\SerializedName("extra_data_ids")
     * @Serializer\Type("array<int>")
     */
    private array $extraDataIds = [];

    /**
     * @var File[]|null $files
     * @Serializer\SerializedName("files")
     * @Serializer\Type("array<App\Domains\Files\Models\File>")
     */
    private ?array $files = null;

    /**
     * @var Note[]|null $notes
     * @Serializer\SerializedName("notes")
     * @Serializer\Type("array<App\Domains\Notes\Models\Note>")
     */
    private ?array $notes = null;

    /**
     * @var array|null $morphables
     * @Serializer\SerializedName("morphables")
     * @Serializer\Type("enum<'App\Models\Enums\EloqMorphEnum'>")
     */
    private ?array $morphables = [EloqMorphEnum::NOTES, EloqMorphEnum::FILES];

    /**
     * Summary of getValues
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = true): array
    {
        $data = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'activity' => $this->getActivity(),
            'typeId' => $this->getTypeId(),
        ];

        if ($withRelations) {
            $data['companyType'] = $this->getCompanyType();
            $data['users'] = $this->getUsers();
        }

        return $data;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Company
    {
        $this->id = $id;
        return $this;
    }

    public function getErpId(): ?string
    {
        return $this->erpId;
    }

    public function setErpId(?string $erpId): Company
    {
        $this->erpId = $erpId;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Company
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Company
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): Company
    {
        $this->phone = $phone;
        return $this;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity(?string $activity): Company
    {
        $this->activity = $activity;
        return $this;
    }

    public function getTypeId(): ?int
    {
        return $this->typeId;
    }

    public function setTypeId(?int $typeId): Company
    {
        $this->typeId = $typeId;
        return $this;
    }

    public function getCompanyType(): ?CompanyType
    {
        return $this->companyType;
    }

    public function setCompanyType(?CompanyType $companyType): Company
    {
        $this->companyType = $companyType;
        return $this;
    }

    public function getSourceId(): ?int
    {
        return $this->sourceId;
    }

    public function setSourceId(?int $sourceId): Company
    {
        $this->sourceId = $sourceId;
        return $this;
    }

    public function getCompanySource(): ?CompanySource
    {
        return $this->companySource;
    }

    public function setCompanySource(?CompanySource $companySource): Company
    {
        $this->companySource = $companySource;
        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): Company
    {
        $this->website = $website;
        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): Company
    {
        $this->linkedin = $linkedin;
        return $this;
    }

    public function getCountryId(): ?int
    {
        return $this->countryId;
    }

    public function setCountryId(?int $countryId): Company
    {
        $this->countryId = $countryId;
        return $this;
    }

    public function getCountry(): ?CountryCode
    {
        return $this->country;
    }

    public function setCountry(?CountryCode $country): Company
    {
        $this->country = $country;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): Company
    {
        $this->city = $city;
        return $this;
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function setUsers(array $users): Company
    {
        $this->users = $users;
        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): Company
    {
        $this->client = $client;
        return $this;
    }

    public function getLead(): ?Lead
    {
        return $this->lead;
    }

    public function setLead(?Lead $lead): Company
    {
        $this->lead = $lead;
        return $this;
    }

    public function getTickets(): array
    {
        return $this->tickets;
    }

    public function setTickets(array $tickets): Company
    {
        $this->tickets = $tickets;
        return $this;
    }

    public function getExtraData(): array
    {
        return $this->extraData;
    }

    public function setExtraData(array $extraData): Company
    {
        $this->extraData = $extraData;
        return $this;
    }

    public function getExtraDataIds(): array
    {
        return $this->extraDataIds;
    }

    public function setExtraDataIds(array $extraDataIds): Company
    {
        $this->extraDataIds = $extraDataIds;
        return $this;
    }

    public function getFiles(): ?array
    {
        return $this->files;
    }

    public function setFiles(?array $files): Company
    {
        $this->files = $files;
        return $this;
    }

    public function getNotes(): ?array
    {
        return $this->notes;
    }

    public function setNotes(?array $notes): Company
    {
        $this->notes = $notes;
        return $this;
    }

    public function getMorphables(): ?array
    {
        return $this->morphables;
    }

    public function setMorphables(?array $morphables): Company
    {
        $this->morphables = $morphables;
        return $this;
    }


    /**
     * @param Request $request
     * @return Company
     */
    public static function fromRequest(Request $request): Company
    {
        $extraDataIds = isset($request['extra_data']) ? array_filter($request['extra_data'], fn($value) => $value !== null) : null;

        $companyDTO = new Company();
        return $companyDTO
            ->setName($request['name'] ?? null)
            ->setEmail($request['email'] ?? null)
            ->setPhone($request['phone'] ?? null)
            ->setActivity($request['activity'] ?? null)
            ->setTypeId($request['typeId'] ?? null)
            ->setSourceId($request['sourceId'] ?? null)
            ->setWebsite($request['website'] ?? null)
            ->setLinkedin($request['linkedin'] ?? null)
            ->setCountryId($request['countryId'] ?? null)
            ->setCity($request['city'] ?? null)
            ->setExtraDataIds($extraDataIds ?? []);


    }
}
