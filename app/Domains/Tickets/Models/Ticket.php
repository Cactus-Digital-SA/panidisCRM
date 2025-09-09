<?php

namespace App\Domains\Tickets\Models;


use App\Domains\Auth\Models\User;
use App\Domains\Companies\Models\Company;
use App\Domains\Files\Models\File;
use App\Domains\Notes\Models\Note;
use App\Domains\Tickets\Enums\TicketSourceEnum;
use App\Domains\Visits\Enums\VisitNextActionSourceEnum;
use App\Domains\Visits\Enums\VisitProductDiscussedSourceEnum;
use App\Domains\Visits\Enums\VisitTypeSourceEnum;
use App\Helpers\Enums\ActionTypesEnum;
use App\Helpers\Enums\PriorityEnum;
use App\Models\CactusEntity;
use App\Models\Enums\EloqMorphEnum;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class Ticket extends CactusEntity
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
     * @var TicketSourceEnum|null $source
     * @Serializer\SerializedName("source")
     * @Serializer\Type("enum<'App\Domains\Tickets\Enums\TicketSourceEnum'>")
     */
    private ?TicketSourceEnum $source = null;

    /**
     * @var bool|null $public
     * @Serializer\SerializedName("public")
     * @Serializer\Type("bool")
     */
    private ?bool $public = false;

    /**
     * @var bool|null $billable
     * @Serializer\SerializedName("billable")
     * @Serializer\Type("bool")
     */
    private ?bool $billable = false;

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
     * @var User[]|null $assignees
     * @Serializer\SerializedName("assignees")
     * @Serializer\Type("array<App\Domains\Auth\Models\User>")
     */
    private ?array $assignees = null;

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
     * @var TicketStatus[] $projectStatus
     * @Serializer\SerializedName("status")
     * @Serializer\Type("array<App\Domains\Tickets\Models\TicketStatus>")
     */
    private array $ticketStatuses;

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
     * @var TicketStatus|null $activeStatus
     * @Serializer\SerializedName("active_status")
     * @Serializer\Type("App\Domains\Tickets\Models\TicketStatus")
     */
    private ?TicketStatus $activeStatus = null;

    /**
     * @var Ticket[]|null $blockedByTickets
     * @Serializer\SerializedName("blocked_by")
     * @Serializer\Type("array<App\Domains\Tickets\Models\Ticket>")
     */
    private ?array $blockedByTickets = null;

    /**
     * @var Ticket[]|null $blockingTickets
     * @Serializer\SerializedName("blocking_tickets")
     * @Serializer\Type("array<App\Domains\Tickets\Models\Ticket>")
     */
    private ?array $blockingTickets = null;

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
    private ?array $morphables = [EloqMorphEnum::NOTES, EloqMorphEnum::FILES, EloqMorphEnum::ASSIGNEES];

    /**
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = false): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'deadline' => $this->deadline,
            'est_time' => $this->estTime,
            'priority' => $this->priority->value,
            'type_id' => $this->typeId,
            'company_id' => $this->companyId,
            'owner_id' => $this->ownerId,
            'active_status_id' => $this->activeStatus->getId(),
            'active_status' => $this->activeStatus->getName()
        ];

        if($withRelations){
            $data['owner'] = $this->getOwner();
            $data['company'] = $this->getCompany();
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Ticket
     */
    public function setId($id)
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
     * @return Ticket
     */
    public function setName(string $name): Ticket
    {
        $this->name = $name;
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
     * @return Ticket
     */
    public function setDeadline(?DateTime $deadline): Ticket
    {
        $this->deadline = $deadline;
        return $this;
    }

    /**
     * @return PriorityEnum|null
     */
    public function getPriority(): ?PriorityEnum
    {
        return $this->priority;
    }

    /**
     * @param PriorityEnum|null $priority
     * @return Ticket
     */
    public function setPriority(?PriorityEnum $priority): Ticket
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * @return TicketSourceEnum|null
     */
    public function getSource(): ?TicketSourceEnum
    {
        return $this->source;
    }

    /**
     * @param TicketSourceEnum|null $source
     * @return Ticket
     */
    public function setSource(?TicketSourceEnum $source): Ticket
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getPublic(): ?bool
    {
        return $this->public;
    }

    /**
     * @param bool|null $public
     * @return Ticket
     */
    public function setPublic(?bool $public): Ticket
    {
        $this->public = $public;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getBillable(): ?bool
    {
        return $this->billable;
    }

    /**
     * @param bool|null $billable
     * @return Ticket
     */
    public function setBillable(?bool $billable): Ticket
    {
        $this->billable = $billable;
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
     * @return Ticket
     */
    public function setEstTime(?int $estTime): Ticket
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
     * @return Ticket
     */
    public function setEstTimeArray(?array $estTimeArray): Ticket
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
     * @return Ticket
     */
    public function setTypeId(string $typeId): Ticket
    {
        $this->typeId = $typeId;
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
     * @return Ticket
     */
    public function setOwnerId(?string $ownerId): Ticket
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
     * @return Ticket
     */
    public function setOwner(?User $owner): Ticket
    {
        $this->owner = $owner;
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
     * @return Ticket
     */
    public function setAssignees(?array $assignees): Ticket
    {
        $this->assignees = $assignees;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyId(): ?string
    {
        return $this->companyId;
    }

    /**
     * @param string|null $companyId
     * @return Ticket
     */
    public function setCompanyId(?string $companyId): Ticket
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
     * @return Ticket
     */
    public function setCompany(?Company $company): Ticket
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return array
     */
    public function getTicketStatuses(): array
    {
        return $this->ticketStatuses;
    }

    /**
     * @param array $ticketStatuses
     * @return Ticket
     */
    public function setTicketStatuses(array $ticketStatuses): Ticket
    {
        $this->ticketStatuses = $ticketStatuses;
        return $this;
    }

    /**
     * @return ?TicketStatus
     */
    public function getActiveStatus(): ?TicketStatus
    {
        return $this->activeStatus;
    }

    /**
     * @param TicketStatus $activeStatus
     * @return Ticket
     */
    public function setActiveStatus(TicketStatus $activeStatus): Ticket
    {
        $this->activeStatus = $activeStatus;
        return $this;
    }

    public function getBlockedByTickets(): ?array
    {
        return $this->blockedByTickets;
    }

    public function setBlockedByTickets(?array $blockedByTickets): Ticket
    {
        $this->blockedByTickets = $blockedByTickets;
        return $this;
    }

    public function getBlockingTickets(): ?array
    {
        return $this->blockingTickets;
    }

    public function setBlockingTickets(?array $blockingTickets): Ticket
    {
        $this->blockingTickets = $blockingTickets;
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
     * @return Ticket|null
     */
    public function setPriorityAttribute(?string $value): ?Ticket
    {
        $this->setPriority($value ? PriorityEnum::from($value) : null);// Enum from string
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
     * @return Ticket
     */
    public function setNotes(?array $notes): Ticket
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
     * @return Ticket
     */
    public function setFiles(?array $files): Ticket
    {
        $this->files = $files;
        return $this;
    }

    public function getContacts(): array
    {
        return $this->contacts;
    }

    public function setContacts(array $contacts): Ticket
    {
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
     * @return Ticket
     */
    public function setMorphables(?array $morphables): Ticket
    {
        $this->morphables = $morphables;
        return $this;
    }

    /**
     * @param Request $request
     * @return Ticket
     */
    public static function fromRequest(Request $request): Ticket
    {
        $projectDTO = new Ticket();

        if(!$request['category']){
            $request['category_status'] = null;
        }

        return $projectDTO
            ->setName($request['name'])
            ->setDeadline($request['deadline'] ?  Carbon::parse($request['deadline']) : null)
            ->setPriorityAttribute($request['priority'])
            ->setEstTime($request['est_time'])
            ->setOwnerId($request['owner_id'])
            ->setCompanyId($request['company_id'])
           ;
    }

    /**
     * @return array|null
     */
    public static function morphBuilder(): ?array
    {
        $ticket = new Ticket();
        return $ticket->getMorphables();
    }
}
