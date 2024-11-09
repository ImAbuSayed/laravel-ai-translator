<?php

namespace ImAbuSayed\LaravelAiTranslator\Http\Livewire;

use Livewire\Component;
use ImAbuSayed\LaravelAiTranslator\Services\TranslationService;
use Illuminate\Support\Facades\File;

class TranslationManager extends Component
{
    public $selectedLocale = '';
    public $availableLocales = [];
    public $translations = [];
    public $selectedFile = '';
    public $files = [];
    public $isLoading = false;
    public $searchQuery = '';
    public $selectedView = '';
    public $viewFiles = [];

    protected $translationService;

    public function mount(TranslationService $translationService)
    {
        $this->translationService = $translationService;
        $this->availableLocales = config('ai-translator.supported_locales', ['en']);
        $this->loadViewFiles();
        $this->loadTranslationFiles();
    }

    public function loadViewFiles()
    {
        $viewPath = resource_path('views');
        $this->viewFiles = collect(File::allFiles($viewPath))
            ->map(fn($file) => $file->getRelativePathname())
            ->filter(fn($file) => pathinfo($file, PATHINFO_EXTENSION) === 'blade.php')
            ->values()
            ->toArray();
    }

    public function loadTranslationFiles()
    {
        $this->files = collect(File::files(lang_path()))
            ->map(fn($file) => $file->getFilename())
            ->filter(fn($file) => in_array(pathinfo($file, PATHINFO_EXTENSION), ['php', 'json']))
            ->values()
            ->toArray();
    }

    public function translateView()
    {
        $this->isLoading = true;
        
        try {
            $content = File::get(resource_path("views/{$this->selectedView}"));
            $strings = $this->translationService->extractTranslatableStrings($content);
            
            foreach ($this->availableLocales as $locale) {
                if ($locale === config('app.locale')) continue;
                
                foreach ($strings as $string) {
                    $translation = $this->translationService->translateString($string, $locale);
                    $this->translationService->saveTranslation($string, $translation, $locale);
                }
            }
            
            $this->notify('View translations completed successfully!');
        } catch (\Exception $e) {
            $this->notify('Error translating view: ' . $e->getMessage(), 'error');
        }
        
        $this->isLoading = false;
    }

    public function translateMissing()
    {
        $this->isLoading = true;
        
        try {
            $missing = $this->translationService->scanMissingTranslations();
            
            foreach ($missing as $locale => $strings) {
                foreach ($strings as $string) {
                    $translation = $this->translationService->translateString($string, $locale);
                    $this->translationService->saveTranslation($string, $translation, $locale);
                }
            }
            
            $this->notify('Missing translations completed successfully!');
        } catch (\Exception $e) {
            $this->notify('Error translating missing strings: ' . $e->getMessage(), 'error');
        }
        
        $this->isLoading = false;
    }

    public function render()
    {
        return view('ai-translator::translation-manager');
    }
}