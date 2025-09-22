<?php

namespace App\Domains\Leads\Services;

use App\Domains\Clients\Models\Client;
use App\Domains\Clients\Services\ClientService;
use App\Domains\Leads\Models\Lead;

class ConvertLeadEventService
{
    public function __construct(
        protected ClientService $clientService,
        protected LeadService $leadService,
    ){}

    public function convertEvent(Lead $leadDTO): ?Client
    {
        $clientDTO = new Client();
        $clientDTO->setCompanyId($leadDTO->getCompanyId())
                ->setSalesPersonId($leadDTO->getSalesPersonId())
                ->setTagIds($leadDTO->getTagIds() ?? []);

        $client = $this->clientService->createOrUpdate($clientDTO);

        if($client->getId()){
            $this->leadService->deleteById($leadDTO->getId());
        }

        return $client;
    }
}
