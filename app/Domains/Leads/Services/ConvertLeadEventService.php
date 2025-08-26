<?php

namespace App\Domains\Leads\Services;

use App\Domains\Clients\Models\Client;
use App\Domains\Clients\Repositories\Eloquent\Models\ClientStatusEnum;
use App\Domains\Clients\Services\ClientService;
use App\Domains\Events\Models\CactusEvent;
use App\Domains\Leads\Events\ConvertLeadEventInterface;
use App\Domains\Leads\Models\Lead;
use App\Domains\Leads\Repositories\Eloquent\Models\LeadStatusEnum;

class ConvertLeadEventService implements ConvertLeadEventInterface
{
    public function __construct(
        protected ClientService $clientService,
        protected LeadService $leadService,
    ){}

    public function convertEvent(CactusEvent $cactusEvent): ?Client
    {
        /** @var  $leadDTO Lead */
        $leadDTO = $cactusEvent->getCactusEntities()[0] ?? null;
        if($leadDTO) {
            $clientDTO = new Client();
            $clientDTO->setCompanyId($leadDTO->getCompanyId())
                        ->setStatusId(ClientStatusEnum::CONVERTED->value);

            $client = $this->clientService->createOrUpdate($clientDTO);

            // update lead status, event created from Create Project Event
            if (($client->getId() ?? false) && $leadDTO->getStatusId() !== LeadStatusEnum::WON->value) {
                $leadDTO->setStatusId(LeadStatusEnum::WON->value);
                $this->leadService->updateStatus($leadDTO, $leadDTO->getId());
            }


            return $client;
        }

        return null;
    }
}
