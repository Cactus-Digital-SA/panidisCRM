<?php

namespace App\Console\Commands;

use App\Domains\Clients\Models\Client;
use App\Domains\Clients\Services\ClientService;
use App\Domains\Companies\Models\Company;
use App\Domains\Companies\Services\CompanyService;
use App\Domains\Erp\Endpoints\CustomersEndpoint;
use App\Domains\Erp\Services\MapperService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncCustomers extends Command
{
    protected $signature = 'erp:sync-customers';
    protected $description = 'Sync Customers from ERP';


    public function __construct(
        private readonly CustomersEndpoint $customers,
        private readonly MapperService $mapperService,
        private readonly ClientService $clientService,
        private readonly CompanyService $companyService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $startTime = microtime(true);

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

        $duration = round(microtime(true) - $startTime, 2);

        Log::info('Sync Customers from ERP', [
            'duration_sec' => $duration,
        ]);
    }
}
