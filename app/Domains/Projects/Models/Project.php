<?php
declare(strict_types=1);

namespace App\Domains\Projects\Models;


use App\Domains\Auth\Models\User;
use App\Domains\Clients\Models\Client;
use App\Domains\Companies\Models\Company;
use App\Domains\Files\Models\File;
use App\Domains\Milestones\Models\Milestone;
use App\Domains\Notes\Models\Note;
use App\Domains\Projects\Enums\ProjectCategoryEnum;
use App\Domains\Projects\Enums\ProjectCategoryStatusEnum;
use App\Domains\Projects\Enums\ProjectPriorityEnum;
use App\Domains\Tags\Models\Tag;
use App\Domains\Tickets\Models\Ticket;
use App\Models\CactusEntity;
use App\Models\Enums\EloqMorphEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DateTime;
use JMS\Serializer\Annotation as Serializer;

class Project extends CactusEntity
{
    /**
     * @var $id
     * @Serializer\SerializedName("id")
     * @Serializer\Type("string")
     */
    private $id;

    /**
     * @var string $name
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    private string $name;

    /**
     * @var string|null $description
     * @Serializer\SerializedName("description")
     * @Serializer\Type("string")
     */
    private ?string $description = null;

    /**
     * @var float|null $salesCost
     * @Serializer\SerializedName("sales_cost")
     * @Serializer\Type("float")
     */
    private ?float $salesCost = null;

    /**
     * @var DateTime|null $startDate
     * @Serializer\SerializedName("start_date")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    private ?DateTime $startDate = null;

    /**
     * @var DateTime|null $deadline
     * @Serializer\SerializedName("deadline")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    private ?DateTime $deadline = null;


    /**
     * @var DateTime|null $estDate
     * @Serializer\SerializedName("est_date")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    private ?DateTime $estDate = null;

    /**
     * @var string|null $googleDrive
     * @Serializer\SerializedName("google_drive")
     * @Serializer\Type("string")
     */
    private ?string $googleDrive = null;

    /**
     * @var ProjectPriorityEnum|null $priority
     * @Serializer\SerializedName("priority")
     * @Serializer\Type("enum<'App\Domains\Projects\Enums\ProjectPriorityEnum'>")
     */
    private ?ProjectPriorityEnum $priority = null;

    /**
     * @var int|null $estTime
     * @Serializer\SerializedName("est_time")
     * @Serializer\Type("int")
     */
    private ?int $estTime = null;


    /**
     * @var array|null $estTimeArray
     * @Serializer\SerializedName("est_time_array")
     * @Serializer\Type("array")
     */
    private ?array $estTimeArray = null;


    /**
     * @var string $typeId
     * @Serializer\SerializedName("type_id")
     * @Serializer\Type("string")
     */
    private string $typeId;

    /**
     * @var ProjectType $type
     * @Serializer\SerializedName("type")
     * @Serializer\Type("App\Domains\Projects\Models\ProjectType")
     */
    private ProjectType $type;

    /**
     * @var ?string $ownerId
     * @Serializer\SerializedName("owner_id")
     * @Serializer\Type("string")
     */
    private ?string $ownerId;

    /**
     * @var ?User $owner
     * @Serializer\SerializedName("owner")
     * @Serializer\Type("App\Domains\Auth\Models\User")
     */
    private ?User $owner;

    /**
     * @var ?string $createdBy
     * @Serializer\SerializedName("created_by")
     * @Serializer\Type("string")
     */
    private ?string $createdBy = null;

    /**
     * @var ?User $createdByUser
     * @Serializer\SerializedName("created_by_user")
     * @Serializer\Type("App\Domains\Auth\Models\User")
     */
    private ?User $createdByUser = null;

    /**
     * @var ProjectCategoryEnum|null $category
     * @Serializer\SerializedName("category")
     * @Serializer\Type("enum<'App\Domains\Projects\Enums\ProjectCategoryEnum'>")
     */
    private ?ProjectCategoryEnum $category = null;

