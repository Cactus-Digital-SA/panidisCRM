<?php

namespace App\Providers;

use App\Domains\Auth\Repositories\Eloquent\EloqPermissionRepository;
use App\Domains\Auth\Repositories\Eloquent\EloqRoleRepository;
use App\Domains\Auth\Repositories\Eloquent\EloqUserDetailsRepository;
use App\Domains\Auth\Repositories\Eloquent\EloqUserRepository;
use App\Domains\Auth\Repositories\Eloquent\Models\User;
use App\Domains\Auth\Repositories\PermissionRepositoryInterface;
use App\Domains\Auth\Repositories\RoleRepositoryInterface;
use App\Domains\Auth\Repositories\UserDetailsRepositoryInterface;
use App\Domains\Auth\Repositories\UserRepositoryInterface;
use App\Domains\Clients\Repositories\ClientRepositoryInterface;
use App\Domains\Clients\Repositories\Eloquent\EloqClientRepository;
use App\Domains\Clients\Repositories\Eloquent\Models\Client;
use App\Domains\Companies\Repositories\CompanyRepositoryInterface;
use App\Domains\Companies\Repositories\Eloquent\EloqCompanyRepository;
use App\Domains\Companies\Repositories\Eloquent\Models\Company;
use App\Domains\CompanySource\Repositories\CompanySourceRepositoryInterface;
use App\Domains\CompanySource\Repositories\Eloquent\EloqCompanySourceRepository;
use App\Domains\CompanyTypes\Repositories\CompanyTypeRepositoryInterface;
use App\Domains\CompanyTypes\Repositories\Eloquent\EloqCompanyTypeRepository;
use App\Domains\CountryCodes\Repositories\CountryCodeRepositoryInterface;
use App\Domains\CountryCodes\Repositories\Eloquent\EloqCountryCodeRepository;
use App\Domains\ExtraData\Repositories\Eloquent\EloqExtraDataRepository;
use App\Domains\ExtraData\Repositories\ExtraDataRepositoryInterface;
use App\Domains\Files\Repositories\Eloquent\EloqFileRepository;
use App\Domains\Files\Repositories\FileRepositoryInterface;
use App\Domains\Leads\Repositories\Eloquent\EloqLeadRepository;
use App\Domains\Leads\Repositories\Eloquent\Models\Lead;
use App\Domains\Leads\Repositories\LeadRepositoryInterface;
use App\Domains\Notes\Repositories\Eloquent\EloqNoteRepository;
use App\Domains\Notes\Repositories\NoteRepositoryInterface;
use App\Domains\Tags\Repositories\Eloquent\EloqTagRepository;
use App\Domains\Tags\Repositories\TagRepositoryInterface;
use App\Domains\Tickets\Repositories\Eloquent\EloqTicketRepository;
use App\Domains\Tickets\Repositories\Eloquent\EloqTicketStatusRepository;
use App\Domains\Tickets\Repositories\Eloquent\Models\Ticket;
use App\Domains\Tickets\Repositories\TicketRepositoryInterface;
use App\Domains\Tickets\Repositories\TicketStatusRepositoryInterface;
use App\Domains\Visits\Repositories\Eloquent\EloqVisitRepository;
use App\Domains\Visits\Repositories\Eloquent\EloqVisitStatusRepository;
use App\Domains\Visits\Repositories\Eloquent\Models\Visit;
use App\Domains\Visits\Repositories\VisitRepositoryInterface;
use App\Domains\Visits\Repositories\VisitStatusRepositoryInterface;
use App\Models\ModelMorphEnum;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloqUserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, EloqRoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, EloqPermissionRepository::class);
        $this->app->bind(UserDetailsRepositoryInterface::class, EloqUserDetailsRepository::class);
        $this->app->bind(FileRepositoryInterface::class, EloqFileRepository::class);
        $this->app->bind(NoteRepositoryInterface::class, EloqNoteRepository::class);
        $this->app->bind(ExtraDataRepositoryInterface::class, EloqExtraDataRepository::class);

        $this->app->bind(CountryCodeRepositoryInterface::class, EloqCountryCodeRepository::class);
        $this->app->bind(ClientRepositoryInterface::class, EloqClientRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, EloqCompanyRepository::class);
        $this->app->bind(CompanyTypeRepositoryInterface::class, EloqCompanyTypeRepository::class);
        $this->app->bind(CompanySourceRepositoryInterface::class, EloqCompanySourceRepository::class);
        $this->app->bind(LeadRepositoryInterface::class, EloqLeadRepository::class);
        $this->app->bind( TicketRepositoryInterface::class, EloqTicketRepository::class);
        $this->app->bind( TicketStatusRepositoryInterface::class, EloqTicketStatusRepository::class);
        $this->app->bind( VisitRepositoryInterface::class, EloqVisitRepository::class);
        $this->app->bind( VisitStatusRepositoryInterface::class, EloqVisitStatusRepository::class);
        $this->app->bind(TagRepositoryInterface::class, EloqTagRepository::class);

        $this->relations();

    }

    /**
     * Eloquent Models Morphs Relation Classes.
     */
    public function relations(): void
    {
        Relation::morphMap([
            ModelMorphEnum::USER->value => User::class,
            ModelMorphEnum::CLIENT->value => Client::class,
//            ModelMorphEnum::PROJECT->value => Project::class,
            ModelMorphEnum::TICKET->value => Ticket::class,
            ModelMorphEnum::LEAD->value => Lead::class,
            ModelMorphEnum::COMPANY->value => Company::class,
            ModelMorphEnum::VISIT->value => Visit::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
