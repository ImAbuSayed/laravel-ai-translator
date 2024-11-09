<?php

namespace ImAbuSayed\LaravelAiTranslator\Tests;

use Orchestra\Testbench\TestCase;
use ImAbuSayed\LaravelAiTranslator\Services\TranslationService;
use ImAbuSayed\LaravelAiTranslator\Providers\LaravelAiTranslatorServiceProvider;

class TranslationServiceTest extends TestCase
{
    protected $translationService;

    protected function getPackageProviders($app)
    {
        return [LaravelAiTranslatorServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->translationService = $this->app->make(TranslationService::class);
    }

    public function test_can_extract_translatable_strings()
    {
        $content = <<<'BLADE'
            <div>
                {{ __('Welcome') }}
                {{ __('Hello, :name', ['name' => $user]) }}
                @lang('messages.greeting')
            </div>
        BLADE;

        $strings = $this->translationService->extractTranslatableStrings($content);

        $this->assertContains('Welcome', $strings);
        $this->assertContains('Hello, :name', $strings);
        $this->assertContains('messages.greeting', $strings);
    }

    public function test_can_translate_string()
    {
        // Mock OpenAI response
        $this->mock(OpenAI::class, function ($mock) {
            $mock->shouldReceive('chat->create')
                ->andReturn((object)[
                    'choices' => [(object)[
                        'message' => (object)['content' => 'Hola']
                    ]]
                ]);
        });

        $translation = $this->translationService->translateString('Hello', 'es');
        
        $this->assertEquals('Hola', $translation);
    }
}