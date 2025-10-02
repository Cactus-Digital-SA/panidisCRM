<?php

namespace App\Domains\Erp\Endpoints;

use App\Domains\Erp\Client\ErpClient;
use App\Domains\Erp\DTOs\ERPResponse;
use App\Domains\Erp\DTOs\ERPReport;

class CustomersEndpoint
{
    private ?ErpClient $erpClient = null;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->erpClient = (new ErpClient())->getClient();
    }

    public function getCustomers(): ErpResponse
    {
        return $this->erpClient->post("/s1services/js/Customers/getcustomers");
    }

    public function getCustomer(string $customerERPId): ErpResponse
    {
        $options = [
            "service" => "SqlData",
            "appId" => "3001",
            "SqlName" => "1002",
            "param1" => $customerERPId,
        ];

        return $this->erpClient->post("/s1services", $options);
    }

    public function getCustomerSalesReport(?string $customerERPId = null): ERPReport
    {
        $options = [
            "service" => "getReportInfo",
            "OBJECT" => "STAT_SAL",
            "appId" => "3001",
            "LIST" => "",
        ];

        if ($customerERPId) {
            $options["FILTERS"] = "CUSTOMER.CODE=" . $customerERPId;
        }

        $res = $this->erpClient->post("/s1services", $options)->getData();
        return (new ErpReport())
            ->setReportId($res["reqID"])
            ->setPages($res["npages"]);
    }

    public function getCustomerLedgerReport(?string $customerERPId = null)
    {
        $options = [
            "service" => "getReportInfo",
            "OBJECT" => "CUST_STM",
            "appId" => "3001",
            "LIST" => "",
        ];

        if ($customerERPId) {
            $options["FILTERS"] = "CUSTOMER.CODE=" . $customerERPId . "*";
        }

        $res = $this->erpClient->post("/s1services", $options)->getData();
        return (new ErpReport())
            ->setReportId($res["reqID"])
            ->setPages($res["npages"]);
    }

    public function getCustomerRevenueReport(?string $customerERPId = null): ErpReport
    {
        $options = [
            "service" => "getBrowserInfo",
            "OBJECT" => "CUSTOMER[LIST=Υπόλοιπα πελατών με τζίρο]",
            "appId" => "3001",
            "LIST" => "",
        ];

        if ($customerERPId) {
            $options["FILTERS"] = "CUSTOMER.CODE=" . $customerERPId . "*";
        }

        $res = $this->erpClient->post("/s1services", $options)->getData();
        return (new ErpReport())
            ->setReportId($res["reqID"])
            ->setPages(array_key_exists("npages", $res) ? $res["npages"] : 1);
    }

    public function getReport(ERPReport $report, ?int $page = 1): ERPResponse
    {
        $options = [
            "service" => "getReportData",
            "appId" => "3001",
            "reqID" => $report->getReportId(),
            "PAGENUM" => $page,
        ];

        return $this->erpClient->post("/s1services", $options);
    }

    public function getItems(string $limit = "100", ?string $customerERPId = null): ErpResponse
    {
        $options = [
            "service" => "getBrowserInfo",
            "appId" => "3001",
            "OBJECT" => "ITEM",
            "LIST" => "λίστα cactus",
            "LIMIT" => $limit,
        ];

        if ($customerERPId) {
            $options["FILTERS"] = "CUSTOMER.CODE=" . $customerERPId . "*";
        }

        return $this->erpClient->post("/s1services", $options);
    }
}
