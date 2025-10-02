<?php

namespace App\Domains\Erp\Client;

use App\Domains\Erp\DTOs\ErpCredentials;
use App\Domains\Erp\DTOs\ERPResponse;
use Exception;
use GuzzleHttp\Client;

class ErpClient
{
    private const BASE_URL = "https://crmpanidis.oncloud.gr";
    private ?string $clientId = null;

    private ?ErpCredentials $credentials = null;

    public function __construct(private ?ApiClient $client = null,)
    {}

    /**
     * @throws Exception
     */
    public function getClient(): ErpClient
    {
        if ($this->client === null) {
            $this->init();
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function init(): void
    {
        $this->clientId = (new AuthClient())->authenticate();
        $this->credentials = new ErpCredentials();

        $this->client = new ApiClient(new Client([
            'base_uri' => rtrim(self::BASE_URL, '/'),
            'headers' => [
                'Accept' => 'application/json'
            ],
        ]));
    }

    public function get(string $endpoint, array $query = []): ?ErpResponse
    {
        return $this->client->get($endpoint, $this->mergeCredentials($query));
    }

    private function mergeCredentials(array $query): array
    {
        return array_merge($query, $this->getAuthenticateCredentials($this->clientId));
    }

    private function getAuthenticateCredentials(string $clientId): array
    {
        return [
            "ClientId" => $clientId
        ];
    }

    public function post(string $endpoint, array $query = []): ErpResponse
    {
        $params = $this->mergeCredentials($query);
        return $this->client->post($endpoint, $params);
    }
}
