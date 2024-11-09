# Laravel AI Translator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/imabusayed/laravel-ai-translator.svg?style=flat-square)](https://packagist.org/packages/imabusayed/laravel-ai-translator)
[![Total Downloads](https://img.shields.io/packagist/dt/imabusayed/laravel-ai-translator.svg?style=flat-square)](https://packagist.org/packages/imabusayed/laravel-ai-translator)
[![License](https://img.shields.io/packagist/l/imabusayed/laravel-ai-translator.svg?style=flat-square)](https://packagist.org/packages/imabusayed/laravel-ai-translator)

An AI-powered Laravel Translation Manager with OpenAI integration and UI.

## Features

- 🤖 AI-powered translation using OpenAI
- 🔍 Missing translation detection
- 💾 Translation memory system
- 📤 Import/export functionality
- 👀 Real-time preview
- 🌐 Support for multiple locales
- ⚡ Command-line interface
- 🎨 User-friendly UI

## Installation

1. Install the package via composer:
```bash
composer require imabusayed/laravel-ai-translator
```

2. Publish the configuration file:
```bash
php artisan vendor:publish --provider="ImAbuSayed\LaravelAiTranslator\Providers\LaravelAiTranslatorServiceProvider"
```

3. Add your OpenAI API key to your .env file:
```
OPENAI_API_KEY=your-api-key
```

4. Run the migrations:
```bash
php artisan migrate
```

## Usage

### Web Interface

Access the translation manager at `/translations` route.

### Command Line

Scan for missing translations:
```bash
php artisan translations:scan
```

Scan and translate missing strings:
```bash
php artisan translations:scan --translate
```

Scan specific locale:
```bash
php artisan translations:scan es --translate
```

### In Your Code

```php
use ImAbuSayed\LaravelAiTranslator\Services\TranslationService;

public function example(TranslationService $translator)
{
    $translation = $translator->translateString('Hello, World!', 'es');
    // Returns: "¡Hola, Mundo!"
}
```

## Configuration

The configuration file (`config/ai-translator.php`) includes:

- OpenAI API settings
- Supported locales
- Cache configuration
- Scan paths
- Translation memory settings

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email abusayedofficialbd@gmail.com.

## Credits

- [Abu Sayed](https://github.com/ImAbuSayed)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Security

If you discover any security related issues, please email hi@abusayed.com.bd instead of using the issue tracker.

