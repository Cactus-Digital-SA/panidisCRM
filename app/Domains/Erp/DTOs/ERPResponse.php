<?php

namespace App\Domains\Erp\DTOs;

class ERPResponse
{
    private ?array $data = null;
    private ?string $responseBody = null;
    private ?int $statusCode = null;

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): ERPResponse
    {
        $this->data = $data;
        return $this;
    }

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    public function setResponseBody(?string $responseBody): ERPResponse
    {
        $this->responseBody = $responseBody;
        return $this;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setStatusCode(?int $statusCode): ERPResponse
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}