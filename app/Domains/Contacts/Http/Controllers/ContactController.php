<?php

namespace App\Domains\Contacts\Http\Controllers;

use App\Domains\Auth\Models\User;
use App\Domains\Auth\Models\UserDetails;
use App\Domains\Auth\Services\UserDetailsService;
use App\Domains\Auth\Services\UserService;
use App\Domains\ExtraData\Enums\ExtraDataModelsEnum;
use App\Domains\ExtraData\Services\ExtraDataService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(protected UserService $userService,
                                private ExtraDataService $extraDataService,
                                private UserDetailsService $userDetailsService)
    {

    }

    public function edit(Request $request, $contactId)
    {
        $user = $this->userService->getById($contactId);
        $extraData = $this->extraDataService->getByModel(ExtraDataModelsEnum::USER);
        session(['previous_url' => url()->previous()]);

        return view('backend.content.contacts.edit', compact('user', 'extraData'));
    }


    public function update(Request $request, $contactId)
    {
        $extraDataIds = isset($request['extra_data']) ? array_filter($request['extra_data'], fn($value) => $value !== null) : null;

        $userDTO = new User();
        $userDTO->setName($request['firstName'] . ' ' . $request['lastName']);
        $userDTO->setEmail($request['email']);
        $userDTO->setExtraDataIds($extraDataIds ?? []);

        $user = $this->userService->update($userDTO, $contactId,true);

        $userDetailsDTO = UserDetails::fromRequest($request);

        $this->userDetailsService->createOrUpdateByUserId($userDetailsDTO, $contactId);

        $redirectUrl = session('previous_url');

        if($redirectUrl){
            return redirect()->to($redirectUrl)->with('success', 'Η επαφή ενημερώθηκε με επιτυχία!');
        }

        return redirect()->back()->with('success', 'Η επαφή ενημερώθηκε με επιτυχία!');
    }

}
