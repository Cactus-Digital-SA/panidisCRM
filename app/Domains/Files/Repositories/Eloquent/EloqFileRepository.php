<?php

namespace App\Domains\Files\Repositories\Eloquent;

use App\Domains\Files\Models\File;
use App\Domains\Files\Repositories\Eloquent\Models\File as EloquentFile;
use App\Domains\Files\Repositories\FileRepositoryInterface;
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

            // Attach Note
            $relationModel?->files()?->attach([$file->id]);

            return true;
        }
       return false;
    }
}
