<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    /**
     * Translates a text string to the target locale with permanent caching.
     */
    public function translate(?string $text, ?string $targetLocale = null, string $sourceLocale = 'es'): string
    {
        if (empty($text)) {
            return '';
        }

        $targetLocale = $targetLocale ?: app()->getLocale();

        // If target locale is the same as source locale, return original text
        if ($targetLocale === $sourceLocale) {
            return $text;
        }

        // Generate a unique cache key based on content and target language
        $cacheKey = 'trans_' . $targetLocale . '_' . md5($text);

        return Cache::rememberForever($cacheKey, function () use ($text, $targetLocale, $sourceLocale) {
            return $this->fetchFromGoogleTranslate($text, $targetLocale, $sourceLocale);
        });
    }

    /**
     * Translates an array of strings.
     */
    public function translateArray(?array $items, ?string $targetLocale = null, string $sourceLocale = 'es'): array
    {
        if (empty($items)) {
            return [];
        }

        return array_map(function ($item) use ($targetLocale, $sourceLocale) {
            return is_string($item) ? $this->translate($item, $targetLocale, $sourceLocale) : $item;
        }, $items);
    }

    /**
     * Fetches translation from Google Translate Free API.
     */
    protected function fetchFromGoogleTranslate(string $text, string $targetLocale, string $sourceLocale): string
    {
        try {
            $url = 'https://translate.googleapis.com/translate_a/single';

            $response = Http::withoutVerifying()
                ->timeout(6)
                ->get($url, [
                    'client' => 'gtx',
                    'sl'     => $sourceLocale,
                    'tl'     => $targetLocale,
                    'dt'     => 't',
                    'q'      => $text,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                if (is_array($data) && isset($data[0]) && is_array($data[0])) {
                    $translatedText = '';
                    foreach ($data[0] as $segment) {
                        if (isset($segment[0])) {
                            $translatedText .= $segment[0];
                        }
                    }
                    return !empty($translatedText) ? $translatedText : $text;
                }
            }

            Log::warning('Translation API non-successful response', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Translation API error: ' . $e->getMessage());
        }

        // Graceful fallback to original text
        return $text;
    }
}
