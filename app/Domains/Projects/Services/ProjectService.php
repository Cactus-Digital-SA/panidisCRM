<?php

namespace App\Domains\Projects\Services;

use App\Domains\Auth\Services\UserService;
use App\Domains\Notifications\Models\CactusNotification;
use App\Domains\Notifications\Models\EmailNotification;
use App\Domains\Notifications\Models\Recipient;
use App\Domains\Notifications\Services\NotificationsService;
use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Repositories\ProjectRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ProjectService
{

    /**
     * @param ProjectRepositoryInterface $repository
     * @param UserService $userService
     */
    public function __construct(
        private ProjectRepositoryInterface $repository,
    )
    {}

    /**
     * @param string $id
     * @param bool $withRelations
     * @return Project|null
     */
    public function getById(string $id, bool $withRelations = true) : ?Project
    {
        return $this->repository->getById($id, $withRelations);
    }

    /**
     * @param Project $project
     * @return Project|null
     */
    public function store(Project $project): ?Project
    {
        $project = $this->repository->store($project);

        if($project->getId()){
            $this->sendEmailNotificationNewProject($project->getId());
        }

        return $project;
    }

    public function update(Project $entity, string $projectId): ?Project
    {
        $oldProject = $this->repository->getById($projectId);
        $project = $this->repository->update($entity, $projectId);

        if($oldProject->getActiveStatus()->getId() !== $entity->getActiveStatus()->getId()){
            $this->sendEmailNotificationProjectStatus($project->getId());
        }

        return $project;
    }

    public function assignTicket(string $projectId, string $ticketId): ?Project
    {
        return $this->repository->assignTicket($projectId, $ticketId);
    }


    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id) : bool
    {
        return $this->repository->deleteById($id);
    }

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableProjects(array $filters) : JsonResponse
    {
        return $this->repository->dataTableProjects($filters);
    }

    /**
     * @return array
     */
    public function getTableColumns() : array
    {
        return $this->repository->getTableColumns();
    }

    /**
     * @param string $id
     * @param array $morphs
     * @return Project
     */
    public function getByIdWithMorphs(string $id, array $morphs = []): Project
    {
        return $this->repository->getByIdWithMorphs($id, $morphs);
    }

    /**
     * @param string $id
     * @param array $morphs
     * @param array $relations
     * @return Project
     */
    public function getByIdWithMorphsAndRelations(string $id, array $morphs = [], array $relations = []): Project
    {
        return $this->repository->getByIdWithMorphsAndRelations($id, $morphs, $relations);
    }

    public function sendEmailNotificationNewProject(string $projectId, array $oldAssignees = []): void
    {
        $project = $this->repository->getById($projectId);
        $userService = app(UserService::class);
        $authUser = $userService->getAuthUser();

        $recipients = [];

        if($authUser->getId() != $project->getOwnerId()) {
            $recipients[] = new Recipient($project->getOwner()->getEmail(),$project->getOwner()->getName());
        }
        foreach ($project->getAssignees() as $assignee){
            if($assignee->getId() != $authUser->getId()) {
                $assigneeId = $assignee->getId();

                if ($assigneeId != $authUser->getId() && !in_array($assigneeId, $oldAssignees) ) {
                    $recipients[] = new Recipient($assignee->getEmail(), $assignee->getName());
                }

            }
        }

        try {
            $emailDTO = new EmailNotification();
            $emailDTO->setRecipients($recipients);

            $emailDTO->setSubject("Έχεις καινούργιο Project: ". $project->getName());

            $emailDTO->addBody("Έχεις ένα καινούργιο Project: <strong>". $project->getName() ." </strong> <br>");

            if($project->getDeadline() !== null){
                $emailDTO->addBody("με deadline: <strong>". $project->getDeadline()->format('d/m/Y') ." </strong> <br>");
            }

            $emailDTO->addAction('Δες το Project', route('admin.projects.show',[$project->getTypeId(),$project->getId()]), 'btn-primary');

            $cactusNotification = new CactusNotification([$emailDTO]);

            // Αποστολή Ειδοποίησης
            $notificationService = new NotificationsService();
            $notificationService->send($cactusNotification);

        } catch (\Exception $e) {

            \Log::error('email error: '. $e->getMessage());
        }

    }


    public function sendEmailNotificationProjectStatus(string $projectId): void
    {
        $project = $this->repository->getById($projectId);
        $userService = app(UserService::class);
        $authUser = $userService->getAuthUser();

        $recipients = [];

        if($authUser->getId() != $project->getOwnerId()) {
            $recipients[] = new Recipient($project->getOwner()->getEmail(),$project->getOwner()->getName());
        }
        foreach ($project->getAssignees() as $assignee){
            if($assignee->getId() != $authUser->getId()) {
                $recipients[] = new Recipient($assignee->getEmail(),$assignee->getName());
            }
        }

//        $recipients[] = new Recipient($project->getCreatedByUser()->getEmail(),$project->getCreatedByUser()->getName());
//        $recipients[] = new Recipient($project->getOwner()->getEmail(),$project->getOwner()->getName());

        try {
            $emailDTO = new EmailNotification();
            $emailDTO->setRecipients($recipients);

            $emailDTO->setSubject("Αλλαγή κατάστασης στο Project: ". $project->getName());

            $emailDTO->addBody("Η κατάσταση στο Project: <strong>". $project->getName() ." </strong> <br> άλλαξε σε: <strong>". $project->getActiveStatus()->getName() ." </strong> <br>" );

            $emailDTO->addAction('Δες το Project', route('admin.projects.show',[$project->getTypeId(),$project->getId()]), 'btn-primary');

            $cactusNotification = new CactusNotification([$emailDTO]);

            // Αποστολή Ειδοποίησης
            $notificationService = new NotificationsService();
            $notificationService->send($cactusNotification);

        } catch (\Exception $e) {

            \Log::error('email error: '. $e->getMessage());
        }

    }
}
