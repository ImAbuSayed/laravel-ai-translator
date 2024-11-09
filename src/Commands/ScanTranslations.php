<?php

namespace ImAbuSayed\LaravelAiTranslator\Commands;

use Illuminate\Console\Command;
use ImAbuSayed\LaravelAiTranslator\Services\TranslationService;

class ScanTranslations extends Command
{
    protected $signature = 'translations:scan {locale?} {--translate : Translate missing strings}';
    protected $description = 'Scan and optionally translate missing translations';

    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        parent::__construct();
        $this->translationService = $translationService;
    }

    public function handle()
    {
        $locale = $this->argument('locale');
        $shouldTranslate = $this->option('translate');

        $this->info('Scanning for missing translations...');
        
        $missing = $this->translationService->scanMissingTranslations();
        
        if (empty($missing)) {
            $this->info('No missing translations found.');
            return;
        }

        foreach ($missing as $targetLocale => $items) {
            if ($locale && $targetLocale !== $locale) {
                continue;
            }

            $this->info("\nMissing translations for {$targetLocale}:");
            
            $bar = $this->output->createProgressBar(count($items));
            $bar->start();

            foreach ($items as $key => $text) {
                $this->line("  - {$key}");
                
                if ($shouldTranslate) {
                    try {
                        $translation = $this->translationService->translateString($text, $targetLocale);
                        $this->translationService->saveTranslation($key, $translation, $targetLocale);
                        $this->line("    ✓ Translated");
                    } catch (\Exception $e) {
                        $this->error("    ✗ Failed: {$e->getMessage()}");
                    }
                }
                
                $bar->advance();
            }

            $bar->finish();
        }

        $this->info("\nScan completed!");
    }
}