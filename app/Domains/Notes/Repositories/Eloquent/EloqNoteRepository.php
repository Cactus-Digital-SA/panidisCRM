<?php

namespace App\Domains\Notes\Repositories\Eloquent;

use App\Domains\Auth\Models\User;
use App\Domains\Notes\Models\Note;
use App\Domains\Notes\Repositories\Eloquent\Models\Notable;
use App\Domains\Notes\Repositories\Eloquent\Models\Note as EloquentNote;
use App\Domains\Notes\Repositories\NoteRepositoryInterface;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;

class EloqNoteRepository implements NoteRepositoryInterface
{
    private EloquentNote $model;

    public function __construct(EloquentNote $note)
    {
        $this->model = $note;
    }

    /**
     * @param string $model
     * @param string $id
     * @return mixed
     */
    private function relationModel (string $model, string $id)
    {
        // Find Relation Model By Class Name
        $relationModelClass = Relation::getMorphedModel($model);

        // Find Relation Model By id
        return $relationModelClass::find($id);
    }

    /**
     * @inheritdoc
     */
    public function getById(string $id): ?Note
    {
        $note = $this->model->find($id);

        return ObjectSerializer::deserialize($note->toJson() ?? "{}", Note::class, 'json');
    }

    /**
     * @inheritdoc
     */
    public function store(CactusEntity|Note $entity): ?Note
    {
        if ($entity->getUserId() === null) {
            $entity->setUserId(Auth::user()->id);
        }

        $note = $this->model::create([
            'user_id' => $entity->getUserId(),
            'content' => $entity->getContent(),
        ]);

        // Find Relation Model
        $relationModel = $this->relationModel($entity->getNotableType(), $entity->getNotableId());

        // Attach Note
        $relationModel?->notes()?->attach([$note->id]);

        return ObjectSerializer::deserialize($note->toJson() ?? "{}", Note::class, 'json');
    }

    /**
     * @inheritdoc
     */
    public function update(CactusEntity|Note $entity, string $id): ?Note
    {
        // Find Relation Model
        //$relationModel = $this->relationModel($entity);

        // Find Note by relation and id
        $note = $this->model::find($id);

        // Update Note
        $note?->update([
            'content' => $entity->getContent(),
        ]);

        return ObjectSerializer::deserialize($note->toJson() ?? "{}", Note::class, 'json');
    }

    /**
     * @inheritdoc
     */
    public function deleteById(string $id): bool
    {
        $note = $this->model->find($id);
        return (bool)$note?->delete();
    }

    /**
     * @inheritdoc
     */
    public function deleteByEntityAndNoteId(CactusEntity|Note $entity, string $id): bool
    {
        // Find Relation Model
        $relationModel = $this->relationModel($entity);

        // Find Note by relation and id
        $note = $relationModel?->notes()?->find($id);

        return (bool)$note?->delete();
    }
}
