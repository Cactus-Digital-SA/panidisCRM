<?php

namespace App\Domains\Notes\Repositories\Eloquent;

use App\Domains\Auth\Models\User;
use App\Domains\Auth\Services\UserService;
use App\Domains\Notes\Models\Note;
use App\Domains\Notes\Repositories\Eloquent\Models\Notable;
use App\Domains\Notes\Repositories\Eloquent\Models\Note as EloquentNote;
use App\Domains\Notes\Repositories\NoteRepositoryInterface;
use App\Domains\Notifications\Models\CactusNotification;
use App\Domains\Notifications\Models\EmailNotification;
use App\Domains\Notifications\Models\Recipient;
use App\Domains\Notifications\Services\NotificationsService;
use App\Domains\Projects\Repositories\Eloquent\Models\ProjectType;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;
use Illuminate\Database\Eloquent\Model;
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

        // Send Notification New Note
        $this->sendEmailNotificationNewNote($relationModel, $entity->getNotableType(), $entity->getContent());

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

    public function sendEmailNotificationNewNote(Model $model, string $modelType, string $body = null): void
    {
        $authUser = Auth::user();

        $recipients = [];

        if($authUser?->id != $model?->owner_id) {
            $recipients[] = new Recipient($model->owner?->email, $model->owner?->name );
        }
        foreach ($model?->assignees ?? [] as $assignee){
            if($assignee->id != $authUser?->id) {
                $recipients[] = new Recipient($assignee->email, $assignee->name);
            }
        }


        try {
            $emailDTO = new EmailNotification();
            $emailDTO->setRecipients($recipients);

            $subject = "Έχεις καινούργιο σχόλιο : ". $model?->name;
            if($modelType == 'Project') {
                $subject = "Έχεις καινούργιο σχόλιο στο Project : ". $model?->name;
                $actionText = 'Δες το Project';
                $projectType = ProjectType::find($model->type_id);
                $actionLink = route('admin.projects.show',[$projectType?->slug, $model->id]);
            }else if($modelType == 'Ticket') {
                $subject = "Έχεις καινούργιο σχόλιο στο Ticket : ". $model?->name;
                $actionText = 'Δες το Ticket';
                $actionLink = route('admin.tickets.show', $model->id);
            }


            $emailDTO->setSubject($subject);

            $emailDTO->addBody($body);

            if(isset($actionText) && isset($actionLink)) {
                $emailDTO->addAction($actionText, $actionLink, 'btn-primary');
            }

            $cactusNotification = new CactusNotification([$emailDTO]);

            // Αποστολή Ειδοποίησης
            $notificationService = new NotificationsService();
            $notificationService->send($cactusNotification);

        } catch (\Exception $e) {

            \Log::error('email error: '. $e->getMessage());
        }

    }
}
