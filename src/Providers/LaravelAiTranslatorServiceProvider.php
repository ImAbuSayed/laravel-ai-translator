<?php

namespace ImAbuSayed\LaravelAiTranslator\Providers;

use Illuminate\Support\ServiceProvider;
use ImAbuSayed\LaravelAiTranslator\Commands\ScanTranslations;
use ImAbuSayed\LaravelAiTranslator\Services\TranslationService;

class LaravelAiTranslatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Config
        $this->publishes([
            __DIR__.'/../Config/ai-translator.php' => config_path('ai-translator.php'),
        ], 'config');

        // Views
        $this->loadViewsFrom(__DIR__.'/../Views', 'ai-translator');
        $this->publishes([
            __DIR__.'/../Views' => resource_path('views/vendor/ai-translator'),
        ], 'views');

        // Routes
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        // Migrations
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        // Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                ScanTranslations::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/ai-translator.php', 'ai-translator'
        );

        $this->app->singleton(TranslationService::class, function ($app) {
            return new TranslationService();
        });
    }
}