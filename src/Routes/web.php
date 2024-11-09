<?php

use Illuminate\Support\Facades\Route;
use ImAbuSayed\LaravelAiTranslator\Http\Livewire\TranslationManager;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/translations', TranslationManager::class)->name('translations.manager');
});