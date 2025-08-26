<?php

namespace App\Domains\Clients\Http\Controllers;

use App\Domains\Companies\Models\Company;
use App\Domains\Clients\Http\Requests\DeleteClientRequest;
use App\Domains\Clients\Http\Requests\ManageClientRequest;
use App\Domains\Clients\Http\Requests\ShowClientRequest;
use App\Domains\Clients\Http\Requests\StoreClientRequest;
use App\Domains\Clients\Http\Requests\UpdateClientRequest;
use App\Domains\Clients\Http\Requests\EditClientRequest;
use App\Domains\Clients\Models\Client;
use App\Domains\Clients\Services\ClientService;
use App\Domains\CountryCodes\Services\CountryCodeService;
use App\Domains\ExtraData\Enums\ExtraDataModelsEnum;
use App\Domains\ExtraData\Services\ExtraDataService;
use App\Domains\Tags\Services\TagService;
use App\Http\Controllers\Controller;
use App\Models\ModelMorphEnum;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Domains\Companies\Services\CompanyService;
use App\Domains\CompanyTypes\Services\CompanyTypeService;
use App\Domains\Auth\Services\UserService;

final class ClientController extends Controller
{
    public function __construct(
        protected ClientService $clientService,
        protected CompanyService $companyService,
        protected CompanyTypeService $companyTypeService,
        protected UserService $userService,
        private ExtraDataService $extraDataService,
//        private TagService $tagService,
        private CountryCodeService $countryCodeService,
    ){}

    /**
     * Displays the list of all clients
     *
     * @param ManageClientRequest $request
     * @return View
     */
    public function index(ManageClientRequest $request): View
    {
        $columns = $this->clientService->getTableColumns();

        return view('backend.content.clients.index',compact('columns'));
    }

    /**
     * Shows the details of the client with the given id
     *
     * @param ShowClientRequest $request
     * @param string $clientId
     * @return View
     */
     public function show(ShowClientRequest $request, string $clientId): View
     {
        $client = $this->clientService->getByIdWithMorphsAndRelations($clientId, Client::morphBuilder() ,['projects','company','company.doy','company.companyType','company','company.country']);

        $company = $this->companyService->getById($client->getCompanyId());
        $users = $this->userService->getWithoutRole();

        $contactsColumns =  $this->companyService->getContactsTableColumns() ?? [];

         return view('backend.content.clients.show', compact('client',  'company', 'contactsColumns', 'users'));
     }


    /**
     * Displays the form for creating a new client
     *
     * @return View
     */
    public function create(): View
    {
        $companies = $this->companyService->get();

        $types = $this->companyTypeService->get();

//        $tags = $this->tagService->get();

        $countries = $this->countryCodeService->get();

        return view('backend.content.clients.create', compact('companies', 'types', 'countries'));
    }

    /**
     * Creates a new client in the database and redirects to the clients index page
     *
     * @param StoreClientRequest $request
     * @return RedirectResponse
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        $companyDTO = new Company();

        $companyDTO->setErpId($request->input('erpID'));
        $companyDTO->setName($request->input('newCompanyName'));
        $companyDTO->setTypeId($request->input('typeId'));
        $companyDTO->setEmail($request->input('email'));
        $companyDTO->setPhone($request->input('phone'));
        $companyDTO->setActivity($request->input('activity'));
        $companyDTO->setAddress($request->input('address'));
        $companyDTO->setVat($request->input('vat'));
        $companyDTO->setDoyId($request->input('doyId'));
        $companyDTO->setGemi($request->input('gemi'));
        $companyDTO->setCountryId($request->input('countryId'));
        $companyDTO->setWebsite($request->input('website'));
        $companyDTO->setExtraDataIds($extraDataIds ?? []);

        $company = $this->companyService->createOrUpdateByCompanyId($companyDTO, $request->input('existing_company_id'));

        $leadDTO  = (new Client())->fromRequest($request);
        $leadDTO->setCompanyId($company->getId());

        $this->clientService->store($leadDTO);

        if($company->getId() !== null) {
            // Assign User to Company
            $companyDTO = new Company();
            $companyDTO->setUsers($request['userIds'] ?? []);

            $this->companyService->storeContacts($companyDTO, $company->getId());
        }

        return redirect()->route('admin.clients.index')->with('success', 'Ο πελάτης δημιουργήθηκε με επιτυχία!');
    }

    /**
     * Displays the form for editing a client with the given id
     *
     * @param EditClientRequest $request
     * @param string $clientId
     * @return View
     */
    public function edit(EditClientRequest $request, string $clientId)
    {
        $client = $this->clientService->getById($clientId);

        return view('backend.content.clients.edit', compact('client'));
    }

    /**
     * Updates a client in the database and redirects to the clients index page
     *
     * @param UpdateClientRequest $request
     * @param string $clientId
     * @return RedirectResponse
     */
    public function update(UpdateClientRequest $request, string $clientId): RedirectResponse
    {
        $request = $this->extraDataService->createOrUpdate($request);

        $this->clientService->update((new Client())->fromRequest($request), $clientId);

        return redirect()->route('admin.clients.index')->with('success', 'Ο πελάτης ενημερώθηκε με επιτυχία!');
    }

    /**
     * Deletes a client from the database and redirects to the clients index page
     *
     * @param DeleteClientRequest $request
     * @param string $clientId
     * @return RedirectResponse
     */
    public function destroy(DeleteClientRequest $request, string $clientId): RedirectResponse
    {
        $response = $this->clientService->deleteById($clientId);

        if ($response) {
            return redirect()->route('admin.clients.index')->with('success', 'Ο πελάτης διαγράφηκε με επιτυχία!');
        }

        return redirect()->route('admin.clients.index')->with('error', 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή!');
    }
}
