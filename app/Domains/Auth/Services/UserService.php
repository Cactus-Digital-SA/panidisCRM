<?php

namespace App\Domains\Auth\Services;

use App\Domains\Auth\Repositories\UserRepositoryInterface;
use App\Domains\Notifications\Models\CactusNotification;
use App\Domains\Notifications\Models\EmailNotification;
use App\Domains\Notifications\Models\Recipient;
use App\Domains\Notifications\Services\NotificationsService;
use App\Domains\Auth\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use PragmaRX\Google2FA\Google2FA;

/**
 * Class UserService.
 */
class UserService
{
    private UserRepositoryInterface $repository;

    /**
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return User[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @return User|null
     */
    public function getAuthUser(): ?User
    {
        return $this->repository->getAuthUser();
    }

    /**
     * @param int $userId
     * @param string|int $role
     * @return bool
     */
    public function hasRole(int $userId, string|int $role): bool
    {
        return $this->repository->hasRole($userId, $role);
    }

    /**
     * @param string $id
     * @return User
     */
    public function getById(string $id): User
    {
        return $this->repository->getById($id);
    }

    /**
     * @param string $uuid
     * @return User
     */
    public function getByUuid(string $uuid): User
    {
        return $this->repository->getByUuid($uuid);
    }

    public function getByIdWithMorphsAndRelations(string $id, array $morphs = [], array $relations = []): User
    {
        return $this->repository->getByIdWithMorphsAndRelations($id, $morphs, $relations);
    }

    /**
     * @param array $ids
     * @return User[]|null
     */
    public function getByIds(array $ids): ?array
    {
        return $this->repository->getByIds($ids);
    }

    /**
     * @param string $email
     * @return ?User
     */
    public function getByEmail(string $email): ?User
    {
        return $this->repository->getByEmail($email);
    }

    /**
     * @param string $roleId
     * @return User[]
     */
    public function getByRoleId(string $roleId): array
    {
        return $this->repository->getByRoleId($roleId);
    }


    /**
     * @return User[]
     */
    public function getWithoutRole(): array
    {
        return $this->repository->getWithoutRole();
    }

    /**
     * @param User $entity
     * @return User
     */
    public function store(User $entity): User
    {
        return $this->repository->store($entity);
    }

    /**
     * @param User $entity
     * @param string $id
     * @param bool $updateRole
     * @return User
     */
    public function update(User $entity, string $id, bool $updateRole = false): User
    {
        return $this->repository->update($entity, $id, $updateRole);
    }

    /**
     * @param User $entity
     * @param string $userId
     * @param $expired
     * @return User|null
     */
    public function updatePassword(User $entity, string $userId, $expired = false): ?User
    {
        return $this->repository->updatePassword($entity, $userId, $expired);
    }

    /**
     * @param string $userId
     * @param UploadedFile $photo
     * @return User|null
     */
    public function updateProfileImage(string $userId, UploadedFile $photo): ?User
    {
        return $this->repository->updateProfileImage($userId, $photo);
    }

    /**
     * @param string $userId
     * @return bool
     */
    public function deleteProfilePhoto(string $userId): bool
    {
        return $this->repository->deleteProfilePhoto($userId);
    }

    /**
     * @param string $userId
     * @param bool $active
     * @return bool
     */
    public function updateActive(string $userId, bool $active): bool
    {
        return $this->repository->updateActive($userId, $active);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool
    {
        return $this->repository->deleteById($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function restore(string $id): bool
    {
        return $this->repository->restore($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function destroy(string $id): bool
    {
        return $this->repository->destroyById($id);
    }

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function emailsPaginated(?string $searchTerm, int $offset, int $resultCount, bool $onlyContacts = false): array
    {
        return $this->repository->emailsPaginated($searchTerm, $offset, $resultCount, $onlyContacts);
    }

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function namesPaginated(?string $searchTerm, int $offset, int $resultCount, bool $onlyContacts = false): array
    {
        return $this->repository->namesPaginated($searchTerm, $offset, $resultCount, $onlyContacts);
    }

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function usersDatatable(array $filters): JsonResponse
    {
        return $this->repository->usersDatatable($filters);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function apiAuthentication(User $user): JsonResponse
    {
        return $this->repository->apiAuthentication($user);
    }

    /**
     * @param string $email
     * @param string $password
     * @return JsonResponse
     */
    public function apiGetUser(string $email, string $password): JsonResponse
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        return $this->apiAuthentication($user);
    }


    /**
     * @return JsonResponse
     */
    public function apiLogOut(): JsonResponse
    {
        return $this->repository->apiLogOut();
    }


    /**
     * @param string $code
     * @return bool
     */
    public function confirmTwoFactorAuth(string $code): bool
    {
        return $this->repository->confirmTwoFactorAuth($code);
    }

    /**
     * @param string $userId
     * @return bool
     * @throws Exception
     */
    public function sendOTP(string $userId): bool
    {
        $user = $this->getById($userId);

        try {
            $currentOTP = app(Google2FA::class)->getCurrentOtp(decrypt($user->getTwoFactorSecret()));

            $recipients[] = new Recipient($user->getEmail(), $user->getName());

            $OTPEmailDTO = new EmailNotification();
            $OTPEmailDTO->setRecipients($recipients);
            $OTPEmailDTO->setSubject('Κωδικός μιας χρήσης');
            $OTPEmailDTO->setBody('Ο κωδικός μιας χρήσης είναι <strong>'.$currentOTP.'</strong> <br> <br> Ο κωδικός θα λήξει σε 2 λεπτά <br> <br> Εάν δεν έχετε προσπαθήσει να συνδεθείτε, αγνοήστε αυτό το μήνυμα.');
            $OTPEmailDTO->setAction('Συνδεθείτε εδώ', route('two-factor.login'));

            $cactusNotification = new CactusNotification([$OTPEmailDTO]);

            // Αποστολή Ειδοποίησης
            $notificationService = new NotificationsService();
            $notificationService->send($cactusNotification);

            return true;
        }catch (Exception $e) {
            \Log::error('OTP email error: '.$e);
        }


        return false;
    }


    /**
     * @param array $assignees
     * @param string $model
     * @param string $id
     * @return bool
     */
    public function sync(array $assignees, string $model, string $id) : bool
    {
        return $this->repository->sync($assignees,$model,$id);
    }

}
