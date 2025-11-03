<?php

namespace App\Domains\Quotes\Models;

use App\Domains\Items\Models\Item;
use App\Domains\Quotes\Enums\UnitTypeEnum;
use App\Models\CactusEntity;
use Illuminate\Http\Request;

class QuoteItem extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var int|null $quoteId
     * @JMS\Serializer\Annotation\SerializedName("quote_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $quoteId = null;

    /**
     * @var int|null $itemId
     * @JMS\Serializer\Annotation\SerializedName("item_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $itemId = null;

    /**
     * @var string $productName
     * @JMS\Serializer\Annotation\SerializedName("product_name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $productName;

    /**
     * @var string|null $sku
     * @JMS\Serializer\Annotation\SerializedName("sku")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $sku;

    /**
     * @var string|null $color
     * @JMS\Serializer\Annotation\SerializedName("color")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $color;

    /**
     * @var UnitTypeEnum|null $unitType
     * @JMS\Serializer\Annotation\SerializedName("unit_type")
     * @JMS\Serializer\Annotation\Type("enum<'App\Domains\Quotes\Enums\UnitTypeEnum'>")
     */
    private ?UnitTypeEnum $unitType;

    /**
     * @var float $price
     * @JMS\Serializer\Annotation\SerializedName("price")
     * @JMS\Serializer\Annotation\Type("float")
     */
    private float $price = 0;

    /**
     * @var float $discount
     * @JMS\Serializer\Annotation\SerializedName("discount")
     * @JMS\Serializer\Annotation\Type("float")
     */
    private float $discount = 0;

    /**
     * @var float $quantity
     * @JMS\Serializer\Annotation\SerializedName("quantity")
     * @JMS\Serializer\Annotation\Type("float")
     */
    private float $quantity = 1;

    /**
     * @var float $total
     * @JMS\Serializer\Annotation\SerializedName("total")
     * @JMS\Serializer\Annotation\Type("float")
     */
    private float $total = 0;

    /**
     * @var Item
     * @JMS\Serializer\Annotation\SerializedName("item")
     * @JMS\Serializer\Annotation\Type("App\Domains\Items\Models\Item")
     */
    private Item $item;

    public function getValues(bool $withRelations = true): array
    {
        // TODO: Implement getValues() method.
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): QuoteItem
    {
        $this->id = $id;
        return $this;
    }

    public function getQuoteId(): ?int
    {
        return $this->quoteId;
    }

    public function setQuoteId(?int $quoteId): QuoteItem
    {
        $this->quoteId = $quoteId;
        return $this;
    }

    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function setItemId(?int $itemId): QuoteItem
    {
        $this->itemId = $itemId;
        return $this;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): QuoteItem
    {
        $this->productName = $productName;
        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): QuoteItem
    {
        $this->sku = $sku;
        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): QuoteItem
    {
        $this->color = $color;
        return $this;
    }

    public function getUnitType(): ?UnitTypeEnum
    {
        return $this->unitType;
    }

    public function setUnitTypeAttribute(?string $value): ?QuoteItem
    {
        $this->setUnitType($value ? UnitTypeEnum::from($value) : null);
        return $this;
    }

    public function setUnitType(?UnitTypeEnum $unitType): QuoteItem
    {
        $this->unitType = $unitType;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): QuoteItem
    {
        $this->price = $price;
        return $this;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): QuoteItem
    {
        $this->discount = $discount;
        return $this;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): QuoteItem
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): QuoteItem
    {
        $this->total = $total;
        return $this;
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function setItem(Item $item): QuoteItem
    {
        $this->item = $item;
        return $this;
    }


    public static function fromRequest(Request $request): QuoteItem
    {
        $quoteItemDTO = new QuoteItem();

        return $quoteItemDTO
            ->setQuoteId($request['quote_id'])
            ->setItemId($request['item_id'])
            ->setProductName($request['product_name'])
            ->setSku($request['sku'])
            ->setColor($request['color'])
            ->setUnitTypeAttribute($request['unit_type'])
            ->setPrice($request['price'])
            ->setDiscount($request['discount'])
            ->setQuantity($request['quantity'])
            ->setTotal($request['item_total_price']);
    }
}
