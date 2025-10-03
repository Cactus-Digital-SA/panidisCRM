<?php

namespace App\Domains\Erp\Client;

use App\Domains\Erp\DTOs\ErpCredentials;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AuthClient
{
    private const AUTH_URL = "https://crmpanidis.oncloud.gr/s1services";
    private ErpCredentials $clientCredentials;
    private ?APIClient $client = null;

    public function __construct(?ErpCredentials $clientCredentials = null, ?APIClient $client = null)
    {
        if (!$clientCredentials) {
            $this->clientCredentials = new ErpCredentials();
        }

        if (!$client) {
            $guzzleClient = new Client([
                'base_uri' => self::AUTH_URL,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $this->client = new ApiClient($guzzleClient);
        }
    }

    /**
     * @throws Exception
     */
    public function authenticate(): ?string
    {
        $clientId = $this->login();
        $response = $this->client->post("", $this->getAuthenticateCredentials($clientId))->getData();

        if ($response && isset($response['success']) && $response['success'] && array_key_exists('clientID', $response)) {
            return $response['clientID'];
        }
        return null;
    }

    /**
     * @throws Exception
     */
    private function login(): string
    {
        try {
            $response = $this->client->post("", $this->getLoginServiceCredentials())->getData();

            if ($response && isset($response['success']) && $response['success'] && array_key_exists('clientID', $response)) {
                return $response['clientID'];
            }

            throw new Exception("Login Service Error: " . json_encode($response));

        } catch (Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getLoginServiceCredentials(): array
    {
        return [
            "service" => $this->clientCredentials->getLoginService(),
            "username" => $this->clientCredentials->getUsername(),
            "password" => $this->clientCredentials->getPassword(),
            "appId" => $this->clientCredentials->getAppId(),
        ];
    }

    private function getAuthenticateCredentials(string $clientId): array
    {
        return [
            "CLIENTID" => $clientId,
            "APPID" => $this->clientCredentials->getAppId(),
            "SERVICE" => $this->clientCredentials->getAuthenticateService(),
            "COMPANY" => $this->clientCredentials->getCompany(),
            "BRANCH" => $this->clientCredentials->getBranch(),
            "REFID" => $this->clientCredentials->getRefId(),
        ];
    }
}
