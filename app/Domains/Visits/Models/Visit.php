<?php

namespace App\Domains\Visits\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Companies\Models\Company;
use App\Domains\Files\Models\File;
use App\Domains\Notes\Models\Note;
use App\Domains\Visits\Enums\VisitNextActionSourceEnum;
use App\Domains\Visits\Enums\VisitProductDiscussedSourceEnum;
use App\Domains\Visits\Enums\VisitTypeSourceEnum;
use App\Helpers\Enums\PriorityEnum;
use App\Models\CactusEntity;
use App\Models\Enums\EloqMorphEnum;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use JMS\Serializer\Annotation as Serializer;

class Visit extends CactusEntity
{
    /**
     * @var int $id
     * @Serializer\SerializedName("id")
     * @Serializer\Type("int")
     */
    private int $id;

    /**
     * @var string $name
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    private string $name;

    /**
     * @var DateTime|null $deadline
     * @Serializer\SerializedName("deadline")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    private ?DateTime $deadline = null;

    /**
     * @var PriorityEnum|null $priority
     * @Serializer\SerializedName("priority")
     * @Serializer\Type("enum<'App\Helpers\Enums\PriorityEnum'>")
     */
    private ?PriorityEnum $priority = null;

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
     * @var DateTime|null $visitDate
     * @Serializer\SerializedName("visit_date")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    private ?DateTime $visitDate = null;

    /**
     * @var VisitTypeSourceEnum|null $visitType
     * @Serializer\SerializedName("visit_type")
     * @Serializer\Type("enum<'App\Domains\Visits\Enums\VisitTypeSourceEnum'>")
     */
    private ?VisitTypeSourceEnum $visitType = null;

    /**
     * @var string|null $outcome
     * @Serializer\SerializedName("outcome")
     * @Serializer\Type("string")
     */
    private ?string $outcome = null;

    /**
     * @var VisitProductDiscussedSourceEnum|null $productsDiscussed
     * @Serializer\SerializedName("products_discussed")
     * @Serializer\Type("enum<'App\Domains\Visits\Enums\VisitProductDiscussedSourceEnum'>")
     */
    private ?VisitProductDiscussedSourceEnum $productsDiscussed = null;

    /**
     * @var VisitNextActionSourceEnum|null $nextAction
     * @Serializer\SerializedName("next_action")
     * @Serializer\Type("enum<'App\Domains\Visits\Enums\VisitNextActionSourceEnum'>")
     */
    private ?VisitNextActionSourceEnum $nextAction = null;

    /**
     * @var string|null $nextActionComment
     * @JMS\Serializer\Annotation\SerializedName("next_action_comment")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $nextActionComment = null;

    /**
     * @var User[]|null $assignees
     * @Serializer\SerializedName("assignees")
     * @Serializer\Type("array<App\Domains\Auth\Models\User>")
     */
    private ?array $assignees = [];

    /**
     * @var VisitStatus[] $visitStatuses
     * @Serializer\SerializedName("status")
     * @Serializer\Type("array<App\Domains\Visits\Models\visitStatus>")
     */
    private array $visitStatuses = [];

