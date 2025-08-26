<?php

namespace App\Domains\Files\Repositories\Eloquent;

use App\Domains\Files\Models\File;
use App\Domains\Files\Repositories\Eloquent\Models\File as EloquentFile;
use App\Domains\Files\Repositories\FileRepositoryInterface;
use App\Domains\Notifications\Models\CactusNotification;
use App\Domains\Notifications\Models\EmailNotification;
use App\Domains\Notifications\Models\Recipient;
use App\Domains\Notifications\Services\NotificationsService;
use App\Domains\Projects\Repositories\Eloquent\Models\ProjectType;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;
use App\Models\ModelMorphEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EloqFileRepository implements FileRepositoryInterface
{
    private EloquentFile $model;

    public function __construct(EloquentFile $note)
    {
        $this->model = $note;
    }

    public function getById(string $id): ?File
    {
        $file = $this->model->find($id);
        return ObjectSerializer::deserialize($file->toJson() ?? "{}", File::class, 'json');
    }

    public function store(CactusEntity|File $entity): ?File
    {
        if ($entity->getUploadedBy() === null) {
            $entity->setUploadedBy(Auth::user()->id);
        }

        $file = $this->model::create([
            'name' => $entity->getName(),
            'path' => $entity->getPath(),
            'file_name' => $entity->getFileName(),
            'mime_type' => $entity->getMimeType(),
            'extension' => $entity->getExtension(),
            'size' => $entity->getSize(),
            'uploaded_by' => $entity->getUploadedBy(),
        ]);

        return ObjectSerializer::deserialize($file->toJson() ?? "{}", File::class, 'json');
    }

    public function update(CactusEntity|File $entity, string $id): ?File
    {
        // TODO: Implement deleteById() method.
    }

    public function deleteById(string $id): bool
    {
        // TODO: Implement deleteById() method.
    }

    public function deleteByPath(string $filePath): bool
    {
        $file = $this->model->where('path',$filePath)->first();

        if ($file) {
            $file->delete();

            if (Storage::exists($file)) {
                Storage::delete([$file]);
            }
            return true;
        }

        return false;
    }

    private function relationModel (string $model, string $id) :Model
    {
        // Find Relation Model By Class Name
        $relationModelClass = Relation::getMorphedModel($model);

        // Find Relation Model By id
        return $relationModelClass::find($id);
    }

    public function attach(File $file, string $model, string $morphable_id) : bool
    {
        $file = $this->model::find($file->getId());

        if($file) {
            // Find Relation Model
            $relationModel = $this->relationModel($model, $morphable_id);

            if($model === ModelMorphEnum::LEAD->value) {
                $file->update([
                    'lead_status_id' => $relationModel->status_id
                ]);
            }

            // Attach Note
            $relationModel?->files()?->attach([$file->id]);


            // Send Notification New Note
            $body = 'Ένα καινούργιο αρχείο προστέθηκε με όνομα : ' . $file->file_name;
            $this->sendEmailNotificationNewFile($relationModel, $model, $body);

            return true;
        }
       return false;
    }

    public function sendEmailNotificationNewFile(Model $model, string $modelType, string $body = null): void
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

            $subject = "Ένα καινούργιο αρχείο προστέθηκε στο : ". $model?->name;
            if($modelType == 'Project') {
                $subject = "Ένα καινούργιο αρχείο προστέθηκε στο Project : ". $model?->name;
                $actionText = 'Δες το Project';
                $projectType = ProjectType::find($model->type_id);
                $actionLink = route('admin.projects.show',[$projectType?->slug, $model->id]);
            }else if($modelType == 'Ticket') {
                $subject = "Ένα καινούργιο αρχείο προστέθηκε στο Ticket : ". $model?->name;
                $actionText = 'Δες το Ticket';
                $actionLink = route('admin.tickets.show', $model->id);
            }


            $emailDTO->setSubject($subject);

            $emailDTO->addBody($body ?? $subject);

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