    /**
     * @var ProjectCategoryStatusEnum|null $categoryStatus
     * @Serializer\SerializedName("category_status")
     * @Serializer\Type("enum<'App\Domains\Projects\Enums\ProjectCategoryStatusEnum'>")
     */
    private ?ProjectCategoryStatusEnum $categoryStatus = null;

    /**
     * @var User[]|null $assignees
     * @Serializer\SerializedName("assignees")
     * @Serializer\Type("array<App\Domains\Auth\Models\User>")
     */
    private ?array $assignees = null;

    /**
     * @var Milestone[]|null $milestones
     * @Serializer\SerializedName("milestones")
     * @Serializer\Type("array<App\Domains\Milestones\Models\Milestone>")
     */
    private ?array $milestones = null;


    /**
     * @var string|null $clientId
     * @Serializer\SerializedName("client_id")
     * @Serializer\Type("string")
     */
    private ?string $clientId;

    /**
     * @var Client|null $client
     * @Serializer\SerializedName("client")
     * @Serializer\Type("App\Domains\Clients\Models\Client")
     */
    private ?Client $client;

    /**
     * @var string|null $companyId
     * @Serializer\SerializedName("company_id")
     * @Serializer\Type("string")
     */
    private ?string $companyId;

    /**
     * @var Company|null $company
     * @Serializer\SerializedName("company")
     * @Serializer\Type("App\Domains\Companies\Models\Company")
     */
    private ?Company $company;

    /**
     * @var ProjectStatus[] $projectStatus
     * @Serializer\SerializedName("status")
     * @Serializer\Type("array<App\Domains\Projects\Models\ProjectStatus>")
     */
    private array $projectStatus;


    /**
     * @var Note[]|null $notes
     * @Serializer\SerializedName("notes")
     * @Serializer\Type("array<App\Domains\Notes\Models\Note>")
     */
    private ?array $notes = null;


    /**
     * @var File[]|null $files
     * @Serializer\SerializedName("files")
     * @Serializer\Type("array<App\Domains\Files\Models\File>")
     */
    private ?array $files = null;

    /**
     * @var Tag[]|null $tags
     * @Serializer\SerializedName("tags")
     * @Serializer\Type("array<App\Domains\Tags\Models\Tag>")
     */
    private ?array $tags = null;

    /**
     * @var Ticket[]|null $tags
     * @Serializer\SerializedName("tickets")
     * @Serializer\Type("array<App\Domains\Tickets\Models\Ticket>")
     */
    private ?array $tickets = null;


    /**
     * @var ProjectStatus $activeStatus
     * @Serializer\SerializedName("active_status")
     * @Serializer\Type("App\Domains\Projects\Models\ProjectStatus")
     */
    private ProjectStatus $activeStatus;

    /**
     * @var array|null $morphables
     * @Serializer\SerializedName("morphables")
     * @Serializer\Type("enum<'App\Models\Enums\EloqMorphEnum'>")
     */
    private ?array $morphables = [EloqMorphEnum::FILES, EloqMorphEnum::NOTES, EloqMorphEnum::ASSIGNEES,   EloqMorphEnum::TICKETS,
//        EloqMorphEnum::MILESTONES,
//        EloqMorphEnum::TAGS
    ];


    /**
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = false): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->startDate,
            'deadline' => $this->deadline,
            'sales_cost' => $this->salesCost,
            'est_time' => $this->estTime,
            'est_date' => $this->estDate,
            'google_drive' => $this->googleDrive,
            'priority' => $this->priority->value,
            'type_id' => $this->typeId,
            'client_id' => $this->clientId,
            'company_id' => $this->companyId,
            'owner_id' => $this->ownerId,
            'created_by' => $this->createdBy,
            'active_status_id' => $this->activeStatus->getId(),
            'active_status' => $this->activeStatus->getName()
        ];

        if($withRelations){
            $data['type'] = $this->getType();
            $data['owner'] = $this->getOwner();
            $data['createdByUser'] = $this->getCreatedByUser();
            $data['client'] = $this->getClient();
            $data['company'] = $this->getCompany();
            $data['status'] = $this->getProjectStatus();
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Project
     */
    public function setId(string $id): Project
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Project
     */
    public function setName(string $name): Project
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Project
     */
    public function setDescription(?string $description): Project
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getSalesCost(): ?float
    {
        return $this->salesCost;
    }

