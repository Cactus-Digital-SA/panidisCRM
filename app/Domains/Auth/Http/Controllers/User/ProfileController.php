<?php

namespace App\Domains\Auth\Http\Controllers\User;

use App\Domains\Auth\Http\Requests\User\UpdateUserPasswordRequest;
use App\Domains\Auth\Services\UserService;
use App\Http\Controllers\Controller;
use App\Domains\Auth\Models\User;
use Illuminate\Http\Request;
use Log;

class ProfileController extends Controller
{
    protected UserService $userService;

    /**
     * @param  UserService  $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show (Request $request){
        $user = $this->userService->getAuthUser();
        return redirect()->route('profile.edit',$user->getId());
    }

    public function edit(Request $request)
    {
        $user = $this->userService->getAuthUser();
        return view('backend.auth.profile.edit')
            ->withUser($user);
    }

    public function update(Request $request, $userId)
    {
        $user = $this->userService->getAuthUser();
        if($user->getId() != $userId){
            Log::critical('Ο χρήστης '. $user->getId().' προσπάθησε να αποθηκεύσει στον χρήστη '. $userId);
            return redirect()->route('profile.edit', $userId)->with('error','Υπήρξε κάποιο πρόβλημα κατά την αποθήκευση.');
        }

        $userDTO = new User();
        $userDTO->setName($request['name']);
        $userDTO->setEmail($request['email']);

        $this->userService->update($userDTO, $userId);

        return redirect()->route('profile.edit', $userId)->with('success', 'Η ενημέρωση έγινε με επιτυχία!');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function editPassword(Request $request)
    {
        $user = $this->userService->getAuthUser();
        return view('backend.auth.users.edit_password')
            ->withUser($user);
    }
    public function updatePassword(UpdateUserPasswordRequest $request, string $userId)
    {
        $user = $this->userService->getAuthUser();
        if($user->getId() != $userId){
            Log::critical('Ο χρήστης '. $user->getId().' προσπάθησε να αποθηκεύσει στον χρήστη '. $userId);
            return redirect()->route('profile.edit', $userId)->with('error','Υπήρξε κάποιο πρόβλημα κατά την αποθήκευση.');
        }

        $userDTO = new User();
        $userDTO->setPassword($request['password']);

        $this->userService->updatePassword($userDTO, $userId);

        return redirect()->route('profile.edit',$userId)->with('success','Ο κωδικός του χρήστη ενημερώθηκε με επιτυχία');
    }


}
