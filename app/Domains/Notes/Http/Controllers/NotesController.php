<?php

namespace App\Domains\Notes\Http\Controllers;

use App\Domains\Notes\Http\Requests\DeleteNoteRequest;
use App\Domains\Notes\Http\Requests\StoreNoteRequest;
use App\Domains\Notes\Http\Requests\UpdateNoteRequest;
use App\Domains\Notes\Models\Note;
use App\Domains\Notes\Services\NoteService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

final class NotesController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Domains\Notes\Services\NoteService $noteService
     */
    public function __construct(protected readonly NoteService $noteService)
    {}

    /**
     * Summary of update
     * @param \App\Domains\Notes\Http\Requests\UpdateNoteRequest $request
     * @param string $noteId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateNoteRequest $request, string $noteId): JsonResponse
    {
        $noteDTO = new Note();
        $noteDTO->setContent($request->input('content'));

        $this->noteService->update($noteDTO, $noteId);

        return response()->json(['success'=> true]);
   }

   /**
    * Summary of store
    * @param \App\Domains\Notes\Http\Requests\StoreNoteRequest $request
    * @param string $model
    * @param int $id
    * @return \Illuminate\Http\RedirectResponse
    */
   public function store(StoreNoteRequest $request, string $model, int $id): RedirectResponse
   {
       $request['notableType'] = $model;
       $request['notableId'] = $id;

       $noteDTO = Note::fromRequest($request);

       //dd($noteDTO);
       $this->noteService->store($noteDTO);

       return redirect()->back()->with('success', 'Η σημείωση δημιουργήθηκε');
   }

    /**
     * Summary of destroy
     * @param \App\Domains\Notes\Http\Requests\DeleteNoteRequest $request
     * @param string $noteId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DeleteNoteRequest $request, string $noteId): RedirectResponse
    {
        $response = $this->noteService->deleteById($noteId);
        if ($response) {
            return redirect()->back()->with('success', 'Η σημείωση διαγράφηκε με επιτυχία!');
        }
        return redirect()->back()->with('error', 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή!');
    }
}