    /**
     * @param float|null $salesCost
     * @return Project
     */
    public function setSalesCost(?float $salesCost): Project
    {
        $this->salesCost = $salesCost;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime|null $startDate
     * @return Project
     */
    public function setStartDate(?DateTime $startDate): Project
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDeadline(): ?DateTime
    {
        return $this->deadline;
    }

    /**
     * @param DateTime|null $deadline
     * @return Project
     */
    public function setDeadline(?DateTime $deadline): Project
    {
        $this->deadline = $deadline;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getEstDate(): ?DateTime
    {
        return $this->estDate;
    }

    /**
     * @param DateTime|null $estDate
     * @return Project
     */
    public function setEstDate(?DateTime $estDate): Project
    {
        $this->estDate = $estDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGoogleDrive(): ?string
    {
        return $this->googleDrive;
    }

    /**
     * @param string|null $googleDrive
     * @return Project
     */
    public function setGoogleDrive(?string $googleDrive): Project
    {
        $this->googleDrive = $googleDrive;
        return $this;
    }

    /**
     * @return ProjectPriorityEnum|null
     */
    public function getPriority(): ?ProjectPriorityEnum
    {
        return $this->priority;
    }

    /**
     * @param ProjectPriorityEnum|null $priority
     * @return Project
     */
    public function setPriority(?ProjectPriorityEnum $priority): Project
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getEstTime(): ?int
    {
        return $this->estTime;
    }

    /**
     * @param int|null $estTime
     * @return Project
     */
    public function setEstTime(?int $estTime): Project
    {
        $this->estTime = $estTime;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getEstTimeArray(): ?array
    {
        return $this->estTimeArray;
    }

    /**
     * @param array|null $estTimeArray
     * @return Project
     */
    public function setEstTimeArray(?array $estTimeArray): Project
    {
        $this->estTimeArray = $estTimeArray;
        return $this;
    }

    /**
     * @return string
     */
    public function getTypeId(): string
    {
        return $this->typeId;
    }

    /**
     * @param string $typeId
     * @return Project
     */
    public function setTypeId(string $typeId): Project
    {
        $this->typeId = $typeId;
        return $this;
    }

    /**
     * @return ProjectType
     */
    public function getType(): ProjectType
    {
        return $this->type;
    }

    /**
     * @param ProjectType $type
     * @return Project
     */
    public function setType(ProjectType $type): Project
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOwnerId(): ?string
    {
        return $this->ownerId;
    }

    /**
     * @param string|null $ownerId
     * @return Project
     */
    public function setOwnerId(?string $ownerId): Project
    {
        $this->ownerId = $ownerId;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * @param User|null $owner
     * @return Project
     */
    public function setOwner(?User $owner): Project
    {
        $this->owner = $owner;
        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $createdBy): Project
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getCreatedByUser(): ?User
    {
        return $this->createdByUser;
    }

    public function setCreatedByUser(?User $createdByUser): Project
    {
        $this->createdByUser = $createdByUser;
        return $this;
    }

    public function getCategory(): ?ProjectCategoryEnum
    {
        return $this->category;
    }

    public function setCategory(?ProjectCategoryEnum $category): Project
    {
        $this->category = $category;
        return $this;
    }

    public function getCategoryStatus(): ?ProjectCategoryStatusEnum
    {
        return $this->categoryStatus;
    }

    public function setCategoryStatus(?ProjectCategoryStatusEnum $categoryStatus): Project
    {
        $this->categoryStatus = $categoryStatus;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getAssignees(): ?array
    {
        return $this->assignees;
    }

    /**
     * @param array|null $assignees
     * @return Project
     */
    public function setAssignees(?array $assignees): Project
    {
        $this->assignees = $assignees;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getMilestones(): ?array
    {
        return $this->milestones;
    }

    /**
     * @param array|null $milestones
     * @return Project
     */
    public function setMilestones(?array $milestones): Project
    {
        $this->milestones = $milestones;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    /**
     * @param string|null $clientId
     * @return Project
     */
    public function setClientId(?string $clientId): Project
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @param Client|null $client
     * @return Project
     */
    public function setClient(?Client $client): Project
    {
        $this->client = $client;
        return $this;
    }

    public function getCompanyId(): ?string
    {
        return $this->companyId;
    }

    public function setCompanyId(?string $companyId): Project
    {
        $this->companyId = $companyId;
        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): Project
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return array
     */
    public function getProjectStatus(): array
    {
        return $this->projectStatus;
    }

    /**
     * @param array $projectStatus
     * @return Project
     */
    public function setProjectStatus(array $projectStatus): Project
    {
        $this->projectStatus = $projectStatus;
        return $this;
    }

    /**
     * @return ProjectStatus
     */
    public function getActiveStatus(): ProjectStatus
    {
        return $this->activeStatus;
    }

    /**
     * @param ProjectStatus $activeStatus
     * @return Project
     */
    public function setActiveStatus(ProjectStatus $activeStatus): Project
    {
        $this->activeStatus = $activeStatus;
        return $this;
    }

    /**
     * @param $value
     * @return ProjectPriorityEnum|null
     */
    public function getPriorityAttribute($value): ?ProjectPriorityEnum
    {
        return ProjectPriorityEnum::from($value);  // Enum from string
    }

    /**
     * @param string|null $value
     * @return Project|null
     */
    public function setPriorityAttribute(?string $value): ?Project
    {
        $this->setPriority(ProjectPriorityEnum::from($value));// Enum from string
        return $this;
    }

    /**
     * @return array|null
     */
    public function getNotes(): ?array
    {
        return $this->notes;
    }

    /**
     * @param array|null $notes
     * @return Project
     */
    public function setNotes(?array $notes): Project
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getFiles(): ?array
    {
        return $this->files;
    }

    /**
     * @param array|null $files
     * @return Project
     */
    public function setFiles(?array $files): Project
    {
        $this->files = $files;
        return $this;
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
     * @return Project
     */
    public function setMorphables(?array $morphables): Project
    {
        $this->morphables = $morphables;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    /**
     * @param array|null $tags
     * @return Project
     */
    public function setTags(?array $tags): Project
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getTickets(): ?array
    {
        return $this->tickets;
    }

    /**
     * @param array|null $tickets
     * @return Project
     */
    public function setTickets(?array $tickets): Project
    {
        $this->tickets = $tickets;
        return $this;
    }

    /**
     * @param Request $request
     * @return Project
     */
    public static function fromRequest(Request $request): Project
    {
        $projectDTO = new Project();

        return $projectDTO
            ->setName($request['name'])
            ->setDescription($request['description'])
            ->setStartDate($request['start_date'] ? Carbon::createFromFormat('d/m/Y',$request['start_date']) : null)
            ->setDeadline($request['deadline'] ? Carbon::createFromFormat('d/m/Y',$request['deadline']) : null)
            ->setEstDate($request['est_date'] ? Carbon::createFromFormat('d/m/Y',$request['est_date']) : null)
            ->setSalesCost((float)$request['sales_cost'])
            ->setGoogleDrive($request['google_drive'])
            ->setPriorityAttribute($request['priority'])
            ->setEstTime($request['est_time'])
            ->setOwnerId($request['owner_id'])
            ->setCreatedBy((string) \Auth::user()->id ?? null)
            ->setClientId($request['client_id'])
            ->setCompanyId($request['company_id'])
            ->setCategory($request['category'] ? ProjectCategoryEnum::from($request['category']) : null)
            ->setCategoryStatus($request['category_status'] ? ProjectCategoryStatusEnum::from((string)$request['category_status']) : null);

    }

    /**
     * @return array|null
     */
    public static function morphBuilder(): ?array
    {
        $project = new Project();
        return $project->getMorphables();
    }
}
