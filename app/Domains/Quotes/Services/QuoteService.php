<?php

namespace App\Domains\Quotes\Services;

use App\Domains\Quotes\Models\Quote;
use App\Domains\Quotes\Repositories\QuoteRepositoryInterface;
use Illuminate\Http\JsonResponse;

class QuoteService
{
    private QuoteRepositoryInterface $repository;

    /**
     * @param QuoteRepositoryInterface $repository
     */
    public function __construct(QuoteRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Quote[]
     */
    public function get(): array
    {
        return $this->repository->get();
    }

    /**
     * @param string $id
     * @return Quote|null
     */
    public function getById(string $id): ?Quote
    {
        return $this->repository->getById($id);
    }

    /**
     * @param string $uuid
     * @return Quote|null
     */
    public function getByUuid(string $uuid): ?Quote
    {
        return $this->repository->getByUuid($uuid);
    }

    /**
     * @param Quote $quote
     * @return Quote
     */
    public function store(Quote $quote): Quote
    {
        return $this->repository->store($quote);
    }

    /**
     * @param Quote $quote
     * @param string $id
     * @return Quote
     */
    public function update(Quote $quote, string $id): Quote
    {
        return $this->repository->update($quote, $id);
    }

    /**
     * @param Quote $quote
     * @param string $id
     * @return Quote
     */
    public function updateStatus(Quote $quote, string $id): Quote
    {
        return $this->repository->updateStatus($quote, $id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id): bool
    {
        return $this->repository->deleteById($id);
    }

    /**
     * @param array $filters
     * @return JsonResponse
     */
    public function dataTableQuotes(array $filters = []): \Illuminate\Http\JsonResponse
    {
        return $this->repository->dataTableQuotes($filters);
    }

    /**
     * @return array|null
     */
    public function getTableColumns(): ?array
    {
        return $this->repository->getTableColumns();
    }

    /**
     * @param string $id
     * @return Quote|null
     */
    public function getByIdWithMorphsAndRelations(string $id): ?Quote
    {
        return $this->repository->getByIdWithMorphsAndRelations($id);
    }
}
