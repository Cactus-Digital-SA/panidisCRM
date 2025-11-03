<?php

namespace App\Console\Commands;

use App\Domains\Erp\Services\ErpService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncItems extends Command
{
    protected $signature = 'erp:sync-items';
    protected $description = 'Sync Items from ERP';


    public function __construct(
        private readonly ErpService $erpService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $startTime = microtime(true);

        $limit = "";
        $customerERPId = null;

        $this->info('Starting ERP items sync...');

        try {
            $this->erpService->getItems($limit, $customerERPId);
            $this->info('ERP items sync completed successfully!');
        } catch (\Throwable $e) {
            $this->error('Error syncing ERP items: ' . $e->getMessage());
        }

        $duration = round(microtime(true) - $startTime, 2);

        Log::info('Sync Items from ERP', [
            'duration_sec' => $duration,
        ]);
    }
}
