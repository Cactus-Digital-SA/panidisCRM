<?php

namespace App\Domains\Erp\DTOs;

class ErpCredentials
{
    private ?string $loginService;
    private ?string $authenticateService;
    private ?string $username;
    private ?string $password;
    private ?string $appId;
    private ?string $company;
    private ?string $branch;
    private ?string $refId;
    private ?string $clientId;

    public function __construct()
    {
        $this->loginService = env('ERP_LOGIN_SERVICE');
        $this->authenticateService = env('ERP_AUTHENTICATE_SERVICE');
        $this->username = env('ERP_USERNAME');
        $this->password = env('ERP_PASSWORD');
        $this->appId = env('ERP_APP_ID');
        $this->company = env('ERP_COMPANY');
        $this->branch = env('ERP_BRANCH');
        $this->refId = env('ERP_REF_ID');
    }

    public function getLoginService(): ?string
    {
        return $this->loginService;
    }

    public function getAuthenticateService(): ?string
    {
        return $this->authenticateService;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getAppId(): ?string
    {
        return $this->appId;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function getBranch(): ?string
    {
        return $this->branch;
    }

    public function getRefId(): ?string
    {
        return $this->refId;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }
}
