<?php

namespace App\Domains\Leads\Http\Controllers;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Companies\Models\Company;
use App\Domains\CompanySource\Services\CompanySourceService;
use App\Domains\CountryCodes\Services\CountryCodeService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Domains\Leads\Models\Lead;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Domains\Leads\Services\LeadService;
use App\Domains\Companies\Services\CompanyService;
use App\Domains\Leads\Http\Requests\EditLeadRequest;
use App\Domains\Leads\Http\Requests\StoreLeadRequest;
use App\Domains\Leads\Http\Requests\DeleteLeadRequest;
use App\Domains\Leads\Http\Requests\ManageLeadRequest;
use App\Domains\Leads\Http\Requests\UpdateLeadRequest;
use App\Domains\CompanyTypes\Services\CompanyTypeService;
use App\Domains\Auth\Services\UserService;

final class LeadController extends Controller
{
    public function __construct(
        protected LeadService $leadService,
        protected CompanyService $companyService,
        protected CompanyTypeService $companyTypeService,
        protected CompanySourceService $companySourceService,
        protected UserService $userService,
        protected CountryCodeService $countryCodeService,
    ){}

    /**
     * @param ManageLeadRequest $request
     * @return Factory|\Illuminate\Contracts\View\View|Application|View
     */
    public function index(ManageLeadRequest $request)
    {
        $columns = $this->leadService->getTableColumns();

        return view('backend.content.leads.index',compact('columns'));
    }

    /**
     * Summary of show
     * @param EditLeadRequest $request
     * @param string $leadId
     * @return View|JsonResponse
     */
    public function show(EditLeadRequest $request, string $leadId): View | JsonResponse
    {
        $lead = $this->leadService->getByIdWithMorphsAndRelations($leadId, Lead::morphBuilder() , ['company','company.companyType','company.country']);

        $company = $this->companyService->getById($lead->getCompanyId());
        $users = $this->userService->getWithoutRole();

        $contactsColumns =  $this->companyService->getContactsTableColumns() ?? [];

        return view('backend.content.leads.show', compact('lead',  'company', 'contactsColumns', 'users'));
    }

    /**
     * Summary of create
     * @return View
     */
    public function create(): View
    {
        $companies = $this->companyService->get();
        $types = $this->companyTypeService->get();
        $countries = $this->countryCodeService->get();
        $sources = $this->companySourceService->get();

        // todo add sales role
        $salesPersons = $this->userService->getByRoleId(RolesEnum::Administrator->value);

        return view('backend.content.leads.create', compact('companies', 'types', 'countries', 'sources', 'salesPersons'));
    }

    /**
     * @param StoreLeadRequest $request
     * @return RedirectResponse
     */
    public function store(StoreLeadRequest $request): RedirectResponse
    {
        $companyDTO = new Company();

        $companyDTO->setErpId($request->input('erpId'));
        $companyDTO->setName($request->input('companyName'));
        // possible data
        $companyDTO->setEmail($request->input('email'));
        $companyDTO->setPhone($request->input('phone'));
        $companyDTO->setActivity($request->input('activity'));
        // end possible data

        $companyDTO->setTypeId($request->input('typeId'));
        $companyDTO->setSourceId($request->input('sourceId'));
        $companyDTO->setCountryId($request->input('countryId'));
        $companyDTO->setCity($request->input('city'));
        $companyDTO->setWebsite($request->input('website'));
        $companyDTO->setLinkedin($request->input('linkedin'));

        $company = $this->companyService->createOrUpdateByCompanyId($companyDTO, $request->input('existing_company_id'));

        $leadDTO  = (new Lead())->fromRequest($request);
        $leadDTO->setCompanyId($company->getId());
        $leadDTO->setSalesPersonId($request->input('salesPersonId'));

        $lead = $this->leadService->store($leadDTO);

        if($company->getId() !== null && $request['userIds'] !== null) {
            // Assign User to Company
            $companyDTO = new Company();
            $companyDTO->setUsers($request['userIds']);

            $this->companyService->storeContacts($companyDTO, $company->getId());
        }
dd($lead);

        return redirect()->route('admin.leads.index')->with('success', 'Το Lead δημιουργήθηκε με επιτυχία!');
    }

    /**
     * @param EditLeadRequest $request
     * @param string $leadId
     * @return View
     */
    public function edit(EditLeadRequest $request, string $leadId): View
    {
        $lead = $this->leadService->getById($leadId);

        return view('backend.content.leads.edit', compact('lead'));
    }

    /**
     * @param UpdateLeadRequest $request
     * @param string $leadId
     * @return RedirectResponse;.
     */
    public function update(UpdateLeadRequest $request, string $leadId): RedirectResponse
    {
        $this->leadService->update((new Lead())->fromRequest($request), $leadId);

        return redirect()->route('admin.leads.show', ['leadId' => $leadId])->with('success', 'Το Lead ενημερώθηκε με επιτυχία!');
    }

    /**
     * @param DeleteLeadRequest $request
     * @param string $leadId
     * @return RedirectResponse
     */
    public function destroy(DeleteLeadRequest $request, string $leadId): RedirectResponse
    {
        $response = $this->leadService->deleteById($leadId);

        if ($response) {
            return redirect()->route('admin.leads.index')->with('success', 'Το Lead διαγράφηκε με επιτυχία!');
        }

        return redirect()->route('admin.leads.index')->with('error', 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή!');
    }
}
