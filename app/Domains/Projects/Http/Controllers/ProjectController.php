<?php

namespace App\Domains\Projects\Http\Controllers;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Auth\Services\UserService;
use App\Domains\Files\Services\FileService;
use App\Domains\Notes\Models\Note;
use App\Domains\Notes\Services\NoteService;
use App\Domains\Projects\Http\Requests\DeleteProjectsRequest;
use App\Domains\Projects\Http\Requests\StoreProjectRequest;
use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Services\ProjectService;
use App\Domains\Projects\Services\ProjectStatusService;
use App\Domains\Projects\Services\ProjectTypeService;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Tickets\Services\TicketService;
use App\Http\Controllers\Controller;
use App\Models\ModelMorphEnum;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class ProjectController extends Controller
{

    /**
     * @param ProjectService $projectService
     * @param ProjectTypeService $projectTypeService
     * @param ProjectStatusService $projectStatusService
     * @param UserService $userService
     */
    public function __construct(
        private readonly ProjectService       $projectService,
        private readonly ProjectTypeService   $projectTypeService,
        private readonly ProjectStatusService $projectStatusService,
        private readonly UserService          $userService,
        private readonly TicketService        $ticketService
    )
    {
    }

    /**
     * @param string $typeSlug
     * @return View
     */
    public function index(string $typeSlug): View
    {
        $projectType = $this->projectTypeService->getBySlug($typeSlug);
        $projectStatus = $this->projectStatusService->getVisible();
        $columns = $this->projectService->getTableColumns();

        return view('backend.content.projects.index', compact('columns', 'projectType', 'projectStatus'));
    }

    public function indexCancelled(Request $request, string $typeSlug): View
    {
        $projectStatus = 'cancelled';
        $selectedStatus = $this->projectStatusService->getBySlug($projectStatus);
        $title = 'Ακυρωμένα';

        $projectType = $this->projectTypeService->getBySlug($typeSlug);
        $projectStatus = $this->projectStatusService->getVisible();
        $columns = $this->projectService->getTableColumns();

        return view('backend.content.projects.index', compact('columns', 'projectType', 'projectStatus','selectedStatus', 'title'));
    }

    /**
     * @param string $typeSlug
     * @return View
     */
    public function mine(string $typeSlug): View
    {
        $projectType = $this->projectTypeService->getBySlug($typeSlug);
        $projectStatus = $this->projectStatusService->getVisible();
        $columns = $this->projectService->getTableColumns();

        $mine = Auth::id();

        return view('backend.content.projects.index', compact('mine', 'columns', 'projectType', 'projectStatus'));
    }

    /**
     * @param Request $request
     * @param string $typeSlug
     * @param string $id
     * @return Project|JsonResponse|View
     */
    public function show(Request $request, string $typeSlug, string $id)
    {
        $project = $this->projectService->getByIdWithMorphsAndRelations($id, Project::morphBuilder(), ['owner', 'createdByUser', 'client.company.companyType', 'status', 'type']);
        $userId = auth()->id();

//        $assignees = $project->getAssignees();

//        $hasAccess = collect($assignees)->contains(function ($assignee) use ($userId) {
//            return $assignee->getId() === $userId;
//        });
//
//        if (!$hasAccess && !$this->userService->hasRole($userId, RolesEnum::Administrator->value)) {
//            abort(403, 'Δεν έχετε δικαίωμα πρόσβασης σε αυτό το project.');
//        }


        $projectType = $this->projectTypeService->getBySlug($typeSlug);
        $projectStatus = $this->projectStatusService->getVisible();

        if ($request->ajax()) {
            return $project;
        }

        $defaultTab = $request->get('tab', 'navs-pills-top-details');
//        $company = $project->getClient()->getCompany();
        //dd($project, $project->getMorphables());
        return view('backend.content.projects.show', compact('project', 'projectType', 'projectStatus', 'defaultTab'));
    }

    /**
     * @param string $typeSlug
     * @return View
     */
    public function create(string $typeSlug): View
    {
        $projectType = $this->projectTypeService->getBySlug($typeSlug);
        $projectStatus = $this->projectStatusService->getVisible();

        // exclude finished and cancelled
        $projectStatus = array_filter($projectStatus, function ($status) {
            return !in_array($status->getSlug(), ['finished', 'cancelled']);
        });

        return view('backend.content.projects.create', compact('projectType', 'projectStatus'));
    }

    /**
     * @param StoreProjectRequest $request
     * @param string $typeSlug
     * @return RedirectResponse|JsonResponse
     */
    public function store(StoreProjectRequest $request, string $typeSlug): RedirectResponse|JsonResponse
    {

        $project = Project::fromRequest($request);

        $projectType = $this->projectTypeService->getBySlug($typeSlug);
        if(isset($request['status'])){
            $projectStatus = $this->projectStatusService->getById($request['status']);
        }else{
            $projectStatus = $this->projectStatusService->getBySlug('in-progress' );
        }

        //Todo new for morphs
        if ($request['assignees']) {
            $assignees = $this->userService->getByIds($request['assignees']);
            $project->setAssignees($assignees);
        }

        $project->setTypeId($projectType->getId())
            ->setActiveStatus($projectStatus);

        //dd($project, $request->validated(), $project->getAssignees());
        $this->projectService->store($project);

        return redirect()->route('admin.projects.index', ['type' => $typeSlug])->with('status', 'Τέλεια');
        //dd($request->validated());
    }

    public function assignTicket(Request $request)
    {
        $projectId = $request['morphId'] ?? $request['projectId'] ?? abort(404);

        $project = $this->projectService->getById($projectId);

        $ticketDTO = Ticket::fromRequest($request);
        $ticketDTO->setAssignees($request['assignees']);

        // blocked by
        $blockedByIds = $request['blocked_by_ids'] ?? [];
        $ticketDTO->setBlockedByTickets($blockedByIds);

        $ticketDTO = $this->ticketService->store($ticketDTO);

        $this->projectService->assignTicket($project->getId(), $ticketDTO->getId());

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

        return redirect()->back()->with('status', 'Το ticket δημιουργήθηκε με επιτυχία');
    }

    public function edit(Request $request, string $typeSlug, string $projectId)
    {
        $projectStatus = $this->projectStatusService->getVisible();
        $projectType = $this->projectTypeService->getBySlug($typeSlug);

        $project = $this->projectService->getById($projectId);
        $userId = auth()->id();

//        $assignees = $project->getAssignees();

//        $hasAccess = collect($assignees)->contains(function ($assignee) use ($userId) {
//            return $assignee->getId() === $userId;
//        });
//
//        if (!$hasAccess && !$this->userService->hasRole($userId, RolesEnum::Administrator->value)) {
//            abort(403, 'Δεν έχετε δικαίωμα πρόσβασης σε αυτό το project.');
//        }

        $hasAccessToStatus = false;
        if($this->userService->hasRole($userId, RolesEnum::Administrator->value) || $userId == $project->getOwnerId()) {
            $hasAccessToStatus = true;
        }

        return view('backend.content.projects.edit', compact('project', 'projectType','projectStatus','hasAccessToStatus'));
    }

    public function update(Request $request, string $typeSlug, string $projectId)
    {
        $projectDTO = Project::fromRequest($request);

        $projectType = $this->projectTypeService->getBySlug($typeSlug);
        $projectStatus = $this->projectStatusService->getById($request['status']);

        $projectDTO->setAssignees([]);
        if ($request['assignees']) {
            $assignees = $this->userService->getByIds($request['assignees']);
            $projectDTO->setAssignees($assignees);
        }

        $projectDTO->setTypeId($projectType->getId())
            ->setActiveStatus($projectStatus);

        $this->projectService->update($projectDTO, $projectId);

        return redirect()->back()->with('status', 'Το Project έχει ενημερωθεί');
    }

    /**
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(DeleteProjectsRequest $request,string $id) : RedirectResponse
    {
        $this->projectService->deleteById($id);
        return redirect()->back()->with('status', 'Τέλεια');
    }



}
