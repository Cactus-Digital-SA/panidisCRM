@if($company)
<div class="card">
    <div class="card-header d-flex justify-content-between mb-3 pb-0">
        <h5 class="card-title m-0 me-2 pt-1 mb-2 d-flex align-items-center"><i
                class="fa fa-users-viewfinder me-3"></i> {{ __('Company') }}</h5>
        <div class="card-header-elements ms-auto">
            @if($company->getClient())
                <a href="{{route('admin.clients.show', $company->getClient()?->getId())}}" class="btn btn-xs btn-success waves-effect"><span class="tf-icon fa fa-eye fa-xs me-1"></span>{{__('View')}} Client</a>
            @elseif($company->getLead())
                <a href="{{route('admin.leads.show', $company->getLead()?->getId())}}" class="btn btn-xs btn-success waves-effect"><span class="tf-icon fa fa-eye fa-xs me-1"></span>{{__('View')}} Lead</a>
            @endif
            <a href="{{route('admin.companies.edit', $company->getId())}}" class="btn btn-xs btn-primary waves-effect"><span class="tf-icon fa fa-pen fa-xs me-1"></span>{{__('Edit')}}</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-sm">
                <tbody class="table-border-bottom-0">
                <tr>
                    <td style="width: 10%;"><span class="fw-bold">ERP ID</span></td>
                    <td>{{ $company->getErpId() }}</td>
                </tr>
                <tr>
                    <td style="width: 10%;"><span class="fw-bold">Όνομα</span></td>
                    <td>{{ $company->getName() }}</td>
                </tr>
{{--                <tr>--}}
{{--                    <td style="width: 10%;"><span class="fw-bold">Email</span></td>--}}
{{--                    <td>{{ $company->getEmail() }}</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <td style="width: 10%;"><span class="fw-bold">Τηλέφωνο</span></td>--}}
{{--                    <td>{{ $company->getPhone() }}</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <td style="width: 10%;"><span class="fw-bold">Δραστηριότητα</span></td>--}}
{{--                    <td>{{ $company->getActivity() }}</td>--}}
{{--                </tr>--}}
                <tr>
                    <td style="width: 10%;"><span class="fw-bold">Κατηγορία Πελάτη</span></td>
                    <td>{{ $company->getCompanyType()?->getName() }}</td>
                </tr>
                <tr>
                    <td style="width: 10%;"><span class="fw-bold">Τομέας</span></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width: 10%;"><span class="fw-bold">Source Channel</span></td>
                    <td>{{ $company->getCompanySource()?->getName() }}</td>
                </tr>
                <tr>
                    <td style="width: 10%;"><span class="fw-bold">Χώρα</span></td>
                    <td>{{ $company->getCountry()?->getName() }}</td>
                </tr>
                <tr>
                    <td style="width: 10%;"><span class="fw-bold">Χώρα</span></td>
                    <td>{{ $company->getCity() }}</td>
                </tr>

                <tr>
                    <td style="width: 10%;"><span class="fw-bold">Website</span></td>
                    <td>{{ $company->getWebsite() }}</td>
                </tr>
                <tr>
                    <td style="width: 10%;"><span class="fw-bold">Linkedin</span></td>
                    <td>{{ $company->getLinkedin() }}</td>
                </tr>


                @if($companyExtraData ?? null)
                    @include('backend.content.extraData.components.extraDataShowOnTable',
                      [
                          'extraData' => $companyExtraData,
                          'model' => $company,
                          'labelCol' => 'col-md-2',
                          'fieldCol' => 'col-md-10'
                      ])
                @endif

                </tbody>
            </table>
        </div>

    </div>
</div>
@endif
