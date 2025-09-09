<?php

namespace App\Domains\Visits\Models;

use App\Helpers\Enums\LabelEnum;
use App\Models\CactusEntity;
use Carbon\Carbon;
use JMS\Serializer\Annotation as Serializer;

class VisitStatus extends CactusEntity
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
     * @var ?Visit[] $visits
     * @Serializer\SerializedName("visits")
     * @Serializer\Type("array<App\Domains\Visits\Models\Visit>")
     */
    private ?array $visits;

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
            $data['visits'] = $this->getVisits();
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
     * @return VisitStatus
     */
    public function setId(string $id): VisitStatus
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
     * @return VisitStatus
     */
    public function setName(string $name): VisitStatus
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
     * @return VisitStatus
     */
    public function setSlug(string $slug): VisitStatus
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
     * @return VisitStatus
     */
    public function setLabel(?LabelEnum $label): VisitStatus
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
     * @return VisitStatus
     */
    public function setSort(int $sort): VisitStatus
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
     * @return VisitStatus
     */
    public function setVisibility(bool $visibility): VisitStatus
    {
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getVisits(): ?array
    {
        return $this->visits;
    }

    /**
     * @param array|null $visits
     * @return VisitStatus
     */
    public function setVisits(?array $visits): VisitStatus
    {
        $this->visits = $visits;
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
     * @return VisitStatus
     */
    public function setPivot(?array $pivot): VisitStatus
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
