<?php

namespace App\Domains\Tickets\Services;

use App\Domains\Auth\Services\UserService;
use App\Domains\Notifications\Models\CactusNotification;
use App\Domains\Notifications\Models\EmailNotification;
use App\Domains\Notifications\Models\Recipient;
use App\Domains\Notifications\Services\NotificationsService;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Tickets\Models\TicketsStatusesPivot;
use App\Domains\Tickets\Repositories\TicketRepositoryInterface;
use App\Helpers\Enums\ActionTypesEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

readonly class TicketService
{
    /**
     * @param TicketRepositoryInterface $repository
     */
    public function __construct(
        private TicketRepositoryInterface $repository,
    )
    {}

    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $statusId
     * @return Ticket[]
     */
    public function getByStatus(string $statusId): array
    {
        return $this->repository->getByStatus($statusId);
    }

    /**
     * @param string $id
     * @param bool $withRelations
     * @return Ticket|null
     */
    public function getById(string $id, bool $withRelations = true) : ?Ticket
    {
        return $this->repository->getById($id, $withRelations);
    }

    /**
     * @param Ticket $ticket
     * @return Ticket|null
     */
    public function store(Ticket $ticket): ?Ticket
    {
        $ticket = $this->repository->store($ticket);
        return $ticket;
    }

    /**
     * @param Ticket $entity
     * @param string $ticketId
     * @return bool|null
     */
    public function storeContacts(Ticket $entity, string $ticketId): ?bool
    {
        return $this->repository->storeContacts($entity, $ticketId);
    }

    /**
     * @param Ticket $ticket
     * @return Ticket|null
     */
    public function update(Ticket $ticket, string $id): ?Ticket
    {
        return $this->repository->update($ticket, $id);
    }

    public function updatePivotPosition(TicketsStatusesPivot $pivot, string $ticketId): ?Ticket
    {
        $ticket = $this->repository->updatePivotPosition($pivot, $ticketId);
        $this->sendEmailNotificationChangeStatus($ticketId);

        return $ticket;
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
    public function dataTableTickets(array $filters) : JsonResponse
    {
        return $this->repository->dataTableTickets($filters);
    }

    /**
     * @return array
     */
    public function getTableColumns(?ActionTypesEnum $type = null) : array
    {
        return $this->repository->getTableColumns($type);
    }

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function searchPaginated(?string $searchTerm, int $offset, int $resultCount): array
    {
        return $this->repository->searchPaginated($searchTerm, $offset, $resultCount);
    }

    /**
     * @param string $id
     * @param array $morphs
     * @return Ticket
     */
    public function getByIdWithMorphs(string $id, array $morphs = []): Ticket
    {
        return $this->repository->getByIdWithMorphs($id, $morphs);
    }


    /**
     * @param string $id
     * @param array $morphs
     * @param array $relations
     * @return Ticket
     */
    public function getByIdWithMorphsAndRelations(string $id, array $morphs = [], array $relations = []): Ticket
    {
        return $this->repository->getByIdWithMorphsAndRelations($id, $morphs, $relations);
    }

    public function sendEmailNotificationNewTicket(string $ticketId, array $oldAssignees = []): void
    {
        $ticket = $this->repository->getById($ticketId);
        $userService = app(UserService::class);
        $authUser = $userService->getAuthUser();

        $recipients = [];

        if($authUser->getId() != $ticket->getOwnerId()) {
            $recipients[] = new Recipient($ticket->getOwner()->getEmail(),$ticket->getOwner()->getName());
        }
        foreach ($ticket->getAssignees() as $assignee){
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

            $emailDTO->setSubject("Έχεις καινούργιο Ticket: ". $ticket->getName());

            $emailDTO->addBody("Έχεις ένα καινούργιο Ticket: <strong>". $ticket->getName() ." </strong> <br>");

            if(isset($ticket->getNotes()[0])){
                $emailDTO->addBody("και περιεχόμενο: <br><strong>". nl2br(e($ticket->getNotes()[0]->getContent()))  ." </strong> <br>");
            }

            $emailDTO->addBody("με deadline: <strong>". $ticket->getDeadline()?->format('d/m/Y') ?? ' - ' ." </strong> <br>");
            $emailDTO->addAction('Δες το Ticket', route('admin.tickets.show', $ticket->getId()), 'btn-primary');

            $cactusNotification = new CactusNotification([$emailDTO]);

            // Αποστολή Ειδοποίησης
            $notificationService = new NotificationsService();
            $notificationService->send($cactusNotification);

        } catch (\Exception $e) {
            \Log::error('email error: '. $e->getMessage());

        }

    }

    public function sendEmailNotificationChangeStatus(string $ticketId): void
    {
        $ticket = $this->repository->getById($ticketId);
        $userService = app(UserService::class);
        $authUser = $userService->getAuthUser();

        $recipients = [];

        if($authUser->getId() != $ticket->getOwnerId()) {
            $recipients[] = new Recipient($ticket->getOwner()->getEmail(),$ticket->getOwner()->getName());
        }
        foreach ($ticket->getAssignees() as $assignee){
            if($assignee->getId() != $authUser->getId()) {
                $recipients[] = new Recipient($assignee->getEmail(),$assignee->getName());
            }
        }

        try {
            $emailDTO = new EmailNotification();
            $emailDTO->setRecipients($recipients);

            $emailDTO->setSubject("Ένα Ticket άλλαξε κατάσταση: ". $ticket->getName());

            $emailDTO->addBody("Το Ticket με τίτλο: <strong>". $ticket->getName() ." </strong> <br>");
            $emailDTO->addBody("Άλλαξε κατάσταση σε: <strong>". $ticket->getActiveStatus()->getName()." </strong> <br>");
            $emailDTO->addBody("από τον/την: <strong>". $authUser->getName() ." </strong> <br>");
            $emailDTO->addAction('Δες το Ticket', route('admin.tickets.show', $ticket->getId()), 'btn-primary');

            $cactusNotification = new CactusNotification([$emailDTO]);

            // Αποστολή Ειδοποίησης
            $notificationService = new NotificationsService();
            $notificationService->send($cactusNotification);

        } catch (\Exception $e) {
            \Log::error('email error: '. $e->getMessage());

        }

    }
}
