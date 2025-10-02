<?php

namespace App\Domains\Items\Models;

use App\Models\CactusEntity;

class Item extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var string|null $erpId
     * @JMS\Serializer\Annotation\SerializedName("erp_id")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $erpId;

    /**
     * @var string|null $name
     * @JMS\Serializer\Annotation\SerializedName("name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $name;

    /**
     * @var string|null $category
     * @JMS\Serializer\Annotation\SerializedName("category")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $category;

    /**
     * @var float|null $priceWholesale
     * @JMS\Serializer\Annotation\SerializedName("price_wholesale")
     * @JMS\Serializer\Annotation\Type("float")
     */
    private ?float $priceWholesale;

    /**
     * @var float|null $priceRetail
     * @JMS\Serializer\Annotation\SerializedName("price_retail")
     * @JMS\Serializer\Annotation\Type("float")
     */
    private ?float $priceRetail;

    /**
     * @var string|null $brand
     * @JMS\Serializer\Annotation\SerializedName("brand")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $brand;

    /**
     * @var string|null $model
     * @JMS\Serializer\Annotation\SerializedName("model")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $model;

    /**
     * @var string|null $imagePath
     * @JMS\Serializer\Annotation\SerializedName("image_path")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $imagePath;

    /**
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = true): array
    {
        // TODO: Implement getValues() method.
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Item
    {
        $this->id = $id;
        return $this;
    }

    public function getErpId(): ?string
    {
        return $this->erpId;
    }

    public function setErpId(?string $erpId): Item
    {
        $this->erpId = $erpId;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Item
    {
        $this->name = $name;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): Item
    {
        $this->category = $category;
        return $this;
    }

    public function getPriceWholesale(): ?float
    {
        return $this->priceWholesale;
    }

    public function setPriceWholesale(?float $priceWholesale): Item
    {
        $this->priceWholesale = $priceWholesale;
        return $this;
    }

    public function getPriceRetail(): ?float
    {
        return $this->priceRetail;
    }

    public function setPriceRetail(?float $priceRetail): Item
    {
        $this->priceRetail = $priceRetail;
        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): Item
    {
        $this->brand = $brand;
        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): Item
    {
        $this->model = $model;
        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): Item
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    public function mapItemToDto(array $erpItem): Item
    {
        $item = new Item();

        $item->setErpId($erpItem['erp_id'] ?? null)
            ->setName($erpItem['name'] ?? null)
            ->setCategory($erpItem['mtr_category'] ?? null)
            ->setPriceWholesale(isset($erpItem['price_wholesale']) ? (float)$erpItem['price_wholesale'] : null)
            ->setPriceRetail(isset($erpItem['price_retail']) ? (float)$erpItem['price_retail'] : null)
            ->setBrand($erpItem['brand'] ?? null)
            ->setModel($erpItem['mtr_model'] ?? null)
            ->setImagePath($erpItem['image_path'] ?? null);

        return $item;
    }

}
