<?php

namespace App\Domains\Erp\Endpoints;

use App\Domains\Erp\Client\ErpClient;
use App\Domains\Erp\DTOs\ERPResponse;
use App\Domains\Erp\DTOs\ERPReport;

class SalesEndpoint
{
    private ?ErpClient $erpClient = null;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->erpClient = (new ErpClient())->getClient();
    }

    public function getAreas(): ErpResponse
    {
        return $this->erpClient->post("/s1services/js/CrmData/getAREAS");
    }

    public function getSalesMan()
    {
        return $this->erpClient->post("/s1services/js/CrmData/getSALESMAN");
    }

    public function getSalesWidgetData(array $filters = []): ERPResponse
    {
        $productCode = $filters['productCode'] ?? '%%';
        $salesmanName = $filters['salesman'] ?? '%%';
        $categoryProduct = $filters['categoryProduct'] ?? '%%';
        $area = $filters['area'] ?? '%%';

        $options = [
            "service" => "SqlData",
            "appId" => "3001",
            "SqlName" => "1001",
            "param1" => $productCode,
            "param2" => $salesmanName,
            "param3" => $categoryProduct,
            "param4" => $area
        ];

        return $this->erpClient->post("/s1services", $options);
    }

    public function getSalesTarget(array $filters = []): ERPResponse
    {
        $salesman = $filters['salesman'] ?? '%';
        $month    = $filters['month'] ?? '%';

        $options = [
            'service' => 'SqlData',
            'appId'   => '3001',
            'SqlName' => '1003',
            'param1'  => $salesman,
            'param2'  => $month,
        ];

        return $this->erpClient->post('/s1services', $options);
    }
}
