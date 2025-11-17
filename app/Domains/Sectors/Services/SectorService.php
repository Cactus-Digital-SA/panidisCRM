<?php

namespace App\Domains\Sectors\Services;

use App\Domains\Sectors\Models\Sector;
use App\Domains\Sectors\Repositories\SectorRepositoryInterface;

class SectorService
{
    private SectorRepositoryInterface $repository;

    /**
     * @param SectorRepositoryInterface $repository
     */
    public function __construct(SectorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Sector[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $id
     * @return Sector|null
     */
    public function getById(string $sectorId, bool $withRelations = true): ?Sector
    {
        return $this->repository->getById($sectorId, $withRelations);
    }

    /**
     * @param Sector $sector
     * @return Sector|null
     */
    public function store(Sector $sector): Sector
    {
        $sectorDTO = $this->repository->store($sector);
        return $sectorDTO;
    }

    /**
     * @param Sector $sector
     * @param string $id
     * @return Sector|null
     */
    public function update(Sector $sector, string $id): Sector
    {
        $sectorDTO = $this->repository->update($sector, $id);
        return $sectorDTO;
    }

    public function createOrUpdateByErpId(Sector $sector, string $erpId): Sector
    {
        return $this->repository->createOrUpdateByErpId($sector, $erpId);
    }

    /**
     * @param string $id
     * @return bool
     *
     */
    public function deleteById(string $id): bool
    {
        return $this->repository->deleteById($id);
    }

    public function assignUserToSector(int $sectorId, int $userId): bool
    {
        return $this->repository->assignUser($sectorId, $userId);
    }
}
