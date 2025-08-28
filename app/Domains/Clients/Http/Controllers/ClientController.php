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
