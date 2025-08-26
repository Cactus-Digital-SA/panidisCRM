<?php

namespace App\Domains\Clients\Repositories\Eloquent;

use App\Domains\Clients\Models\ClientStatus;
use App\Domains\Clients\Repositories\ClientStatusRepositoryInterface;
use App\Domains\Clients\Repositories\Eloquent\Models\ClientStatusEnum;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;
use stdClass;

class EloqClientStatusRepository implements ClientStatusRepositoryInterface
{

    public function get(): array
    {
        $clientStatusArray = ClientStatusEnum::array();

        $clientStatuses = [];

        foreach($clientStatusArray as $key => $clientStatus){
            $object = new StdClass();
            $object->id = $key;
            $object->name = $clientStatus;

            $clientStatuses[] = $object;
        }

        return ObjectSerializer::deserialize(json_encode($clientStatuses) ?? "{}", 'array<' . ClientStatus::class . '>', 'json');
    }

    public function getById(string $id): ?ClientStatus
    {
        $clientStatus = ClientStatusEnum::from($id)->label();

        $object = new StdClass();
        $object->id = $id;
        $object->name = $clientStatus;

        return ObjectSerializer::deserialize(json_encode($object) ?? "{}", 'array<' . ClientStatus::class . '>', 'json');

    }

    public function store(ClientStatus|CactusEntity $entity): ?ClientStatus
    {
        // TODO: Implement store() method.
    }

    public function update(ClientStatus|CactusEntity $entity, string $id): ?ClientStatus
    {
        // TODO: Implement update() method.
    }

    public function deleteById(string $id): bool
    {
        // TODO: Implement deleteById() method.
    }
}
