<?php
declare(strict_types=1);

namespace App\Domains\Tickets\Models;

use App\Models\CactusEntity;
use DateTime;
use JMS\Serializer\Annotation as Serializer;

class TicketsStatusesPivot extends CactusEntity
{
    /**
     * @var string $id
     * @Serializer\SerializedName("id")
     * @Serializer\Type("string")
     */
    private string $id;

    /**
     * @var string $ticketId
     * @Serializer\SerializedName("ticketId")
     * @Serializer\Type("string")
     */
    private string $ticketId;

    /**
     * @var string $ticketStatusId
     * @Serializer\SerializedName("ticketStatusId")
     * @Serializer\Type("string")
     */
    private string $ticketStatusId;

    /**
     * @var string $ticketStatusSlug
     * @Serializer\SerializedName("ticketStatusSlug")
     * @Serializer\Type("string")
     */
    private string $ticketStatusSlug;

    /**
     * @var DateTime|null $date
     * @Serializer\SerializedName("date")
     * @Serializer\Type("DateTime<'Y-m-d\TH:i:s.up'>")
     */
    private ?DateTime $date;

    /**
     * @var int $sort
     * @Serializer\SerializedName("sort")
     * @Serializer\Type("int")
     */
    private int $sort = 1;

    /**
     * @var ?Ticket $ticket
     * @Serializer\SerializedName("ticket")
     * @Serializer\Type("App\Domains\Tickets\Models\Ticket")
     */
    private ?Ticket $ticket;

    /**
     * @var ?TicketStatus $ticketStatus
     * @Serializer\SerializedName("ticket_status")
     * @Serializer\Type("App\Domains\Tickets\Models\TicketStatus")
     */
    private ?TicketStatus $ticketStatus;

    /**
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = false): array
    {
        $data = [
            'id' => $this->id,
            'ticketId' => $this->ticketId,
            'ticketStatusId' => $this->ticketStatusId,
            'ticketStatusSlug' => $this->ticketStatusSlug,
            'date' => $this->date,
            'sort' => $this->sort,
        ];

        if($withRelations){
            $data['ticket'] = $this->getTicket();
            $data['ticketStatus'] = $this->getTicketStatus();
        }

        return $data;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): TicketsStatusesPivot
    {
        $this->id = $id;
        return $this;
    }

    public function getTicketId(): string
    {
        return $this->ticketId;
    }

    public function setTicketId(string $ticketId): TicketsStatusesPivot
    {
        $this->ticketId = $ticketId;
        return $this;
    }

    public function getTicketStatusId(): string
    {
        return $this->ticketStatusId;
    }

    public function setTicketStatusId(string $ticketStatusId): TicketsStatusesPivot
    {
        $this->ticketStatusId = $ticketStatusId;
        return $this;
    }

    public function getTicketStatusSlug(): string
    {
        return $this->ticketStatusSlug;
    }

    public function setTicketStatusSlug(string $ticketStatusSlug): TicketsStatusesPivot
    {
        $this->ticketStatusSlug = $ticketStatusSlug;
        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): TicketsStatusesPivot
    {
        $this->date = $date;
        return $this;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): TicketsStatusesPivot
    {
        $this->sort = $sort;
        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): TicketsStatusesPivot
    {
        $this->ticket = $ticket;
        return $this;
    }

    public function getTicketStatus(): ?TicketStatus
    {
        return $this->ticketStatus;
    }

    public function setTicketStatus(?TicketStatus $ticketStatus): TicketsStatusesPivot
    {
        $this->ticketStatus = $ticketStatus;
        return $this;
    }

}
