<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class UnzipFiles extends Command
{
    protected $signature = 'files:unzip {filename}';
    protected $description = 'Unzip files to storage/app/files';

    public function handle()
    {
        $filename = $this->argument('filename');
        $path = storage_path('app/' . $filename);
        $extractPath = storage_path('app/');

        $zip = new ZipArchive;
        if ($zip->open($path) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
            $this->info("✅ Το zip {$filename} αποσυμπιέστηκε στο storage/app/files");
        } else {
            $this->error("❌ Αποτυχία στο άνοιγμα του zip: {$filename}");
        }

        $this->cleanJunk($extractPath);
    }

    protected function cleanJunk($path)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $filePath = $fileinfo->getPathname();
            if (strpos($filePath, '__MACOSX') !== false || strpos($filePath, '.DS_Store') !== false) {
                if ($fileinfo->isDir()) {
                    @rmdir($filePath);
                } else {
                    @unlink($filePath);
                }
            }
        }
    }
}
