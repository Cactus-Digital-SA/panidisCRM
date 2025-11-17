<?php

namespace App\Domains\ErpSales\Models;

use App\Models\CactusEntity;

class SalesWidget extends CactusEntity
{

    /**
     * @var string
     * @JMS\Serializer\Annotation\SerializedName("ITEMCODE")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $itemCode;

    /**
     * @var string
     * @JMS\Serializer\Annotation\SerializedName("ITEMNAME")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $itemName;

    /**
     * @var string
     * @JMS\Serializer\Annotation\SerializedName("SALESMAN")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $salesman;

    /**
     * @var string
     * @JMS\Serializer\Annotation\SerializedName("AREA")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $area;

    /**
     * @var float
     * @JMS\Serializer\Annotation\SerializedName("SALESVALUE")
     * @JMS\Serializer\Annotation\Type("float")
     */
    private float $salesValue;

    /**
     * @var string
     * @JMS\Serializer\Annotation\SerializedName("CUSTOMER")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $customer;

    /**
     * @var string
     * @JMS\Serializer\Annotation\SerializedName("MARK")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $mark;

    /**
     * @var string
     * @JMS\Serializer\Annotation\SerializedName("CATEGORY")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $category;

    public function getValues(bool $withRelations = true): array
    {
        return [
            'itemCode' => $this->itemCode,
            'itemName' => $this->itemName,
            'salesman' => $this->salesman,
            'area' => $this->area,
            'salesValue' => $this->salesValue,
            'customer' => $this->customer,
            'mark' => $this->mark,
            'category' => $this->category,
        ];
    }

    public function getItemCode(): string
    {
        return $this->itemCode;
    }

    public function setItemCode(string $itemCode): SalesWidget
    {
        $this->itemCode = $itemCode;
        return $this;
    }

    public function getItemName(): string
    {
        return $this->itemName;
    }

    public function setItemName(string $itemName): SalesWidget
    {
        $this->itemName = $itemName;
        return $this;
    }

    public function getSalesman(): string
    {
        return $this->salesman;
    }

    public function setSalesman(string $salesman): SalesWidget
    {
        $this->salesman = $salesman;
        return $this;
    }

    public function getArea(): string
    {
        return $this->area;
    }

    public function setArea(string $area): SalesWidget
    {
        $this->area = $area;
        return $this;
    }

    public function getSalesValue(): float
    {
        return $this->salesValue;
    }

    public function setSalesValue(float $salesValue): SalesWidget
    {
        $this->salesValue = $salesValue;
        return $this;
    }

    public function getCustomer(): string
    {
        return $this->customer;
    }

    public function setCustomer(string $customer): SalesWidget
    {
        $this->customer = $customer;
        return $this;
    }

    public function getMark(): string
    {
        return $this->mark;
    }

    public function setMark(string $mark): SalesWidget
    {
        $this->mark = $mark;
        return $this;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): SalesWidget
    {
        $this->category = $category;
        return $this;
    }


}
