<?php

namespace App\Domains\Erp\Endpoints;

use App\Domains\Erp\Client\ErpClient;
use App\Domains\Erp\DTOs\ERPResponse;

class CountriesEndpoint
{
    private ?ErpClient $erpClient = null;
    /**
     * @throws \Exception
     */
    public function __construct(
    ) {
        $this->erpClient = (new ErpClient())->getClient();
    }

    public function getCountries(): ErpResponse {
        return $this->erpClient->post("/s1services/js/COUNTRY/getCOUNTRY");
    }
}
