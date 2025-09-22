<?php

namespace App\Domains\Clients\Models;

use App\Domains\Companies\Models\Company;
use App\Domains\ExtraData\Models\ExtraData;
use App\Domains\Files\Models\File;
use App\Domains\Leads\Models\Lead;
use App\Domains\Notes\Models\Note;
use App\Domains\Projects\Models\Project;
use App\Domains\Tags\Models\Tag;
use App\Models\CactusEntity;
use App\Models\Enums\EloqMorphEnum;
use App\Domains\Auth\Models\User;
use Illuminate\Http\Request;
use JMS\Serializer\Annotation as Serializer;

class Client extends CactusEntity
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
     * @var Project[]|null $projects
     * @Serializer\SerializedName("projects")
     * @Serializer\Type("array<App\Domains\Projects\Models\Project>")
     */
    private ?array $projects = null;

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
     * @var int[] $tagIds
     * @Serializer\SerializedName("tag_ids")
     * @Serializer\Type("array<int>")
     */
    private array $tagIds = [];

    /**
     * @var Tag[]|null $tags
     * @Serializer\SerializedName("tags")
     * @Serializer\Type("array<App\Domains\Tags\Models\Tag>")
     */
    private ?array $tags = null;

    /**
     * @var array|null $morphables
     * @Serializer\SerializedName("morphables")
     * @Serializer\Type("enum<'App\Models\Enums\EloqMorphEnum'>")
     */
    private ?array $morphables = [EloqMorphEnum::NOTES, EloqMorphEnum::FILES];

    /**
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = true): array
    {
        $data = [
            'id' => $this->getId(),
            'companyId' => $this->companyId,
        ];

        if ($withRelations) {
            $data['company'] = $this->getCompany();
            $data['projects'] = $this->projects;
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
    public function setId(int $id): Client
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
    public function setCompanyId(?int $companyId): Client
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
    public function setCompany(?Company $company): Client
    {
        $this->company = $company;
        return $this;
    }

    public function getSalesPersonId(): ?int
    {
        return $this->salesPersonId;
    }

    public function setSalesPersonId(?int $salesPersonId): Client
    {
        $this->salesPersonId = $salesPersonId;
        return $this;
    }

    public function getSalesPerson(): ?User
    {
        return $this->salesPerson;
    }

    public function setSalesPerson(?User $salesPerson): Client
    {
        $this->salesPerson = $salesPerson;
        return $this;
    }

    /**
     * @param array|null $projects
     * @return Client
     */
    public function setProjects(?array $projects): Client
    {
        $this->projects = $projects;
        return $this;
    }

    public function getExtraData(): array
    {
        return $this->extraData;
    }

    public function setExtraData(array $extraData): Client
    {
        $this->extraData = $extraData;
        return $this;
    }

    public function getExtraDataIds(): array
    {
        return $this->extraDataIds;
    }

    public function setExtraDataIds(array $extraDataIds): Client
    {
        $this->extraDataIds = $extraDataIds;
        return $this;
    }

    public function getFiles(): ?array
    {
        return $this->files;
    }

    public function setFiles(?array $files): Client
    {
        $this->files = $files;
        return $this;
    }

    public function getNotes(): ?array
    {
        return $this->notes;
    }

    public function setNotes(?array $notes): Client
    {
        $this->notes = $notes;
        return $this;
    }

    public function getTagIds(): array
    {
        return $this->tagIds;
    }

    public function setTagIds(array $tagIds): Client
    {
        $this->tagIds = $tagIds;
        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): Client
    {
        $this->tags = $tags;
        return $this;
    }

    public function getMorphables(): ?array
    {
        return $this->morphables;
    }

    public function setMorphables(?array $morphables): Client
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

    /**
     * @param Request $request
     * @return Client
     */
    public static function fromRequest(Request $request): Client
    {
        $client = new self();
        $client->setCompanyId($request['clientCompanyId']) //needed for update
                ->setSalesPersonId($request['salesPersonId']);


        return $client;
    }

}
