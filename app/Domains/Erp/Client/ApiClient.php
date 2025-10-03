<?php

namespace App\Domains\Erp\Client;

use App\Domains\Erp\DTOs\ERPResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;
use Throwable;

class ApiClient
{
    protected string $baseUrl;
    protected ?string $bearer = null;

    public function __construct(private readonly Client $client)
    {}

    public function request(string $method, string $endpoint, array $options = []): ERPResponse
    {
        $response = null;
        try {
            $response = $this->client->request($method, $endpoint, $options);
        } catch (GuzzleException $e) {
            Log::error('Request failed: ' . $e->getMessage());
        }

        return $this->decodeResponse($response);
    }

    public function get(string $endpoint, array $query = []): ERPResponse
    {
        return $this->request('GET', $endpoint, [RequestOptions::QUERY => $query]);
    }

    public function post(string $endpoint, array $data = []): ERPResponse
    {
        return $this->request('POST', $endpoint, [RequestOptions::JSON => $data]);
    }

    protected function decodeResponse(Response $response): ErpResponse
    {
        $res = new ERPResponse();
        if (!$response) {
            return $res;
        }

        $erpResponse = iconv(
            'Windows-1253',
            "UTF-8//TRANSLIT//IGNORE",
            $response->getBody()->getContents()
        );

        $res->setStatusCode($response->getStatusCode());
        $res->setResponseBody($erpResponse);
        try {
            $res->setData(json_decode($erpResponse, true));
        } catch (Throwable $e) {
        }
        return $res;
    }
}
