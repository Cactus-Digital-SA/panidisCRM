<?php

namespace App\Domains\Companies\Http\Controllers;

use App\Domains\Auth\Models\User;
use App\Domains\Auth\Models\UserDetails;
use App\Domains\Auth\Services\UserDetailsService;
use App\Domains\Auth\Services\UserService;
use App\Domains\Companies\Http\Requests\DeleteAssignUserRequest;
use App\Domains\Companies\Http\Requests\DeleteCompanyRequest;
use App\Domains\Companies\Http\Requests\EditCompanyRequest;
use App\Domains\Companies\Http\Requests\ManageCompanyRequest;
use App\Domains\Companies\Http\Requests\StoreCompanyRequest;
use App\Domains\Companies\Http\Requests\UpdateCompanyRequest;
use App\Domains\Companies\Models\Company;
use App\Domains\Companies\Services\CompanyService;
use App\Domains\CompanyTypes\Services\CompanyTypeService;
use App\Domains\CountryCodes\Services\CountryCodeService;
use App\Domains\ExtraData\Enums\ExtraDataModelsEnum;
use App\Domains\ExtraData\Services\ExtraDataService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class CompanyController extends Controller
{

    public function __construct(
        protected CompanyService $companyService,
        protected CompanyTypeService $companyTypeService,
        protected UserService $userService,
        private UserDetailsService $userDetailsService ,
        private ExtraDataService $extraDataService,
        private CountryCodeService $countryCodeService,
    ){}


    /**
     * @param ManageCompanyRequest $request
     * @return View
     */
    public function index(ManageCompanyRequest $request): View
    {
       // todo
    }

    /**
     * @param EditCompanyRequest $request
     * @param string $companyId
     * @return View
     */
    public function show(EditCompanyRequest $request, string $companyId): View|JsonResponse
    {
        // todo
    }

    /**
     * @return View
     */
    public function create(): View
    {
        // todo
    }

    /**
     * Summary of store
     * @param \App\Domains\Companies\Http\Requests\StoreCompanyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        // todo
    }

    /**
     * Summary of edit
     * @param \App\Domains\Companies\Http\Requests\EditCompanyRequest $request
     * @param string $companyId
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(EditCompanyRequest $request, string $companyId)
    {
        $company = $this->companyService->getById($companyId);
        $types = $this->companyTypeService->get();

        $countries = $this->countryCodeService->get();

        return view('backend.content.companies.edit',compact('company','types', 'countries'));
    }

    /**
     * Summary of update
     * @param \App\Domains\Companies\Http\Requests\UpdateCompanyRequest $request
     * @param string $companyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCompanyRequest $request, string $companyId): RedirectResponse
    {
        $this->companyService->update((new Company())->fromRequest($request), $companyId);

        return redirect()->back()->with('success', 'Η εταιρεία ενημερώθηκε με επιτυχία!');
    }

    /**
     * Summary of destroy
     * @param \App\Domains\Companies\Http\Requests\DeleteCompanyRequest $request
     * @param string $companyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DeleteCompanyRequest $request, string $companyId): RedirectResponse
    {
        $response = $this->companyService->deleteById($companyId);

        if ($response) {
            return redirect()->route('admin.companies.index')->with('success', 'Η εταιρεία διαγράφηκε με επιτυχία!');
        }

        return redirect()->route('admin.companies.index')->with('error', 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή!');
    }

    /**
     * Summary of addContact
     * @param \App\Domains\Companies\Http\Requests\ManageCompanyRequest $request
     * @param string $companyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addContact(ManageCompanyRequest $request, string $companyId): RedirectResponse
    {
        $companyDTO = new Company();
        $companyDTO->setUsers($request['userIds']);

        $this->companyService->storeContacts($companyDTO, $companyId);

        return redirect()->back()->with('success', 'Η επαφή προστέθηκε με επιτυχία!');
    }

    /**
     * @param ManageCompanyRequest $request
     * @param string|null $companyId
     * @return RedirectResponse|JsonResponse
     */
    public function addNewContact(ManageCompanyRequest $request, string $companyId = null): RedirectResponse|JsonResponse
    {
        $extraDataIds = isset($request['extra_data']) ? array_filter($request['extra_data'], fn($value) => $value !== null) : null;

        // Create User
        $userDTO = new User();
        $userDTO->setName($request['firstName'] .' '. $request['lastName']);
        $userDTO->setEmail($request['email']);
        $userDTO->setExtraDataIds($extraDataIds ?? []);

        try {
            $user = $this->userService->store($userDTO);
        }catch (Exception $e) {
            if (str_contains($e->getMessage(), 'Υπάρχει χρήστης με το ίδιο email')) {
                return response()->json([
                    'error_type' => 'duplicate_email',
                    'message' => $e->getMessage()
                ], 422);
            }

            return response()->json([
                'error_type' => 'general',
                'message' => $e->getMessage()
            ], 400);
        }

        // Create User Details
        $request['userId'] = $user->getId();
        $userDetailsDTO = UserDetails::fromRequest($request);

        $this->userDetailsService->createOrUpdateByUserId($userDetailsDTO, $user->getId());

        if($companyId) {
            // Assign User to Company
            $companyDTO = new Company();
            $companyDTO->setUsers([$user->getId()]);

            $this->companyService->storeContacts($companyDTO, $companyId);
        }

        if($request['ajax'] ?? false){
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName()
                ],
                'message' => 'Η επαφή προστέθηκε με επιτυχία!'
            ]);
        }

        return redirect()->back()->with('success', 'Η επαφή προστέθηκε με επιτυχία!');
    }

    /**
     * Summary of deleteContact
     * @param \App\Domains\Companies\Http\Requests\DeleteAssignUserRequest $request
     * @param string $companyId
     * @return RedirectResponse
     */
    public function deleteContact(DeleteAssignUserRequest $request, string $companyId)
    {
        $response = $this->companyService->deleteContactByUserId($request['deleteUserId'], $companyId);

        if ($response) {
            return redirect()->back()->with('success', 'Η επαφή διαγράφηκε');
        }

        return redirect()->back()->with('error', 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή!');
    }

}
