<?php

namespace ImAbuSayed\LaravelAiTranslator\Services;

use Illuminate\Support\Facades\File;
use ZipArchive;

class TranslationExportService
{
    public function export(): string
    {
        $zipPath = storage_path('app/translations.zip');
        $zip = new ZipArchive();
        
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $langPath = lang_path();
            
            $files = File::allFiles($langPath);
            
            foreach ($files as $file) {
                $zip->addFile($file->getRealPath(), $file->getRelativePathname());
            }
            
            $zip->close();
        }
        
        return $zipPath;
    }

    public function import(string $zipPath): void
    {
        $zip = new ZipArchive();
        
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo(lang_path());
            $zip->close();
        }
    }
}