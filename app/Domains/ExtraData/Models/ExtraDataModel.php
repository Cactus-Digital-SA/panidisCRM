<?php

namespace App\Domains\ExtraData\Models;

use App\Domains\ExtraData\Enums\ExtraDataTypesEnum;
use App\Models\CactusEntity;
use Illuminate\Http\Request;
use JMS\Serializer\Annotation as Serializer;

class ExtraDataModel extends CactusEntity
{
    /**
     * @var string $id
     * @Serializer\SerializedName("id")
     * @Serializer\Type("string")
     */
    private string $id;

    /**
     * @var string $model
     * @Serializer\SerializedName("model")
     * @Serializer\Type("string")
     */
    private string $model;

    /**
     * @var int $extraDataId
     * @Serializer\SerializedName("extra_data_id")
     * @Serializer\Type("int")
     */
    private int $extraDataId;

    /**
     * @var ExtraData|null $extraData
     * @Serializer\SerializedName("models")
     * @Serializer\Type("array<App\Domains\ExtraData\Models\ExtraData>")
     */
    private ?ExtraData $extraData = null;

    public function getValues(bool $withRelations = true): array
    {
        // TODO: Implement getValues() method.
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): ExtraDataModel
    {
        $this->id = $id;
        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): ExtraDataModel
    {
        $this->model = $model;
        return $this;
    }

    public function getExtraDataId(): int
    {
        return $this->extraDataId;
    }

    public function setExtraDataId(int $extraDataId): ExtraDataModel
    {
        $this->extraDataId = $extraDataId;
        return $this;
    }

    public function getExtraData(): ?ExtraData
    {
        return $this->extraData;
    }

    public function setExtraData(?ExtraData $extraData): ExtraDataModel
    {
        $this->extraData = $extraData;
        return $this;
    }


}
