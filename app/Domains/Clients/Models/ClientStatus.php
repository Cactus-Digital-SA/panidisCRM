<?php

namespace App\Domains\Clients\Models;

use App\Models\CactusEntity;

class ClientStatus extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var string $name
     * @JMS\Serializer\Annotation\SerializedName("name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $name;

    /**
     * @var ?Client[] $clients
     * @JMS\Serializer\Annotation\SerializedName("clients")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Projects\Models\Client>")
     */
    private ?array $clients;

    /**
     * Summary of getValues
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = true): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
        ];


        if($withRelations){
            $data['clients'] = $this->getClients();
        }

        return $data;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): ClientStatus
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Summary of getName
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Summary of setName
     * @param string $name
     * @return \App\Domains\Clients\Models\ClientStatus
     */
    public function setName(string $name): ClientStatus
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Summary of getClients
     * @return array|null
     */
    public function getClients(): ?array
    {
        return $this->clients;
    }

    /**
     * Summary of setClients
     * @param array $clients
     * @return \App\Domains\Clients\Models\ClientStatus
     */
    public function setClients(array $clients): ClientStatus
    {
        $this->clients = $clients;
        return $this;
    }
}
