<?php

namespace App\Domains\Leads\Models;

use App\Domains\Auth\Models\User;
use App\Domains\ExtraData\Models\ExtraData;
use App\Domains\Files\Models\File;
use App\Domains\Notes\Models\Note;
use App\Models\Enums\EloqMorphEnum;
use Carbon\Carbon;
use DateTime;
use App\Models\CactusEntity;
use App\Domains\Companies\Models\Company;
use Illuminate\Http\Request;
use JMS\Serializer\Annotation as Serializer;

//use App\Domains\CompanyTypes\Models\Companies;

class Lead extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var ?int $companyId
     * @JMS\Serializer\Annotation\SerializedName("company_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $companyId = null;

    /**
     * @var ?Company $company
     * @JMS\Serializer\Annotation\SerializedName("company")
     * @JMS\Serializer\Annotation\Type("App\Domains\Companies\Models\Company")
     */
    private ?Company $company = null;

    /**
     * @var ?int $salesPersonId
     * @JMS\Serializer\Annotation\SerializedName("sales_person_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $salesPersonId = null;

    /**
     * @var ?User $salesPerson
     * @JMS\Serializer\Annotation\SerializedName("sales_person")
     * @JMS\Serializer\Annotation\Type("App\Domains\Auth\Models\User")
     */
    private ?User $salesPerson = null;

    /**
     * @var File[]|null $files
     * @Serializer\SerializedName("files")
     * @Serializer\Type("array<App\Domains\Files\Models\File>")
     */
    private ?array $files = null;

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
            'companyId' => $this->getCompanyId(),
        ];

        if ($withRelations) {
            $data['company'] = $this->company;
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
    public function setId(int $id): Lead
    {
        $this->id = $id;
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
    public function setCompanyId(?int $companyId): Lead
    {
        $this->companyId = $companyId;
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
    public function setCompany(?Company $company): Lead
    {
        $this->company = $company;
        return $this;
    }

    public function getSalesPersonId(): ?int
    {
        return $this->salesPersonId;
    }

    public function setSalesPersonId(?int $salesPersonId): Lead
    {
        $this->salesPersonId = $salesPersonId;
        return $this;
    }

    public function getSalesPerson(): ?User
    {
        return $this->salesPerson;
    }

    public function setSalesPerson(?User $salesPerson): Lead
    {
        $this->salesPerson = $salesPerson;
        return $this;
    }

    public function getFiles(): ?array
    {
        return $this->files;
    }

    public function setFiles(?array $files): Lead
    {
        $this->files = $files;
        return $this;
    }

    public function getExtraData(): array
    {
        return $this->extraData;
    }

    public function setExtraData(array $extraData): Lead
    {
        $this->extraData = $extraData;
        return $this;
    }

    public function getExtraDataIds(): array
    {
        return $this->extraDataIds;
    }

    public function setExtraDataIds(array $extraDataIds): Lead
    {
        $this->extraDataIds = $extraDataIds;
        return $this;
    }

    public function getNotes(): ?array
    {
        return $this->notes;
    }

    public function setNotes(?array $notes): Lead
    {
        $this->notes = $notes;
        return $this;
    }



    /**
     * @param Request $request
     * @return Lead
     */
    public function fromRequest(Request $request): Lead
    {
        $lead = new self();
        return $lead
            ->setCompanyId(companyId: $request['leadCompanyId']) //needed for update
            ->setSalesPersonId(salesPersonId: $request['salesPersonId']);

    }


    /**
     * @return array|null
     */
    public function getMorphables(): ?array
    {
        return $this->morphables;
    }

    /**
     * @param array|null $morphables
     * @return Lead
     */
    public function setMorphables(?array $morphables): Lead
    {
        $this->morphables = $morphables;
        return $this;
    }

    /**
     * @return array|null
     */
    public static function morphBuilder(): ?array
    {
        $lead = new Lead();
        return $lead->getMorphables();
    }

    public function getLeadSectionData(): array
    {
        return $this->leadSectionData;
    }

    public function setLeadSectionData(array $leadSectionData): Lead
    {
        $this->leadSectionData = $leadSectionData;
        return $this;
    }

    public function getLeadSectionComments(): array
    {
        return $this->leadSectionComments;
    }

    public function setLeadSectionComments(array $leadSectionComments): Lead
    {
        $this->leadSectionComments = $leadSectionComments;
        return $this;
    }




}
