<?php

namespace App\Domains\Erp\Services;

use App\Domains\Clients\Models\Client;
use App\Domains\Clients\Services\ClientService;
use App\Domains\Companies\Models\Company;
use App\Domains\Companies\Services\CompanyService;
use App\Domains\CountryCodes\Models\CountryCode;
use App\Domains\CountryCodes\Services\CountryCodeService;
use App\Domains\Erp\DTOs\ERPReport;
use App\Domains\Erp\Endpoints\CountriesEndpoint;
use App\Domains\Erp\Endpoints\CustomersEndpoint;
use App\Domains\Erp\Endpoints\SalesEndpoint;
use App\Domains\ErpSales\Models\Salesman;
use App\Domains\ErpSales\Models\SalesWidget;
use App\Domains\ErpSales\Services\SalesmanService;
use App\Domains\Items\Models\Item;
use App\Domains\Items\Services\ItemService;
use App\Domains\Sectors\Models\Sector;
use App\Domains\Sectors\Services\SectorService;

class ErpService
{
    public function __construct(private readonly CustomersEndpoint $customers, private readonly CountriesEndpoint $countries,
                                private readonly CountryCodeService $countryCodeService, private readonly MapperService $mapperService,
                                private readonly ClientService $clientService, private readonly CompanyService $companyService,
                                private readonly SalesEndpoint $salesEndpoint

    )
    {}

    public function syncCustomers(): void
    {
        $response = $this->customers->getCustomers()->getResponseBody();
        $customers = json_decode($response, true);

        foreach ($customers as $customer) {
            try {
                $mappedCustomer = $this->mapperService->mapCustomer($customer);

                // create the company
                $companyDTO = new Company();
                $companyDTO = $companyDTO->mapCompanyToDto($mappedCustomer);

                $company = $this->companyService->storeOrUpdate($companyDTO);

                // create the customer
                $clientDTO = new Client();
                $clientDTO = $clientDTO->setCompanyId($company->getId());

                $client = $this->clientService->storeOrUpdate($clientDTO);

            }
            catch (\Exception $e) {
                \Log::error('ERP syncCustomers : '. $e->getMessage());
            }
        }

    }

    public function syncCustomer(string $erpId): ?Company
    {
        if (!$erpId) {
            throw new \InvalidArgumentException('ERP ID is required');
        }

        $response = $this->customers->getCustomer($erpId)->getResponseBody();
        $responseData = json_decode($response, true);
        $customers = $responseData['rows'] ?? [];

        try {
            foreach ($customers as $customer) {
                if($customer['CUSTCODE'] == $erpId) {
                    $mappedCustomer = $this->mapperService->mapCustomer($customer);

                    // create the company
                    $companyDTO = new Company();
                    return $companyDTO->mapCompanyToDto($mappedCustomer);
                }
            }
        }
        catch (\Exception $e) {
            \Log::error('ERP syncCustomer : '. $e->getMessage());
        }

        return null;
    }


    public function syncCountries(): void
    {
        $countries = $this->countries->getCountries()->getData();
        foreach ($countries as $country) {
            try {
                $countryIsoCode = $this->mapperService->mapCountry((int)$country["COUNTRYCODE"]);
                if($countryIsoCode){
                    $countryDTO = new CountryCode();
                    $countryDTO->setErpId($country["COUNTRYCODE"]);
                    $countryDTO->setName($country["COUNTRYNAME"]);
                    $countryDTO->setIsoCode($countryIsoCode);
                    $this->countryCodeService->updateErpIdByCountryCode($countryIsoCode, $country["COUNTRYCODE"], $countryDTO);
                }
            }
            catch (\Exception $e) {
                throw $e;
            }

        }
    }

    public function getCustomerSalesReport(?string $customerERPId = null): ERPReport
    {
        return $this->customers->getCustomerSalesReport($customerERPId);
    }

    public function getCustomerLedgerReport(?string $customerERPId = null): ERPReport
    {
        return $this->customers->getCustomerLedgerReport($customerERPId);
    }

    public function getCustomerRevenueReport(?string $customerERPId = null): ERPReport
    {
        return $this->customers->getCustomerRevenueReport($customerERPId);
    }

    public function getCustomerRevenueData(?string $customerCode = null)
    {
        $info = $this->getCustomerRevenueReport($customerCode);
        $response = $this->customers->getCustomerRevenueReportData($info) ?? [];

        $data = $response->getData();

        if (!$data['success'] || empty($data['rows'])) {
            return [];
        }

        $mapped = [];

        foreach ($data['rows'] as $row) {
            $mapped[] = [
                'index'     => $row[1] ?? null,
                'salesman'  => $row[2] ?? null,
                'code'      => $row[3] ?? null,
                'name'      => $row[4] ?? null,
                'balance'   => $row[5] ?: 0,
                'turnover'  => $row[6] ?: 0,
            ];
        }

        return $mapped;
    }

