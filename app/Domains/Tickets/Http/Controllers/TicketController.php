<?php

namespace App\Domains\Tickets\Http\Controllers;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Auth\Services\UserService;
use App\Domains\Files\Services\FileService;
use App\Domains\Notes\Models\Note;
use App\Domains\Notes\Services\NoteService;
use App\Domains\Tickets\Http\Requests\DeleteTicketRequest;
use App\Domains\Tickets\Http\Requests\StoreTicketRequest;
use App\Domains\Tickets\Http\Requests\UpdateStatusRequest;
use App\Domains\Tickets\Http\Requests\UpdateTicketRequest;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Tickets\Models\TicketsStatusesPivot;
use App\Domains\Tickets\Services\TicketService;
use App\Domains\Tickets\Services\TicketStatusService;
use App\Http\Controllers\Controller;
use App\Models\ModelMorphEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService,
        private readonly TicketStatusService $ticketStatusService,
        private readonly UserService $userService,
    )
    {}

    /**
     * @return View
     */
    public function index() : View
    {
        $ticketStatus = $this->ticketStatusService->getVisible();
        $columns = $this->ticketService->getTableColumns();

        return view('backend.content.tickets.index',compact('ticketStatus','columns'));
    }

    /**
     * @return View
     */
    public function mine() : View
    {
        $user = $this->userService->getAuthUser();
        $mine = $user->getId();

        $ticketStatus = $this->ticketStatusService->getVisible();
        $columns = $this->ticketService->getTableColumns();

        return view('backend.content.tickets.index',compact('mine','ticketStatus','columns'));
    }

    public function assignedByMe()
    {
        $user = $this->userService->getAuthUser();
        $assignedBy = $user->getId();

        $ticketStatus = $this->ticketStatusService->getVisible();
        $columns = $this->ticketService->getTableColumns();

        return view('backend.content.tickets.index',compact('assignedBy','ticketStatus','columns'));
    }

    /**
     * @param Request $request
     * @param string $id
     * @return View
     */
    public function show(Request $request, string $ticketId) : View
    {
        $ticketStatuses = $this->ticketStatusService->getVisible();
        $ticket = $this->ticketService->getByIdWithMorphsAndRelations($ticketId, Ticket::morphBuilder(), ['company.companyType', 'company.companySource', 'company.country', 'company.lead','company.client','owner','comments','status', 'contacts']);

        $canEditStatus = false;
        $user = $this->userService->getAuthUser();

        $isOwner = $user->getId() == $ticket->getOwnerId();

        $isAssignee = !empty(array_filter(
            $ticket->getAssignees(),
            fn($assignee) => $assignee->getId() == $user->getId()
        ));

        if ($isOwner || $isAssignee || $user->hasRole(RolesEnum::Administrator->value)) {
            $canEditStatus = true;
        }

        return view('backend.content.tickets.' . strtolower($type->value) .'.show', [
            'type' => $type,
            'ticket' => $ticket,
            'ticketStatuses' => $ticketStatuses,
            'canEditStatus' => $canEditStatus
        ]);

    }

    public function create(Request $request, ?string $type = null )
    {
       return redirect()->route('admin.tickets.index');
    }

    public function store(StoreTicketRequest $request)
    {
        $ticketDTO = Ticket::fromRequest($request);
        $ticketDTO->setAssignees($request['assignees']);
        $ticketDTO->setContacts($request['contacts']);

        $ticketDTO = $this->ticketService->store($ticketDTO);

        // store Notes
        $noteRequest = new Request();
        $noteRequest['notableType'] = ModelMorphEnum::TICKET->value;
        $noteRequest['notableId'] = $ticketDTO->getId();
        $noteRequest['content'] = $request['note'];

        $noteDTO = Note::fromRequest($noteRequest);

        $noteService = app(NoteService::class);
        $noteService->store($noteDTO);

        // store Files
        $files = $request->file('files') ?? [];
        $fileService = app(FileService::class);

        $fileService->create($files, ModelMorphEnum::TICKET->value, $ticketDTO->getId());

        $ticketId = $ticketDTO->getId();

        if($ticketId !== null && $request['contacts'] !== null) {
            // Assign User to Company
            $ticketDTO = new Ticket();
            $ticketDTO->setContacts($request['contacts']);

            $this->ticketService->storeContacts($ticketDTO, $ticketId);
        }

        if($request['ajax'] && $ticketId){
            return response()->json(['message' => 'Το ticket δημιουργήθηκε με επιτυχία', 'status'=>200], 200);
        }elseif ($request['ajax']){
            return response()->json(['message' => 'Υπήρξε κάποιο πρόβλημα κατά την δημιουργία', 'status'=>302], 302);
        }

        if($ticketId){
            return redirect()->back()->with('success','Το ticket δημιουργήθηκε με επιτυχία');
        }
        return redirect()->back()->with('error','Υπήρξε κάποιο πρόβλημα κατά την δημιουργία');
    }

    public function edit(Request $request, string $ticketId): View
    {
        $ticket = $this->ticketService->getByIdWithMorphsAndRelations($ticketId, Ticket::morphBuilder(), ['company.companyType', 'company.companySource', 'company.users', 'company.lead','company.client','owner','comments','status','blockedBy','contacts']);
        $ticketStatus = $this->ticketStatusService->getVisible();

        $canEditStatus = false;
        $user = $this->userService->getAuthUser();

        $isOwner = $user->getId() == $ticket->getOwnerId();

        $isAssignee = !empty(array_filter(
            $ticket->getAssignees(),
            fn($assignee) => $assignee->getId() == $user->getId()
        ));

        if ($isOwner || $isAssignee || $user->hasRole(RolesEnum::Administrator->value)) {
            $canEditStatus = true;
        }


        return view('backend.content.tickets.edit',compact('ticket','ticketStatus', 'canEditStatus'));
    }

    public function update(UpdateTicketRequest $request , string $ticketId)
    {
        if($request['status_id']){
            $ticketStatus = $this->ticketStatusService->getById($request['status_id']);
        }else{
            $ticketStatus = $this->ticketService->getById($ticketId)->getActiveStatus();
        }

        $ticketDTO = Ticket::fromRequest($request);
        $ticketDTO->setPublic($request['public'] == 'true'?? false);
        $ticketDTO->setBillable($request['billable'] == 'true' ?? false);
        $ticketDTO->setAssignees($request['assignees']);
        $ticketDTO->setActiveStatus($ticketStatus);

        // blocked by
        $blockedByIds = $request['blocked_by_ids'] ?? [];
        $ticketDTO->setBlockedByTickets($blockedByIds);

        $ticketDTO = $this->ticketService->update($ticketDTO, $ticketId);

        if($request['ajax'] && $ticketId){
            return response()->json(['message' => 'Το ticket ενημερώθηκε με επιτυχία', 'status'=>200], 200);
        }elseif ($request['ajax']){
            return response()->json(['message' => 'Υπήρξε κάποιο πρόβλημα κατά την ενημέρωση', 'status'=>302], 302);
        }

        if($ticketId){
            return redirect()->back()->with('success','Το ticket ενημερώθηκε με επιτυχία');
        }
        return redirect()->back()->with('error','Υπήρξε κάποιο πρόβλημα κατά την ενημέρωση');

    }

    public function updateStatus(UpdateStatusRequest $request, string $ticketId)
    {
        $ticket = $this->ticketService->getByIdWithMorphsAndRelations($ticketId, Ticket::morphBuilder(), ['company.companyType', 'company.companySource', 'company.lead','company.client','owner','comments','status','prospects.extraData']);

        $canEditStatus = false;
        $user = $this->userService->getAuthUser();

        $isOwner = $user->getId() == $ticket->getOwnerId();

        $isAssignee = !empty(array_filter(
            $ticket->getAssignees(),
            fn($assignee) => $assignee->getId() == $user->getId()
        ));

        if ($isOwner || $isAssignee || $user->hasRole(RolesEnum::Administrator->value)) {
            $canEditStatus = true;
        }

        if(!$canEditStatus){
            if($request['ajax']){
                return response()->json(['message' => 'Δεν έχετε δικαίωμα για την ενημέρωση του Ticket', 'status'=>302], 302);
            }
            abort(403, 'Δεν έχετε δικαίωμα για την ενημέρωση του Ticket');
        }


        $ticketsStatusesDTO = new TicketsStatusesPivot();
        $ticketsStatusesDTO->setTicketId($ticketId);
        $ticketsStatusesDTO->setTicketStatusSlug($request['ticket_status']);

        $ticketDTO = $this->ticketService->updatePivotPosition($ticketsStatusesDTO, $ticketId);

        if($request['ajax'] && $ticketDTO?->getId()){
            $object = new \stdClass();
            $object->ticketId = $ticketId;
            $object->labelClass = $ticketDTO->getActiveStatus()->getLabel();
            $object->labelName = $ticketDTO->getActiveStatus()->getName();

            return response()->json(['message' => 'Το ticket ενημερώθηκε με επιτυχία','data' => $object, 'status'=>200], 200);
        }elseif ($request['ajax']){
            return response()->json(['message' => 'Υπήρξε κάποιο πρόβλημα κατά την ενημέρωση', 'status'=>302], 302);
        }

        if($ticketDTO?->getId()){
            return redirect()->back()->with('success','Το ticket ενημερώθηκε με επιτυχία');
        }
        return redirect()->back()->with('error','Υπήρξε κάποιο πρόβλημα κατά την ενημέρωση');
    }

    /**
     * @param DeleteTicketRequest $request
     * @param string $id
     * @return JsonResponse|RedirectResponse
     */
    public function destroy(DeleteTicketRequest $request, string $id): JsonResponse|RedirectResponse
    {
        $response = $this->ticketService->deleteById($id);

        if($request['ajax'] && $response){
            return response()->json(['message' => 'Το ticket διαγράφηκε με επιτυχία', 'status'=>200], 200);
        }elseif ($request['ajax']){
            return response()->json(['message' => 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή', 'status'=>302], 302);
        }

        if($response){
            return redirect()->back()->with('success','Το ticket διαγράφηκε με επιτυχία');
        }
        return redirect()->back()->with('error','Υπήρξε κάποιο πρόβλημα κατά την διαγραφή');

    }

}
