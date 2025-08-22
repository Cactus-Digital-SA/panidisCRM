<?php

namespace App\Domains\Notes\Services;

use App\Domains\Notes\Http\Requests\DeleteNoteRequest;
use App\Domains\Notes\Http\Requests\NoteRequest;
use App\Domains\Notes\Http\Requests\StoreNoteRequest;
use App\Domains\Notes\Http\Requests\UpdateNoteRequest;
use App\Domains\Notes\Models\Note;
use App\Domains\Notes\Repositories\NoteRepositoryInterface;

class NoteService
{
    private NoteRepositoryInterface $repository;

    /**
     * @param NoteRepositoryInterface $repository
     */
    public function __construct(NoteRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $noteId
     * @return Note|null
     */
    public function getById(string $noteId): ?Note
    {
        return $this->repository->getById($noteId);
    }

    /**
     * @param NoteRequest $request
     * @return Note
     */
    public function buildRequest(NoteRequest $request): Note
    {
        return Note::fromRequest($request);
    }

    /**
     * @param Note $note
     * @return Note
     */
    public function store(Note $note): Note
    {
        return $this->repository->store($note);
    }

    /**
     * @param Note $note
     * @param string $id
     * @return Note
     */
    public function update(Note $note, string $id): Note
    {
        return $this->repository->update($note, $id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool
    {
        return $this->repository->deleteById($id);
    }

    /**
     * @param Note $note
     * @param string $id
     * @return bool
     */
    public function deleteByEntityAndNoteId(Note $note, string $id): bool
    {
        return $this->repository->deleteByEntityAndNoteId($note, $id);
    }
}
