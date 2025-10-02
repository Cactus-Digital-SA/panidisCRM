<?php

namespace App\Domains\Erp\DTOs;

class ERPReport
{
    private string $reportId;
    private int $pages;

    public function getReportId(): string
    {
        return $this->reportId;
    }

    public function setReportId(string $reportId): ERPReport
    {
        $this->reportId = $reportId;
        return $this;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function setPages(int $pages): ERPReport
    {
        $this->pages = $pages;
        return $this;
    }
}