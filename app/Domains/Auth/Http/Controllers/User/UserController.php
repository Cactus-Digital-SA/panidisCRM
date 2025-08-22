<?php

namespace App\Domains\Auth\Http\Controllers\User;

use App\Domains\Auth\Http\Requests\User\CreateUserRequest;
use App\Domains\Auth\Http\Requests\User\DeleteUserRequest;
use App\Domains\Auth\Http\Requests\User\EditUserRequest;
use App\Domains\Auth\Http\Requests\User\ManageUserRequest;
use App\Domains\Auth\Http\Requests\User\ReactiveUserRequest;
use App\Domains\Auth\Http\Requests\User\StoreUserRequest;
use App\Domains\Auth\Http\Requests\User\UpdateUserDetailsRequest;
use App\Domains\Auth\Http\Requests\User\UpdateUserPasswordRequest;
use App\Domains\Auth\Http\Requests\User\UpdateUserRequest;
use App\Domains\Auth\Models\UserDetails;
use App\Domains\Auth\Services\PermissionService;
use App\Domains\Auth\Services\RoleService;
use App\Domains\Auth\Services\UserDetailsService;
use App\Domains\Auth\Services\UserService;
use App\Domains\ExtraData\Enums\ExtraDataModelsEnum;
use App\Domains\ExtraData\Services\ExtraDataService;
use App\Http\Controllers\Controller;
use App\Domains\Auth\Models\User;
use App\Models\ModelMorphEnum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(
        private UserService $userService,
        private RoleService $roleService,
        private PermissionService $permissionService,
        private UserDetailsService $userDetailsService ,
        private ExtraDataService $extraDataService
    )
    {}

    private function prepareViewData($request, $status = 0, $active = 1)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => __('locale.Home')], ['name' => __('locale.Users')]
        ];

        $role = $request->input('role');
        $roles = $this->roleService->get();


        return [
            'breadcrumbs' => $breadcrumbs,
            'status' => $status,
            'active' => $active,
            'roles' => $roles,
            'selectedRole' => $role,
        ];
    }

    public function index(ManageUserRequest $request)
    {
        return view('backend.auth.users.index', $this->prepareViewData($request));
    }

    public function deleted(ManageUserRequest $request)
    {
        return view('backend.auth.users.index', $this->prepareViewData($request, 1));
    }

    public function deactivated(ManageUserRequest $request)
    {
        return view('backend.auth.users.index', $this->prepareViewData($request, 0, 0));
    }

    public function create(CreateUserRequest $request)
    {
        $extraData = $this->extraDataService->getByModel(ExtraDataModelsEnum::USER);

        return view('backend.auth.users.create', compact('extraData'))
            ->withRoles($this->roleService->get())
            ->withCategories($this->permissionService->getCategorizedPermissions())
            ->withGeneral($this->permissionService->getUncategorizedPermissions());
    }

    public function store(StoreUserRequest $request)
    {
        $extraDataIds = isset($request['extra_data']) ? array_filter($request['extra_data'], fn($value) => $value !== null) : null;

        $userDTO = new User();
        $userDTO->setName($request['firstName'] . ' ' . $request['lastName']);
        $userDTO->setEmail($request['email']);
        $userDTO->setPassword($request['password'] ?? null);
        $userDTO->setEmailVerifiedAt($request['email_verified'] ? now() : null);
        $userDTO->setActive($request['active'] ?? false);
        $userDTO->setRoles($request['roles'] ?? []);
        $userDTO->setPermissions($request['permissions'] ?? []);
        $userDTO->setExtraDataIds($extraDataIds ?? []);

        $user = $this->userService->store($userDTO);


        $userDetailsDTO = UserDetails::fromRequest($request);
        $userDetailsDTO->setUserId($user->getId());

        $this->userDetailsService->createOrUpdateByUserId($userDetailsDTO, $user->getId());

        return redirect()->route('admin.users.index')->with('success', 'Ο χρήστης δημιουργήθηκε με επιτυχία');
    }

    public function show(Request $request, string $userId)
    {
        return redirect()->route('admin.users.edit', $userId);
    }

    /**
     * @param EditUserRequest $request
     * @param string $userId
     * @return mixed
     */
    public function edit(EditUserRequest $request, string $userId)
    {
        $extraData = $this->extraDataService->getByModel(ExtraDataModelsEnum::USER);
        $cactusUser = $this->userService->getByIdWithMorphsAndRelations($userId, User::morphBuilder(), ['roles','permissions','userDetails','notes','notes.user','extraData']);
        $authUser = $this->userService->getAuthUser();

        return view('backend.auth.users.edit', compact('extraData'))
            ->withUser($cactusUser)
            ->withAuthUser($authUser)
            ->withRoles($this->roleService->get())
            ->withUserRoles($this->roleService->getRolesIdByUserId($userId))
            ->withCategories($this->permissionService->getCategorizedPermissions())
            ->withGeneral($this->permissionService->getUncategorizedPermissions())
            ->withUsedPermissions($this->permissionService->getUserPermissionId($userId));
    }

    public function update(UpdateUserRequest $request, string $userId)
    {
        $extraDataIds = isset($request['extra_data']) ? array_filter($request['extra_data'], fn($value) => $value !== null) : null;

        $userDTO = new User();
        $userDTO->setName($request['firstName'] . ' ' . $request['lastName']);
        $userDTO->setEmail($request['email']);
        $userDTO->setRoles($request['roles'] ?? []);
        $userDTO->setPermissions($request['permissions'] ?? []);
        $userDTO->setEmailVerifiedAt($request['emailVerified'] ? now() : null);
        $userDTO->setExtraDataIds($extraDataIds ?? []);

        $user = $this->userService->update($userDTO, $userId,true);


        $userDetailsDTO = UserDetails::fromRequest($request);

        $this->userDetailsService->createOrUpdateByUserId($userDetailsDTO, $userId);

        return redirect()->route('admin.users.edit', $user->getId())->with('success', 'Ο χρήστης ενημερώθηκε με επιτυχία');
    }


    public function editPassword(Request $request, string $userId)
    {
        $cactusUser = $this->userService->getById($userId);
        $authUser = $this->userService->getAuthUser();

        return view('backend.auth.users.edit_password')
            ->withAuthUser($authUser)
            ->withUser($cactusUser);
    }
    public function updatePassword(UpdateUserPasswordRequest $request, string $userId)
    {
        $userDTO = new User();
        $userDTO->setPassword($request['password']);
        $this->userService->updatePassword($userDTO, $userId);

        return redirect()->route('admin.users.index')->with('success','Ο κωδικός του χρήστη ενημερώθηκε με επιτυχία');
    }

    public function delete(DeleteUserRequest $request, string $userId)
    {

        $response = $this->userService->deleteById($userId);
        if ($response) {
            return redirect()->route('admin.users.index')->with('success', 'Ο χρήστης διαγράφτηκε με επιτυχία');
        }

        return redirect()->route('admin.users.index')->with('error', 'Ο χρήστης δεν μπόρεσε να διαγραφεί!');
    }

    public function restore(ReactiveUserRequest $request)
    {
        $response = $this->userService->restore($request['revert-id']);
        if ($response) {
            return redirect()->back()->with('success', 'Ο χρήστης επαναφέρθηκε');
        }

        return redirect()->back()->with('danger', 'Δεν έγινε επαναφορά του χρήστη!');
    }

    public function updateActive(ReactiveUserRequest $request, string $userId, bool $active)
    {
        $response = $this->userService->updateActive($userId, $active);

        if (!$response) {
            return redirect()->back()->with('error', 'Δεν μπορείς να απενεργοποιήσεις αυτόν τον χρήστη');
        }

        $message = 'Απενεργοποιήθηκε';
        if ($active) {
            $message = 'Ενεργοποιήθηκε';
        }

        return redirect()->back()->with('success', 'Ο χρήστης ' . $message);
    }

    /**
     * @param UpdateUserDetailsRequest $request
     * @param string $userId
     * @return RedirectResponse
     */
    public function updateUserDetails(UpdateUserDetailsRequest $request, string $userId): RedirectResponse
    {
        $userDetailsDTO = UserDetails::fromRequest($request);

        $this->userDetailsService->createOrUpdateByUserId($userDetailsDTO, $userId);

        return redirect()->route('admin.users.edit', $userId)->with('success', 'Ο χρήστης ενημερώθηκε με επιτυχία');
    }


    /**
     * @param Request $request
     * @param string $model
     * @param string $id
     * @return RedirectResponse
     */
    public function sync(Request $request, string $model, string $id) : RedirectResponse
    {
        $request->validate([
            'assignees' => 'array|nullable',
            'assignees.*' => 'exists:users,id',
        ]);

        $assignees = $request->assignees ?: [];

        $this->userService->sync($assignees, $model, $id);

        return redirect()->back()->with('success', 'Οι χρήστες ανανεώθηκαν.');
    }


}
