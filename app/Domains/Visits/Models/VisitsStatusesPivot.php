<?php
declare(strict_types=1);

namespace App\Domains\Visits\Models;

use App\Models\CactusEntity;
use DateTime;
use JMS\Serializer\Annotation as Serializer;

class VisitsStatusesPivot extends CactusEntity
{
    /**
     * @var string $id
     * @Serializer\SerializedName("id")
     * @Serializer\Type("string")
     */
    private string $id;

    /**
     * @var string $visitId
     * @Serializer\SerializedName("visitId")
     * @Serializer\Type("string")
     */
    private string $visitId;

    /**
     * @var string $visitStatusId
     * @Serializer\SerializedName("visitStatusId")
     * @Serializer\Type("string")
     */
    private string $visitStatusId;

    /**
     * @var string $visitStatusSlug
     * @Serializer\SerializedName("visitStatusSlug")
     * @Serializer\Type("string")
     */
    private string $visitStatusSlug;

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
     * @var ?Visit $visit
     * @Serializer\SerializedName("visit")
     * @Serializer\Type("App\Domains\Visits\Models\Visit")
     */
    private ?Visit $visit;

    /**
     * @var ?VisitStatus $visitStatus
     * @Serializer\SerializedName("visit_status")
     * @Serializer\Type("App\Domains\Visits\Models\VisitStatus")
     */
    private ?VisitStatus $visitStatus;

    /**
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = false): array
    {
        $data = [
            'id' => $this->id,
            'visitId' => $this->visitId,
            'visitStatusId' => $this->visitStatusId,
            'visitStatusSlug' => $this->visitStatusSlug,
            'date' => $this->date,
            'sort' => $this->sort,
        ];

        if($withRelations){
            $data['visit'] = $this->getVisit();
            $data['visitStatus'] = $this->getVisitStatus();
        }

        return $data;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): VisitsStatusesPivot
    {
        $this->id = $id;
        return $this;
    }

    public function getVisitId(): string
    {
        return $this->visitId;
    }

    public function setVisitId(string $visitId): VisitsStatusesPivot
    {
        $this->visitId = $visitId;
        return $this;
    }

    public function getVisitStatusId(): string
    {
        return $this->visitStatusId;
    }

    public function setVisitStatusId(string $visitStatusId): VisitsStatusesPivot
    {
        $this->visitStatusId = $visitStatusId;
        return $this;
    }

    public function getVisitStatusSlug(): string
    {
        return $this->visitStatusSlug;
    }

    public function setVisitStatusSlug(string $visitStatusSlug): VisitsStatusesPivot
    {
        $this->visitStatusSlug = $visitStatusSlug;
        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): VisitsStatusesPivot
    {
        $this->date = $date;
        return $this;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): VisitsStatusesPivot
    {
        $this->sort = $sort;
        return $this;
    }

    public function getVisit(): ?Visit
    {
        return $this->visit;
    }

    public function setVisit(?Visit $visit): VisitsStatusesPivot
    {
        $this->visit = $visit;
        return $this;
    }

    public function getVisitStatus(): ?VisitStatus
    {
        return $this->visitStatus;
    }

    public function setVisitStatus(?VisitStatus $visitStatus): VisitsStatusesPivot
    {
        $this->visitStatus = $visitStatus;
        return $this;
    }

}
