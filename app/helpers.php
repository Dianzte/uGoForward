<?php

use App\Services\TranslationService;

if (!function_exists('translate_db')) {
    /**
     * Translates dynamic database text based on current locale.
     */
    function translate_db(?string $text, ?string $targetLocale = null, string $sourceLocale = 'es'): string
    {
        if (empty($text)) {
            return '';
        }

        return app(TranslationService::class)->translate($text, $targetLocale, $sourceLocale);
    }
}

if (!function_exists('translate_array')) {
    /**
     * Translates an array of strings.
     */
    function translate_array(?array $items, ?string $targetLocale = null, string $sourceLocale = 'es'): array
    {
        if (empty($items)) {
            return [];
        }

        return app(TranslationService::class)->translateArray($items, $targetLocale, $sourceLocale);
    }
}
