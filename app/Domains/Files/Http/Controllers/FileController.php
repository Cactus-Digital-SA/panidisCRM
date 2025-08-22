<?php

namespace App\Domains\Files\Http\Controllers;

use App\Domains\Files\Http\Requests\DeleteFileRequest;
use App\Domains\Files\Http\Requests\DownloadFileRequest;
use App\Domains\Files\Http\Requests\PreviewFileRequest;
use App\Domains\Files\Http\Requests\StoreFileRequest;
use App\Domains\Files\Models\File;
use App\Domains\Files\Services\FileService;
use App\Http\Controllers\Controller;
use App\Models\CactusEntity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class FileController extends Controller
{
    protected FileService $fileService;

    /**
     * Summary of __construct
     * @param \App\Domains\Files\Services\FileService $fileService
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * @param StoreFileRequest $request
     * @param string $model
     * @param string $id
     * @return RedirectResponse|JsonResponse
     * @throws \JsonException
     */
    public function store(StoreFileRequest $request, string $model, string $id): RedirectResponse|JsonResponse
    {
        $files = $request->file('file');

        $files = $this->fileService->create($files, $model, $id);

        foreach ($files as $key => $file){
            $files[$key] = $file->convertToJSON();
        }

        if($request->ajax()){
            return response()->json(['success' => true , 'files' => json_encode($files)]);
        }
        if($result ?? false){
            return redirect()->back()->with('success', 'Το αρχείο αποθηκεύτηκε');
        }

        return redirect()->back()->with('error', 'Υπήρξε κάποιο πρόβλημα κατά την αποθήκευση!');

    }

    /**
     * @param PreviewFileRequest $request
     * @return StreamedResponse
     */
    public function previewFile(PreviewFileRequest $request): StreamedResponse
    {
        if($request['filePath']){
            try{
                return $this->fileService->previewFile($request['filePath']) ?? abort(404);
            }catch (\Exception $exception){
                \Log::error($exception);
            }
        }

        abort(404);
    }

    /**
     * @param DownloadFileRequest $request
     * @return StreamedResponse
     */
    public function downloadFile(DownloadFileRequest $request): StreamedResponse
    {
        if($request['filePath']){
            try{
                return $this->fileService->downloadFile($request['filePath']) ?? abort(404);
            }catch (\Exception $exception){
                \Log::error($exception);
            }
        }

        abort(404);
    }

    /**
     * @param DeleteFileRequest $request
     * @return RedirectResponse
     */
    public function destroy(DeleteFileRequest $request): RedirectResponse
    {
        $response = $this->fileService->deleteByPath($request['filePath']);
        if($response){
            return redirect()->back()->with('success', 'Το αρχείο Διαγράφηκε');
        }

        return redirect()->back()->with('error', 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή!');

    }
}