    /**
     * @var VisitStatus|null $activeStatus
     * @Serializer\SerializedName("active_status")
     * @Serializer\Type("App\Domains\Visits\Models\VisitStatus")
     */
    private ?VisitStatus $activeStatus = null;

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
     * @var User[] $contacts
     * @JMS\Serializer\Annotation\SerializedName("contacts")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Auth\Models\User>")
     */
    private array $contacts = [];

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
    public function getValues(bool $withRelations = false): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'visit_date' => $this->visitDate,
            'visit_type' => $this->visitType,
            'outcome' => $this->outcome,
            'products_discussed' => $this->productsDiscussed,
            'next_action' => $this->nextAction,
            'active_status' => $this->activeStatus,
        ];

        if($withRelations){
            $data['owner'] = $this->getOwner();
            $data['company'] = $this->getCompany();
        }

        return $data;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Visit
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Visit
    {
        $this->name = $name;
        return $this;
    }

    public function getDeadline(): ?DateTime
    {
        return $this->deadline;
    }

    public function setDeadline(?DateTime $deadline): Visit
    {
        $this->deadline = $deadline;
        return $this;
    }

    public function getPriority(): ?PriorityEnum
    {
        return $this->priority;
    }

    public function setPriority(?PriorityEnum $priority): Visit
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * @param $value
     * @return PriorityEnum|null
     */
    public function getPriorityAttribute($value): ?PriorityEnum
    {
        return PriorityEnum::from($value);  // Enum from string
    }

    /**
     * @param string|null $value
     * @return Visit|null
     */
    public function setPriorityAttribute(?string $value): ?Visit
    {
        $this->setPriority($value ? PriorityEnum::from($value) : null);// Enum from string
        return $this;
    }

    public function getOwnerId(): ?string
    {
        return $this->ownerId;
    }

    public function setOwnerId(?string $ownerId): Visit
    {
        $this->ownerId = $ownerId;
        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): Visit
    {
        $this->owner = $owner;
        return $this;
    }

    public function getCompanyId(): ?string
    {
        return $this->companyId;
    }

    public function setCompanyId(?string $companyId): Visit
    {
        $this->companyId = $companyId;
        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): Visit
    {
        $this->company = $company;
        return $this;
    }

    public function getVisitDate(): ?DateTime
    {
        return $this->visitDate;
    }

    public function setVisitDate(?DateTime $visitDate): Visit
    {
        $this->visitDate = $visitDate;
        return $this;
    }

    public function getVisitType(): ?VisitTypeSourceEnum
    {
        return $this->visitType;
    }

    public function setVisitType(?VisitTypeSourceEnum $visitType): Visit
    {
        $this->visitType = $visitType;
        return $this;
    }

    public function setVisitTypeAttribute(?string $value): ?Visit
    {
        $this->setVisitType($value ? VisitTypeSourceEnum::from($value) : null);
        return $this;
    }

    public function getOutcome(): ?string
    {
        return $this->outcome;
    }

    public function setOutcome(?string $outcome): Visit
    {
        $this->outcome = $outcome;
        return $this;
    }

    public function getProductsDiscussed(): ?VisitProductDiscussedSourceEnum
    {
        return $this->productsDiscussed;
    }

    public function setProductsDiscussed(?VisitProductDiscussedSourceEnum $productsDiscussed): Visit
    {
        $this->productsDiscussed = $productsDiscussed;
        return $this;
    }

    public function setProductsDiscussedAttribute(?string $value): ?Visit
    {
        $this->setProductsDiscussed($value ? VisitProductDiscussedSourceEnum::from($value) : null);
        return $this;
    }

    public function getNextAction(): ?VisitNextActionSourceEnum
    {
        return $this->nextAction;
    }

    public function setNextAction(?VisitNextActionSourceEnum $nextAction): Visit
    {
        $this->nextAction = $nextAction;
        return $this;
    }

    public function setNextActionAttribute(?string $value): ?Visit
    {
        $this->setNextAction($value ? VisitNextActionSourceEnum::from($value) : null);
        return $this;
    }

    public function getNextActionComment(): ?string
    {
        return $this->nextActionComment;
    }

    public function setNextActionComment(?string $nextActionComment): Visit
    {
        $this->nextActionComment = $nextActionComment;
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
     * @return Visit
     */
    public function setAssignees(?array $assignees): Visit
    {
        $this->assignees = $assignees;
        return $this;
    }

    public function getVisitStatuses(): array
    {
        return $this->visitStatuses;
    }

    public function setVisitStatuses(array $visitStatuses): Visit
    {
        $this->visitStatuses = $visitStatuses;
        return $this;
    }

    /**
     * @return ?VisitStatus
     */
    public function getActiveStatus(): ?VisitStatus
    {
        return $this->activeStatus;
    }

    /**
     * @param VisitStatus $activeStatus
     * @return Visit
     */
    public function setActiveStatus(VisitStatus $activeStatus): Visit
    {
        $this->activeStatus = $activeStatus;
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
     * @return Visit
     */
    public function setNotes(?array $notes): Visit
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
     * @return Visit
     */
    public function setFiles(?array $files): Visit
    {
        $this->files = $files;
        return $this;
    }

    public function getContacts(): array
    {
        return $this->contacts;
    }

    public function setContacts(?array $contacts = []): Visit
    {
        if(!$contacts) {
            $contacts = [];
        }
        $this->contacts = $contacts;
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
     * @return Visit
     */
    public function setMorphables(?array $morphables): Visit
    {
        $this->morphables = $morphables;
        return $this;
    }

    /**
     * @return array|null
     */
    public static function morphBuilder(): ?array
    {
        $visit = new Visit();
        return $visit->getMorphables();
    }

    /**
     * @param Request $request
     * @return Visit
     */
    public static function fromRequest(Request $request): Visit
    {
        $visitDTO = new Visit();

        return $visitDTO
            ->setName($request['name'])
            ->setDeadline($request['deadline'] ?  Carbon::parse($request['deadline']) : null)
            ->setPriorityAttribute($request['priority'])
            ->setOwnerId($request['owner_id'])
            ->setCompanyId($request['company_id'])
            ->setVisitDate($request['visit_date'] ? Carbon::parse($request['visit_date']) : null)
            ->setVisitTypeAttribute($request['visit_type'])
            ->setOutcome($request['outcome'])
            ->setProductsDiscussedAttribute($request['products_discussed'])
            ->setNextActionAttribute($request['next_action'])
            ->setNextActionComment($request['next_action_comment'])
           ;
    }

}
