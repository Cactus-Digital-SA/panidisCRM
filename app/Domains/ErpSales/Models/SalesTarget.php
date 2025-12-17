<?php

namespace App\Domains\ErpSales\Models;

use App\Models\CactusEntity;

class SalesTarget extends CactusEntity
{

    /**
     * @var string
     * @JMS\Serializer\Annotation\SerializedName("SalesmanCode")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $salesmanCode;

    /**
     * @var string
     * @JMS\Serializer\Annotation\SerializedName("SalesmanName")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $salesmanName;

    /**
     * @var string
     * @JMS\Serializer\Annotation\SerializedName("MonthSales")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $monthSales;

    /**
     * @var string
     * @JMS\Serializer\Annotation\SerializedName("YearNum")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $yearNum;

    /**
     * @var float
     * @JMS\Serializer\Annotation\SerializedName("TargetAmount")
     * @JMS\Serializer\Annotation\Type("float")
     */
    private float $targetAmount;


    public function getValues(bool $withRelations = true): array
    {
        return [
            'salesmanCode' => $this->salesmanCode,
            'salesmanName' => $this->salesmanName,
            'monthSales' => $this->monthSales,
            'yearNum' => $this->yearNum,
            'targetAmount' => $this->targetAmount,
        ];
    }

    public function getSalesmanCode(): string
    {
        return $this->salesmanCode;
    }

    public function setSalesmanCode(string $salesmanCode): SalesTarget
    {
        $this->salesmanCode = $salesmanCode;
        return $this;
    }

    public function getSalesmanName(): string
    {
        return $this->salesmanName;
    }

    public function setSalesmanName(string $salesmanName): SalesTarget
    {
        $this->salesmanName = $salesmanName;
        return $this;
    }

    public function getMonthSales(): string
    {
        return $this->monthSales;
    }

    public function setMonthSales(string $monthSales): SalesTarget
    {
        $this->monthSales = $monthSales;
        return $this;
    }

    public function getYearNum(): string
    {
        return $this->yearNum;
    }

    public function setYearNum(string $yearNum): SalesTarget
    {
        $this->yearNum = $yearNum;
        return $this;
    }

    public function getTargetAmount(): float
    {
        return $this->targetAmount;
    }

    public function setTargetAmount(float $targetAmount): SalesTarget
    {
        $this->targetAmount = $targetAmount;
        return $this;
    }




}
