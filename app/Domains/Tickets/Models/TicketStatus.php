<?php
declare(strict_types=1);

namespace App\Domains\Tickets\Models;

use App\Helpers\Enums\LabelEnum;
use App\Models\CactusEntity;
use Carbon\Carbon;
use JMS\Serializer\Annotation as Serializer;

class TicketStatus extends CactusEntity
{
    /**
     * @var string $id
     * @Serializer\SerializedName("id")
     * @Serializer\Type("string")
     */
    private string $id;

    /**
     * @var string $name
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    private string $name;

    /**
     * @var string $slug
     * @Serializer\SerializedName("slug")
     * @Serializer\Type("string")
     */
    private string $slug;

    /**
     * @var ?LabelEnum $label
     * @Serializer\SerializedName("label")
     * @Serializer\Type("enum<'App\Helpers\Enums\LabelEnum'>")
     */
    private ?LabelEnum $label;

    /**
     * @var int $sort
     * @Serializer\SerializedName("sort")
     * @Serializer\Type("int")
     */
    private int $sort;

    /**
     * @var bool $visibility
     * @Serializer\SerializedName("visibility")
     * @Serializer\Type("bool")
     */
    private bool $visibility;

    /**
     * @var array|null $pivot
     * @Serializer\SerializedName("pivot")
     * @Serializer\Type("array")
     */
    private ?array $pivot = null;

    /**
     * @var ?Ticket[] $tickets
     * @Serializer\SerializedName("tickets")
     * @Serializer\Type("array<App\Domains\Tickets\Models\Ticket>")
     */
    private ?array $tickets;

    /**
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = false): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'label' => $this->label->value,
            'sort' => $this->sort,
            'visibility' => $this->visibility,
        ];

        if($withRelations){
            $data['tickets'] = $this->getTickets();
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
     * @return TicketStatus
     */
    public function setId(string $id): TicketStatus
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
     * @return TicketStatus
     */
    public function setName(string $name): TicketStatus
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return TicketStatus
     */
    public function setSlug(string $slug): TicketStatus
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return LabelEnum|null
     */
    public function getLabel(): ?LabelEnum
    {
        return $this->label;
    }

    /**
     * @param LabelEnum|null $label
     * @return TicketStatus
     */
    public function setLabel(?LabelEnum $label): TicketStatus
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     * @return TicketStatus
     */
    public function setSort(int $sort): TicketStatus
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVisibility(): bool
    {
        return $this->visibility;
    }

    /**
     * @param bool $visibility
     * @return TicketStatus
     */
    public function setVisibility(bool $visibility): TicketStatus
    {
        $this->visibility = $visibility;
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
     * @return TicketStatus
     */
    public function setTickets(?array $tickets): TicketStatus
    {
        $this->tickets = $tickets;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getPivot(): ?array
    {
        return $this->pivot;
    }

    /**
     * @param array|null $pivot
     * @return TicketStatus
     */
    public function setPivot(?array $pivot): TicketStatus
    {
        $this->pivot = $pivot;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPivotDate(): ?string
    {
        // Ensure the pivot is set and contains the 'date' key
        return $this->pivot['date'] ? Carbon::parse($this->pivot['date'])->format('d-m-Y') :  null;
    }

    public function getPivotSort(): ?int
    {
        return $this->pivot['sort'] ?? 0;
    }

}
