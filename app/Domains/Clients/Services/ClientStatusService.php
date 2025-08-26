<?php

namespace App\Domains\Clients\Services;



use App\Domains\Clients\Models\ClientStatus;
use App\Domains\Clients\Repositories\ClientStatusRepositoryInterface;

class ClientStatusService
{
    /**
     * @var ClientStatusRepositoryInterface
     */
    private ClientStatusRepositoryInterface $repository;

    /**
     * @param ClientStatusRepositoryInterface $repository
     */
    public function __construct(ClientStatusRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return ClientStatus[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $id
     * @return ClientStatus|null
     */
    public function getById(string $id): ?ClientStatus
    {
        return $this->repository->getById($id);
    }
}
