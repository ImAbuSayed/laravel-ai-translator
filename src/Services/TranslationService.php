<?php

namespace ImAbuSayed\LaravelAiTranslator\Services;

use OpenAI;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class TranslationService
{
    protected $openai;
    
    public function __construct()
    {
        $this->openai = OpenAI::client(config('ai-translator.openai_api_key'));
    }

    public function translateString(string $text, string $targetLocale): string
    {
        $cacheKey = "translation_{$text}_{$targetLocale}";
        
        return Cache::remember($cacheKey, 60 * 24, function () use ($text, $targetLocale) {
            $response = $this->openai->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are a professional translator. Translate the following text to {$targetLocale} language. Maintain the original meaning and context."
                    ],
                    [
                        'role' => 'user',
                        'content' => $text
                    ]
                ]
            ]);

            return $response->choices[0]->message->content;
        });
    }

    public function extractTranslatableStrings(string $content): array
    {
        preg_match_all('/__\([\'"](.+?)[\'"]\)/', $content, $matches);
        return array_unique($matches[1]);
    }

    public function saveTranslation(string $key, string $translation, string $locale): void
    {
        $path = lang_path("{$locale}.json");
        
        $translations = File::exists($path) 
            ? json_decode(File::get($path), true) 
            : [];
            
        $translations[$key] = $translation;
        
        File::put($path, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // need to check duplicates function below are latest

    protected $translationMemory;

public function __construct(TranslationMemoryService $translationMemory)
{
    $this->openai = OpenAI::client(config('ai-translator.openai_api_key'));
    $this->translationMemory = $translationMemory;
}

public function translateString(string $text, string $targetLocale): string
{
    // Check translation memory first
    if (config('ai-translator.translation_memory_enabled')) {
        $memorizedTranslation = $this->translationMemory->findSimilarTranslation(
            $text,
            config('app.locale'),
            $targetLocale
        );

        if ($memorizedTranslation) {
            return $memorizedTranslation;
        }
    }

    // Fallback to AI translation
    $translation = $this->translateWithAI($text, $targetLocale);

    // Store in translation memory
    if (config('ai-translator.translation_memory_enabled')) {
        $this->translationMemory->store(
            $text,
            $translation,
            config('app.locale'),
            $targetLocale
        );
    }

    return $translation;
}

protected function translateWithAI(string $text, string $targetLocale): string
{
    $response = $this->openai->chat()->create([
        'model' => config('ai-translator.openai_model'),
        'messages' => [
            [
                'role' => 'system',
                'content' => "You are a professional translator. Translate the following text to {$targetLocale} language. Maintain the original meaning and context."
            ],
            [
                'role' => 'user',
                'content' => $text
            ]
        ]
    ]);

    return $response->choices[0]->message->content;
}

}