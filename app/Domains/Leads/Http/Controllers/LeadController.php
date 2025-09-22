<?php

namespace App\Domains\Leads\Http\Controllers;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Companies\Models\Company;
use App\Domains\CompanySource\Services\CompanySourceService;
use App\Domains\CountryCodes\Services\CountryCodeService;
use App\Domains\Leads\Services\ConvertLeadEventService;
use App\Domains\Tags\Enums\TagTypesEnum;
use App\Domains\Tags\Services\TagService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
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
        protected TagService $tagService
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
        $lead = $this->leadService->getByIdWithMorphsAndRelations($leadId, Lead::morphBuilder() , ['company','company.companyType','company.companySource','company.country', 'tags']);

        $tags = $this->tagService->getByType(TagTypesEnum::PRODUCT->value);

        $company = $this->companyService->getById($lead->getCompanyId());
        $users = $this->userService->getWithoutRole();

        $contactsColumns =  $this->companyService->getContactsTableColumns() ?? [];

        $salesPersonsATH = $this->userService->getByRoleId(RolesEnum::SALES_ATH->value);
        $salesPersonsSKG = $this->userService->getByRoleId(RolesEnum::SALES_SKG->value);

        $mergedSalesPersons = array_merge($salesPersonsATH, $salesPersonsSKG);
        $salesPersons = array_unique($mergedSalesPersons, SORT_REGULAR);

        return view('backend.content.leads.show', compact('lead',  'company', 'contactsColumns', 'users', 'salesPersons', 'tags'));
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
        $tags = $this->tagService->getByType(TagTypesEnum::PRODUCT->value);

        // todo add sales role
        $salesPersonsATH = $this->userService->getByRoleId(RolesEnum::SALES_ATH->value);
        $salesPersonsSKG = $this->userService->getByRoleId(RolesEnum::SALES_SKG->value);

        $mergedSalesPersons = array_merge($salesPersonsATH, $salesPersonsSKG);
        $salesPersons = array_unique($mergedSalesPersons, SORT_REGULAR);

        return view('backend.content.leads.create', compact('companies', 'types', 'countries', 'sources', 'salesPersons', 'tags'));
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
        $leadDTO->setTagIds($request->input('tagIds') ?? []);

        $lead = $this->leadService->store($leadDTO);

        if($company->getId() !== null && $request['userIds'] !== null) {
            // Assign User to Company
            $companyDTO = new Company();
            $companyDTO->setUsers($request['userIds']);

            $this->companyService->storeContacts($companyDTO, $company->getId());
        }

        return redirect()->route('admin.leads.index')->with('success', 'Επιτυχής αποθήκευση!');
    }

    /**
     * @param EditLeadRequest $request
     * @param string $leadId
     * @return RedirectResponse
     */
    public function edit(EditLeadRequest $request, string $leadId): RedirectResponse
    {
        return redirect()->route('admin.leads.show', ['leadId' => $leadId]);
    }

    /**
     * @param UpdateLeadRequest $request
     * @param string $leadId
     * @return RedirectResponse;.
     */
    public function update(UpdateLeadRequest $request, string $leadId): RedirectResponse
    {
        $leadDTO  = (new Lead())->fromRequest($request);
        $leadDTO->setTagIds($request->input('tagIds') ?? []);

        $this->leadService->update($leadDTO, $leadId);

        return redirect()->route('admin.leads.show', ['leadId' => $leadId])->with('success', 'Επιτυχής αποθήκευση!');
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
            return redirect()->route('admin.leads.index')->with('success', 'Επιτυχής διαγραφή!');
        }

        return redirect()->route('admin.leads.index')->with('error', 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή!');
    }

    public function convertLead(\Request $request, string $leadId): RedirectResponse
    {
        $lead = $this->leadService->getById($leadId);

        $company = $this->companyService->getById($lead->getCompanyId());
        $company->setErpId(rand(100000, 999999));
        $this->companyService->updateErpIdByCompanyId($company, $lead->getCompanyId());

        $convertLeadEventService = app(ConvertLeadEventService::class);
        $response = $convertLeadEventService->convertEvent($lead);

        if($response){
            return redirect()->route('admin.clients.index')->with('success', 'Επιτυχής Μετατροπή!');
        }

        return redirect()->route('admin.leads.index')->with('error', 'Υπήρξε κάποιο πρόβλημα κατά την διαγραφή!');
    }
}
