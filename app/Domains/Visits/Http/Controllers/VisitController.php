<?php

namespace App\Domains\Visits\Http\Controllers;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Auth\Services\UserService;
use App\Domains\Files\Services\FileService;
use App\Domains\Notes\Models\Note;
use App\Domains\Notes\Services\NoteService;
use App\Domains\Visits\Http\Requests\DeleteVisitRequest;
use App\Domains\Visits\Http\Requests\StoreVisitRequest;
use App\Domains\Visits\Http\Requests\UpdateStatusRequest;
use App\Domains\Visits\Http\Requests\UpdateVisitRequest;
use App\Domains\Visits\Models\Visit;
use App\Domains\Visits\Models\VisitsStatusesPivot;
use App\Domains\Visits\Services\VisitService;
use App\Domains\Visits\Services\VisitStatusService;
use App\Http\Controllers\Controller;
use App\Models\ModelMorphEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class VisitController extends Controller
{
    public function __construct(
        private VisitService $visitService,
        private VisitStatusService $visitStatusService,
        private UserService $userService,
    )
    {}

    public function index()
    {
        $visitStatus = $this->visitStatusService->getVisible();
        $columns = $this->visitService->getTableColumns();

        return view('backend.content.visits.index',compact('visitStatus','columns'));
    }

    public function show(Request $request, string $visitId) : \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        $visitStatuses = $this->visitStatusService->getVisible();
        $visit = $this->visitService->getByIdWithMorphsAndRelations($visitId, Visit::morphBuilder(), ['company.companyType', 'company.companySource', 'company.country', 'company.lead','company.client','owner','comments','status', 'contacts', 'assignees']);

        $canEditStatus = false;
        $user = $this->userService->getAuthUser();

        $isOwner = $user->getId() == $visit->getOwnerId();

        $isAssignee = !empty(array_filter(
            $visit->getAssignees(),
            fn($assignee) => $assignee->getId() == $user->getId()
        ));

        if ($isOwner || $isAssignee || $user->hasRole(RolesEnum::Administrator->value)) {
            $canEditStatus = true;
        }

        return view('backend.content.visits.show', [
            'visit' => $visit,
            'visitStatuses' => $visitStatuses,
            'canEditStatus' => $canEditStatus
        ]);
    }

    public function create(Request $request, ?string $type = null )
    {
        return view('backend.content.visits.create');
    }

    public function store(StoreVisitRequest $request)
    {
        $visitDTO = Visit::fromRequest($request);
        $visitDTO->setAssignees($request['assignees']);
        $visitDTO->setContacts($request['contacts'] ?? []);

        $visitDTO = $this->visitService->store($visitDTO);

        // store Notes
        $noteRequest = new Request();
        $noteRequest['notableType'] = ModelMorphEnum::VISIT->value;
        $noteRequest['notableId'] = $visitDTO->getId();
        $noteRequest['content'] = $request['note'];

        $noteDTO = Note::fromRequest($noteRequest);

        $noteService = app(NoteService::class);
        $noteService->store($noteDTO);

        // store Files
        $files = $request->file('files') ?? [];
        $fileService = app(FileService::class);

        $fileService->create($files, ModelMorphEnum::VISIT->value, $visitDTO->getId());


        $visitId = $visitDTO->getId();
        if($request['ajax'] && $visitId){
            return response()->json(['message' => 'Επιτυχής αποθήκευση!', 'status'=>200], 200);
        }elseif ($request['ajax']){
            return response()->json(['message' => 'Υπήρξε κάποιο πρόβλημα κατά την δημιουργία', 'status'=>302], 302);
        }

        if($visitId){
            return redirect()->route('admin.visits.index')->with('success','Επιτυχής αποθήκευση!');
        }
        return redirect()->back()->with('error','Υπήρξε κάποιο πρόβλημα κατά την δημιουργία');
    }

    public function edit(Request $request, string $visitId): \Illuminate\View\View
    {
        $visit = $this->visitService->getByIdWithMorphsAndRelations($visitId, Visit::morphBuilder(), ['company.companyType', 'company.companySource', 'company.users', 'company.lead','company.client','owner','comments','status','blockedBy','contacts', 'assignees']);
        $visitStatus = $this->visitStatusService->getVisible();

        $canEditStatus = false;
        $user = $this->userService->getAuthUser();

        $isOwner = $user->getId() == $visit->getOwnerId();

        $isAssignee = !empty(array_filter(
            $visit->getAssignees(),
            fn($assignee) => $assignee->getId() == $user->getId()
        ));

        if ($isOwner || $isAssignee || $user->hasRole(RolesEnum::Administrator->value)) {
            $canEditStatus = true;
        }

        $companyContacts = $visit->getCompany()->getUsers();
        $visitsContactsIds = [];
        foreach($visit->getContacts() as $contact){
            $visitsContactsIds[] = $contact->getId();
        }

        return view('backend.content.visits.edit', [
            'visit' => $visit,
            'visitStatus' => $visitStatus,
            'canEditStatus' => $canEditStatus,
            'companyContacts' => $companyContacts,
            'visitsContactsIds' => $visitsContactsIds
        ]);

    }

    public function update(UpdateVisitRequest $request , string $visitId)
    {
        if($request['status_id']){
            $visitStatus = $this->visitStatusService->getById($request['status_id']);
        }else{
            $visitStatus = $this->visitService->getById($visitId)->getActiveStatus();
        }

        $visitDTO = Visit::fromRequest($request);
        $visitDTO->setAssignees($request['assignees']);
        $visitDTO->setActiveStatus($visitStatus);
        $visitDTO->setContacts($request['contacts'] ?? []);

        $visitDTO = $this->visitService->update($visitDTO, $visitId);

        if($request['ajax'] && $visitId){
            return response()->json(['message' => 'Επιτυχής αποθήκευση!', 'status'=>200], 200);
        }elseif ($request['ajax']){
            return response()->json(['message' => 'Υπήρξε κάποιο πρόβλημα κατά την ενημέρωση', 'status'=>302], 302);
        }

        if($visitId){
            return redirect()->route('admin.visits.show', $visitId)->with('success','Επιτυχής αποθήκευση!');
        }
        return redirect()->back()->with('error','Υπήρξε κάποιο πρόβλημα κατά την ενημέρωση');

    }

    public function updateStatus(UpdateStatusRequest $request, string $visitId)
    {
        $visit = $this->visitService->getByIdWithMorphsAndRelations($visitId, Visit::morphBuilder(), ['company.companyType', 'company.companySource', 'company.lead','company.client','owner','comments','status','prospects.extraData']);

        $canEditStatus = false;
        $user = $this->userService->getAuthUser();

        $isOwner = $user->getId() == $visit->getOwnerId();

        $isAssignee = !empty(array_filter(
            $visit->getAssignees(),
            fn($assignee) => $assignee->getId() == $user->getId()
        ));

        if ($isOwner || $isAssignee || $user->hasRole(RolesEnum::Administrator->value)) {
            $canEditStatus = true;
        }

        if(!$canEditStatus){
            if($request['ajax']){
                return response()->json(['message' => 'Δεν υπάρχουν σωστά δικαιώματα', 'status'=>302], 302);
            }
            abort(403, 'Δεν υπάρχουν σωστά δικαιώματα');
        }


        $visitsStatusesDTO = new VisitsStatusesPivot();
        $visitsStatusesDTO->setVisitId($visitId);
        $visitsStatusesDTO->setVisitStatusSlug($request['visit_status']);

        $visitDTO = $this->visitService->updatePivotPositionAndStatus($visitsStatusesDTO, $visitId);

        if($request['ajax'] && $visitDTO?->getId()){
            $object = new \stdClass();
            $object->visitId = $visitId;
            $object->labelClass = $visitDTO->getActiveStatus()->getLabel();
            $object->labelName = $visitDTO->getActiveStatus()->getName();

            return response()->json(['message' => 'Επιτυχής αποθήκευση!','data' => $object, 'status'=>200], 200);
        }elseif ($request['ajax']){
            return response()->json(['message' => 'Υπήρξε κάποιο πρόβλημα κατά την ενημέρωση', 'status'=>302], 302);
        }

        if($visitDTO?->getId()){
            return redirect()->back()->with('success','Επιτυχής αποθήκευση!');
        }
        return redirect()->back()->with('error','Υπήρξε κάποιο πρόβλημα κατά την ενημέρωση');
    }

    /**
     * @param DeleteVisitRequest $request
     * @param string $id
     * @return JsonResponse|RedirectResponse
     */
    public function destroy(DeleteVisitRequest $request, string $id): JsonResponse|RedirectResponse
    {
        $response = $this->visitService->deleteById($id);

        if($request['ajax'] && $response){
            return response()->json(['message' => 'Επιτυχής διαγραφή', 'status'=>200], 200);
        }elseif ($request['ajax']){
            return response()->json(['message' => 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή', 'status'=>302], 302);
        }

        if($response){
            return redirect()->back()->with('success','Επιτυχής διαγραφή');
        }
        return redirect()->back()->with('error','Υπήρξε κάποιο πρόβλημα κατά την διαγραφή');

    }

}