    public function getReport(ERPReport $report, ?int $page = 1): string
    {
        return $this->customers->getReport($report, $page)->getResponseBody();
    }

    public function getItems(?string $limit = "", ?string $itemERPId = null): void
    {
        // στην πρώτη κλήση παίρνω το totalCount και ξανακαλώ τη συνάρτηση με το limit
        // αν μου επιστρέψει ολα τα data χωρίς το limit συνεχίζει χωρίς δεύτερη κλήση
        $response = $this->customers->getItems($limit, $itemERPId)->getResponseBody();

        $data = json_decode($response, true);

        if (!$data || !isset($data['fields'], $data['rows'])) {
            if(!isset($data['rows']) && isset($data['totalcount']) && $data['totalcount'] > 1){
                $this->getItems($data['totalcount'], $itemERPId);
                return ;
            }
            throw new \RuntimeException('Invalid ERP response for items');
        }

        $fields = $data['fields'];
        $rows   = $data['rows'];

        // Κάνουμε map τα rows
        $mappedItems = $this->mapperService->mapItemsFromRows($fields, $rows);

        $itemService = app(ItemService::class);
        foreach ($mappedItems as $mappedItem) {
            $itemDTO = new Item();
            $itemDTO = $itemDTO->mapItemToDto($mappedItem);

            $itemService->storeOrUpdate($itemDTO);
        }

    }

    public function getAreas(): void
    {
        $salesAreas =  $this->salesEndpoint->getAreas()->getData();

        $sectorService = app(SectorService::class);
        foreach ($salesAreas as $area) {
            $sectorDTO = new Sector();
            $sectorDTO->setErpId($area["CODE"]);
            $sectorDTO->setName($area["NAME"]);

            $sectorService->createOrUpdateByErpId($sectorDTO, $sectorDTO->getErpId());
        }
    }

    public function getSalesmen(): void
    {
        $salesmen = $this->salesEndpoint->getSalesman()->getData();

        $salesmanService = app(SalesmanService::class);
        foreach ($salesmen as $salesman) {
            $salesmanDTO = new Salesman();
            $salesmanDTO->setErpId($salesman["CODE"]);
            $salesmanDTO->setName($salesman["NAME"]);

            $salesmanService->createOrUpdateByErpId($salesmanDTO, $salesmanDTO->getErpId());
        }

    }

    public function getSalesDashboardData(array $filters = []): array
    {
        $rawData = $this->getSalesWidgetData($filters);

        $byArea = $this->buildSalesByArea($rawData);
        $bySalesman = $this->buildSalesBySalesman($rawData);

        return [
            'raw'        => $rawData,
            'byArea'     => $byArea,
            'bySalesman' => $bySalesman,
        ];
    }

    private function buildSalesByArea(array $widgets): array
    {
        $areas = [];

        foreach ($widgets as $row) {
            $area = $row->getArea();
            $value = $row->getSalesValue();

            if (!isset($areas[$area])) {
                $areas[$area] = 0;
            }

            $areas[$area] += $value;
        }

        return [
            'labels' => array_keys($areas),
            'datasets' => [
                ['data' => array_values($areas)]
            ]
        ];
    }

    private function buildSalesBySalesman(array $widgets): array
    {
        $salesmen = [];

        foreach ($widgets as $row) {
            $salesman = $row->getSalesman();
            $value = $row->getSalesValue();

            if (!isset($salesmen[$salesman])) {
                $salesmen[$salesman] = 0;
            }

            $salesmen[$salesman] += $value;
        }

        return [
            'labels' => array_keys($salesmen),
            'datasets' => [
                [
                    'label' => 'Συνολικές Πωλήσεις',
                    'data' => array_values($salesmen),
                ]
            ]
        ];
    }

    public function getSalesWidgetData(array $filters = []): array
    {
        $response = $this->salesEndpoint->getSalesWidgetData($filters)->getData();
        $salesData = $response['rows'] ?? [];

        $data = [];
        foreach ($salesData as $salesWidget) {
            $salesWidgetDTO = new SalesWidget();
            $salesWidgetDTO->setItemCode($salesWidget["ITEMCODE"] ?? '');
            $salesWidgetDTO->setItemName($salesWidget["ITEMNAME"] ?? '');
            $salesWidgetDTO->setSalesman($salesWidget["SALESMAN"] ?? '');
            $salesWidgetDTO->setArea($salesWidget["AREA"] ?? '');
            $salesWidgetDTO->setSalesValue(floatval($salesWidget["SALESVALUE"] ?? 0));
            $salesWidgetDTO->setCustomer($salesWidget["CUSTOMER"] ?? '');
            $salesWidgetDTO->setMark($salesWidget["MARK"] ?? '');
            $salesWidgetDTO->setCategory($salesWidget["CATEGORY"] ?? '');

            $data[] = $salesWidgetDTO;
        }

        return $data;
    }

}
