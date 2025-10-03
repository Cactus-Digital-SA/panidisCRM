<?php

namespace App\Domains\ExtraData\Models;

use App\Domains\ExtraData\Enums\ExtraDataTypesEnum;
use App\Models\CactusEntity;
use Illuminate\Http\Request;
use JMS\Serializer\Annotation as Serializer;

class ExtraData extends CactusEntity
{
    /**
     * @var string|null $id
     * @Serializer\SerializedName("id")
     * @Serializer\Type("string")
     */
    private ?string $id = null;

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
     * @var ExtraDataTypesEnum $type
     * @Serializer\SerializedName("type")
     * @Serializer\Type("enum<'App\Domains\ExtraData\Enums\ExtraDataTypesEnum'>")
     */
    private ExtraDataTypesEnum $type = ExtraDataTypesEnum::TEXT;

    /**
     * @var string|null $options
     * @Serializer\SerializedName("options")
     * @Serializer\Type("string")
     */
    private ?string $options = null;

    /**
     * @var bool $required
     * @Serializer\SerializedName("required")
     * @Serializer\Type("bool")
     */
    private bool $required = false;

    /**
     * @var bool $multiple
     * @Serializer\SerializedName("multiple")
     * @Serializer\Type("bool")
     */
    private bool $multiple = false;

    /**
     * @var ExtraDataModel[] $models
     * @Serializer\SerializedName("models")
     * @Serializer\Type("array<App\Domains\ExtraData\Models\ExtraDataModel>")
     */
    private ?array $models = [];

    /**
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = true): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type->value,
            'options' => $this->options,
            'required' => $this->required,
            'multiple' => $this->multiple
        ];

//        if ($withRelations) {
//
//        }

        return $data;
    }

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return ExtraData
     */
    public function setId(string $id): ExtraData
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ExtraData
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): ExtraData
    {
        $this->description = $description;
        return $this;
    }

    public function getType(): ExtraDataTypesEnum
    {
        return $this->type;
    }

    public function setType(ExtraDataTypesEnum $type): ExtraData
    {
        $this->type = $type;
        return $this;
    }

    public function getOptions(): ?string
    {
        return $this->options;
    }

    public function setOptions(?string $options): ExtraData
    {
        $this->options = $options;
        return $this;
    }

    public function getRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): ExtraData
    {
        $this->required = $required;
        return $this;
    }

    public function getMultiple(): bool
    {
        return $this->multiple;
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    public function setMultiple(bool $multiple): ExtraData
    {
        $this->multiple = $multiple;
        return $this;
    }

    public function getModels(): ?array
    {
        return $this->models;
    }

    public function setModels(?array $models): ExtraData
    {
        $this->models = $models;
        return $this;
    }

    public static function fromRequest(Request $request): ExtraData
    {
        $extraDataDTO = new ExtraData();

        return $extraDataDTO
            ->setName($request['name'])
            ->setDescription($request['description'])
            ->setType(ExtraDataTypesEnum::from($request['type']))
            ->setOptions($request['options'])
            ->setRequired((bool)$request['required'])
            ->setMultiple((bool)$request['multiple']);
    }

}
