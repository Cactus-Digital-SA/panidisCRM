<?php

namespace App\Domains\Files\Services;

use App\Domains\Files\Http\Requests\CreateFileRequest;
use App\Domains\Files\Models\File;
use App\Domains\Files\Repositories\FileRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileService
{
    private FileRepositoryInterface $repository;

    /**
     * @param FileRepositoryInterface $repository
     */
    public function __construct(FileRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $fileId
     * @return File|null
     */
    public function getById(string $fileId): ?File
    {
        return $this->repository->getById($fileId);
    }

    /**
     * @param $files array|UploadedFile|UploadedFile[]
     * @param string $model
     * @param string $id
     * @return File[]
     */
    public function create(array $files, string $model, string $id): array
    {
        $storedFiles = [];
        foreach ($files as $file){
            if ($file->isValid()) {
                $fileDTO = File::fromRequest($file);
                $storedFiles [] = $this->store($fileDTO,$model,$id);

            }
        }

        foreach ($storedFiles as $storedFile){
            $this->repository->attach($storedFile, $model, $id);
        }

        return $storedFiles;
    }

    /**
     * @param File $file
     * @param string $model
     * @param string $id
     * @return File
     */
    public function store(File $file, string $model, string $id): File
    {
        return $this->repository->store($file);
    }

    /**
     * @param string $deleteByPath
     * @return bool
     */
    public function deleteByPath(string $deleteByPath): bool
    {
        return $this->repository->deleteByPath($deleteByPath);
    }

    /**
     * @param string $filePath
     * @return StreamedResponse|null
     */
    public function previewFile(string $filePath): ?StreamedResponse
    {
        return Storage::exists($filePath) ? Storage::response($filePath) : null;
    }

    /**
     * @param string $filePath
     * @return StreamedResponse|null
     */
    public function downloadFile(string $filePath): ?StreamedResponse
    {
        return Storage::exists($filePath) ? Storage::download($filePath) : null;
    }

}
