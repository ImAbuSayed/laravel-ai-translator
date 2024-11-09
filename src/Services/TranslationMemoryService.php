<?php

namespace ImAbuSayed\LaravelAiTranslator\Services;

use Illuminate\Support\Facades\DB;

class TranslationMemoryService
{
    protected $similarityThreshold;

    public function __construct()
    {
        $this->similarityThreshold = config('ai-translator.translation_memory_threshold', 0.9);
    }

    public function findSimilarTranslation(string $text, string $sourceLocale, string $targetLocale): ?string
    {
        $result = DB::table('translation_memory')
            ->where('source_locale', $sourceLocale)
            ->where('target_locale', $targetLocale)
            ->where('similarity_score', '>=', $this->similarityThreshold)
            ->whereRaw('MATCH(source_text) AGAINST(? IN NATURAL LANGUAGE MODE)', [$text])
            ->first();

        return $result ? $result->translated_text : null;
    }

    public function store(string $sourceText, string $translatedText, string $sourceLocale, string $targetLocale): void
    {
        DB::table('translation_memory')->insert([
            'source_locale' => $sourceLocale,
            'target_locale' => $targetLocale,
            'source_text' => $sourceText,
            'translated_text' => $translatedText,
            'similarity_score' => 1.0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}