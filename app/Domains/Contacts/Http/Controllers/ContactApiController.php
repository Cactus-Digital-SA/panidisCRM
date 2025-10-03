<?php

namespace App\Domains\Contacts\Http\Controllers;

use App\Domains\Auth\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactApiController extends Controller
{
    public function __construct(protected UserService $userService) {}
    public function getContact(Request $request, $contactId) : JsonResponse
    {
        $user = $this->userService->getById($contactId);

        $contact = new \stdClass();
        $contact->id = $user->getId();
        $contact->firstName = $user->getUserDetails()->getFirstName();
        $contact->lastName = $user->getUserDetails()->getLastName();
        $contact->email = $user->getEmail();
        $contact->phone = $user->getUserDetails()->getPhone();

        return response()->json(['success' => true,  'data' => $contact]);
    }
}
