<?php

namespace App\Domains\Notes\Repositories;

use App\Domains\Notes\Models\Note;
use App\Models\CactusEntity;
use App\Repositories\RepositoryInterface;

interface NoteRepositoryInterface extends RepositoryInterface
{
    /**
     * @param string $id
     * @return Note|null
     */
    public function getById(string $id): ?Note;

    /**
     * @param CactusEntity|Note $entity
     * @return Note|null
     */
    public function store(CactusEntity|Note $entity): ?Note;

    /**
     * @param CactusEntity|Note $entity
     * @param string $id
     * @return Note|null
     */
    public function update(CactusEntity|Note $entity, string $id): ?Note;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool;

    /**
     * @param CactusEntity|Note $entity
     * @param string $id
     * @return bool
     */
    public function deleteByEntityAndNoteId(CactusEntity|Note $entity, string $id): bool;

}
